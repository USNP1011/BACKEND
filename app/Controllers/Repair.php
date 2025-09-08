<?php

namespace App\Controllers;

use Ramsey\Uuid\Uuid;
use App\Libraries\Rest;
use App\Models\MahasiswaModel;
use App\Entities\Mahasiswa  as EntitiesMahasiswa;
use App\Models\NilaiTransferModel;
use App\Models\PerkuliahanMahasiswaModel;
use App\Models\PesertaKelasModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use App\Models\UserRoleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Repair extends BaseController
{
    protected $conn;
    public function __construct()
    {
        $this->conn = \Config\Database::connect();
    }
    public function repair()
    {
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        // $data = $riwayat->where('id_jenis_keluar IS NULL')->findAll();
        $pesertaKelas = new \App\Models\PesertaKelasModel();
        $dataPeserta = $pesertaKelas
            ->select("peserta_kelas.id_riwayat_pendidikan, peserta_kelas.kelas_kuliah_id, nilai_kelas.*, kelas_kuliah.id_semester, , kelas_kuliah.matakuliah_id, matakuliah.sks_mata_kuliah")
            ->join('kelas_kuliah', 'kelas_kuliah.id=peserta_kelas.kelas_kuliah_id', 'left')
            ->join('nilai_kelas', 'nilai_kelas.id_nilai_kelas = peserta_kelas.id', 'left')
            ->join('matakuliah', 'matakuliah.id = kelas_kuliah.matakuliah_id', 'left')
            ->where('id_semester', '20241')
            ->findAll();
        $perkuliahan = new \App\Models\PerkuliahanMahasiswaModel();
        $dataPerkuliahan = $perkuliahan->where('id_semester', '20241')->findAll();
        try {
            foreach ($dataPerkuliahan as $key => $value) {
                if ($value->id_status_mahasiswa == 'A') {
                    $value->ips = round($this->getKelas($dataPeserta, $value->id_riwayat_pendidikan), 2);
                    $dataTrans = $this->conn->query("SELECT 
                            SUM(sks_mata_kuliah * nilai_indeks) / SUM(sks_mata_kuliah) AS ipk
                        FROM 
                            transkrip
                        LEFT JOIN matakuliah on matakuliah.id = transkrip.matakuliah_id
                        WHERE id_riwayat_pendidikan = '" . $value->id_riwayat_pendidikan . "'")->getRow();
                    $value->ipk = round($dataTrans->ipk, 2);
                } else {
                    $lastPerkuliahan = $perkuliahan->where('id_riwayat_pendidikan', $value->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1, 1)->first();
                    $value->ips = "0";
                    $value->ipk = $lastPerkuliahan->ipk;
                }
                // $model = new \App\Entities\AktivitasKuliahEntity();
                // $model->fill((array)$value);
                $perkuliahan->update($value->id, ['ips' => $value->ips, 'ipk' => $value->ipk]);
            }
            return $this->respond($dataPerkuliahan);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
    function getKelas($array, $id)
    {
        $data = [];
        $object = new \App\Models\TranskripModel();
        try {
            $nxsks = 0;
            $sks = 0;
            foreach ($array as $key => $value) {
                if ($value->id_riwayat_pendidikan == $id and $value->id_semester == '20241') {
                    $this->conn->query("DELETE FROM transkrip WHERE id_riwayat_pendidikan = '" . $id . "' AND matakuliah_id='" . $value->matakuliah_id . "' AND kelas_kuliah_id='" . $value->kelas_kuliah_id . "'");
                    $item = [
                        'id' => Uuid::uuid4()->toString(),
                        'id_riwayat_pendidikan' => $value->id_riwayat_pendidikan,
                        'matakuliah_id' => $value->matakuliah_id,
                        'kelas_kuliah_id' => $value->kelas_kuliah_id,
                        'nilai_angka' => $value->nilai_angka,
                        'nilai_huruf' => $value->nilai_huruf,
                        'nilai_indeks' => $value->nilai_indeks,
                        'konversi_kampus_merdeka_id' => NULL,
                        'nilai_transfer_id' => NULL,
                    ];
                    if ($value->nilai_indeks > 1) {
                        $data[] = $item;
                    }
                    // $model = new \App\Entities\TranskripEntity();
                    // $model->fill($item);
                    $sks += (int)$value->sks_mata_kuliah;
                    $nxsks += ($value->sks_mata_kuliah * floatval($value->nilai_indeks));
                }
            }
            $object->insertBatch($data);
            return $nxsks / $sks;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function clearTemp()
    {
        $temp = new \App\Models\TempKrsmModel();
        $detail = new \App\Models\TempPesertaKelasModel();
        $perkuliahan = new \App\Models\PerkuliahanMahasiswaModel();
        $dataTempt = $temp->findAll();
        $conn = \Config\Database::connect();
        $semester = getSemesterAktif();
        foreach ($dataTempt as $key => $value) {
            try {
                $conn->transBegin();
                $temp->delete($value->id);
                $detail->where('temp_krsm_id', $value->id)->delete();
                $perkuliahan->where('id_riwayat_pendidikan', $value->id_pendidikan_mahasiswa)->where('id_semester', $semester->id_semester)->update(null, ['id_status_mahasiswa' => 'N']);
                $conn->transCommit();
            } catch (\Throwable $th) {
                $conn->transRollback();
            }
        }
    }

    public function transkrip($id_semester)
    {
        $db = db_connect();

        try {
            // Ambil semua data peserta kelas + nilai + mk
            $peserta = $db->table('peserta_kelas')
                ->select("peserta_kelas.id_riwayat_pendidikan, 
                      peserta_kelas.kelas_kuliah_id, 
                      nilai_kelas.nilai_angka, nilai_kelas.nilai_huruf, nilai_kelas.nilai_indeks, 
                      kelas_kuliah.matakuliah_id, 
                      matakuliah.sks_mata_kuliah")
                ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                ->join('nilai_kelas', 'nilai_kelas.id_nilai_kelas = peserta_kelas.id', 'left')
                ->join('matakuliah', 'matakuliah.id = kelas_kuliah.matakuliah_id', 'left')
                ->get()->getResult();

            // Ambil nilai transfer
            $transfer = $db->table('nilai_transfer')
                ->select("nilai_transfer.id_riwayat_pendidikan, 
                      matakuliah.id as matakuliah_id, 
                      nilai_transfer.nilai_angka_diakui as nilai_indeks, 
                      nilai_transfer.nilai_huruf_diakui as nilai_huruf, 
                      NULL as nilai_angka,
                      matakuliah.sks_mata_kuliah, 
                      nilai_transfer.id as nilai_transfer_id")
                ->join('matakuliah', 'matakuliah.id_matkul = nilai_transfer.id_matkul', 'left')
                ->get()->getResult();

            // Ambil konversi kampus merdeka (via anggota_aktivitas)
            $konversi = $db->table('konversi_kampus_merdeka')
                ->select("anggota_aktivitas.id_riwayat_pendidikan, 
                      konversi_kampus_merdeka.id as konversi_kampus_merdeka_id, 
                      konversi_kampus_merdeka.matakuliah_id, 
                      konversi_kampus_merdeka.nilai_angka, 
                      konversi_kampus_merdeka.nilai_huruf, 
                      konversi_kampus_merdeka.nilai_indeks,
                      matakuliah.sks_mata_kuliah")
                ->join('anggota_aktivitas', 'anggota_aktivitas.id = konversi_kampus_merdeka.anggota_aktivitas_id', 'left')
                ->join('matakuliah', 'matakuliah.id = konversi_kampus_merdeka.matakuliah_id', 'left')
                ->get()->getResult();

            // Gabungkan semua ke dalam 1 array transkrip kandidat
            $kandidat = [];

            foreach ($peserta as $p) {
                $kandidat[$p->id_riwayat_pendidikan][$p->matakuliah_id][] = [
                    'id_riwayat_pendidikan' => $p->id_riwayat_pendidikan,
                    'matakuliah_id' => $p->matakuliah_id,
                    'kelas_kuliah_id' => $p->kelas_kuliah_id,
                    'konversi_kampus_merdeka_id' => null,
                    'nilai_transfer_id' => null,
                    'nilai_angka' => $p->nilai_angka,
                    'nilai_huruf' => $p->nilai_huruf,
                    'nilai_indeks' => $p->nilai_indeks,
                    'sks_mata_kuliah' => $p->sks_mata_kuliah,
                ];
            }

            foreach ($transfer as $t) {
                $kandidat[$t->id_riwayat_pendidikan][$t->matakuliah_id][] = [
                    'id_riwayat_pendidikan' => $t->id_riwayat_pendidikan,
                    'matakuliah_id' => $t->matakuliah_id,
                    'kelas_kuliah_id' => null,
                    'konversi_kampus_merdeka_id' => null,
                    'nilai_transfer_id' => $t->nilai_transfer_id,
                    'nilai_angka' => $t->nilai_angka,
                    'nilai_huruf' => $t->nilai_huruf,
                    'nilai_indeks' => $t->nilai_indeks,
                    'sks_mata_kuliah' => $t->sks_mata_kuliah,
                ];
            }

            foreach ($konversi as $k) {
                $kandidat[$k->id_riwayat_pendidikan][$k->matakuliah_id][] = [
                    'id_riwayat_pendidikan' => $k->id_riwayat_pendidikan,
                    'matakuliah_id' => $k->matakuliah_id,
                    'kelas_kuliah_id' => null,
                    'konversi_kampus_merdeka_id' => $k->konversi_kampus_merdeka_id,
                    'nilai_transfer_id' => null,
                    'nilai_angka' => $k->nilai_angka,
                    'nilai_huruf' => $k->nilai_huruf,
                    'nilai_indeks' => $k->nilai_indeks,
                    'sks_mata_kuliah' => $k->sks_mata_kuliah,
                ];
            }

            // Pilih nilai tertinggi untuk setiap matakuliah
            $final = [];
            foreach ($kandidat as $idPendidikan => $matkulList) {
                foreach ($matkulList as $matkulId => $nilaiSet) {
                    // sort by nilai_indeks tertinggi
                    usort($nilaiSet, fn($a, $b) => $b['nilai_indeks'] <=> $a['nilai_indeks']);

                    // ambil yang tertinggi
                    $item = $nilaiSet[0];

                    // tambahkan id UUID
                    $item['id'] = Uuid::uuid4()->toString();

                    $final[] = $item;
                }
            }

            // Bersihkan transkrip lama & insert baru
            $transkripModel = new \App\Models\TranskripModel();
            $db->table('transkrip')->truncate();
            $transkripModel->insertBatch($final);

            return $this->respond([
                'status' => 'ok',
                'inserted' => count($final),
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }


    public function createMahasiswa()
    {
        $data = $this->request->getJSON();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            // if (!$this->validate('mahasiswa')) {
            //     $result = [
            //         "status" => false,
            //         "message" => $this->validator->getErrors(),
            //     ];
            //     return $this->failValidationErrors($result);
            // }
            $dataSimpan = [];
            $object = new MahasiswaModel();

            foreach ($data as $key => $item) {
                $item->id = Uuid::uuid4()->toString();
                $model = new EntitiesMahasiswa();
                $model->fill((array) $item);
                $dataSimpan[] = $model;
            }
            $object->insertBatch($dataSimpan);

            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $item
            ]);
        } catch (DatabaseException $th) {
            return $this->failValidationErrors([
                'status' => false,
                'message' => $th->getCode() == 1062 ? "Mahasiswa dengan nama, tempat, tanggal lahir dan ibu kandung yang sama sudah ada" : "Maaf, Terjadi kesalahan, silahkan hubungi bagian pengembang!",
            ]);
        }
    }

    public function createRegistrasi()
    {
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $data = $this->request->getJSON();

            // --- 1. Ambil semua NIK sekaligus (cache data mahasiswa) ---
            $nikList = array_map(fn($i) => $i->nik, $data);
            $mhsModel = new \App\Models\MahasiswaModel();
            $allMhs = $mhsModel->whereIn('nik', $nikList)->findAll();
            $mapMhs = [];
            foreach ($allMhs as $row) {
                $mapMhs[$row->nik] = $row;
            }

            // --- 2. Ambil semester aktif & biaya per prodi/angkatan ---
            $semester = getSemesterAktif();
            $biayaModel = new \App\Models\SettingBiayaModel();
            $prodiAngkatan = [];
            foreach ($data as $item) {
                $angkatan = substr($item->nim, 0, 4);
                $prodiAngkatan[] = ['id_prodi' => $item->id_prodi, 'angkatan' => $angkatan];
            }

            // unikkan kombinasi
            $prodiAngkatan = array_unique(array_map('serialize', $prodiAngkatan));
            $prodiAngkatan = array_map('unserialize', $prodiAngkatan);

            // query sekali biaya
            $biayaList = [];
            foreach ($prodiAngkatan as $pa) {
                $row = $biayaModel
                    ->where('id_prodi', $pa['id_prodi'])
                    ->where('angkatan', $pa['angkatan'])
                    ->first();
                if ($row) {
                    $biayaList[$pa['id_prodi']][$pa['angkatan']] = $row->biaya;
                }
            }

            // --- 3. Loop utama ---
            foreach ($data as $item) {
                // === User ===
                $itemUser = [
                    'username' => $item->nim,
                    'email'    => $item->nim . '@usn-papua.ac.id',
                    'password' => $item->nim,
                ];

                $userObject = auth()->getProvider();
                $userEntityObject = new User();
                $userEntityObject->fill($itemUser);
                $userObject->save($userEntityObject);
                $itemData = $userObject->findById($userObject->getInsertID());
                $item->id_user = $userObject->getInsertID();
                $userObject->addToDefaultGroup($itemData);
                $itemData->forcePasswordReset();
                $itemData->activate();

                // === Mahasiswa ===
                if (isset($mapMhs[$item->nik])) {
                    $item->id_mahasiswa = $mapMhs[$item->nik]->id;
                    $mhsModel->update($item->id_mahasiswa, ['id_user' => $item->id_user]);
                }

                // === Role ===
                (new UserRoleModel())->insert([
                    'users_id' => $item->id_user,
                    'role_id'  => '4'
                ]);

                // === Riwayat Pendidikan ===
                $item->id = Uuid::uuid4()->toString();
                $item->angkatan = substr($item->nim, 0, 4);
                $riwayatModel = new \App\Models\RiwayatPendidikanMahasiswaModel();
                $riwayatEntity = new \App\Entities\RiwayatPendidikanMahasiswa();
                $riwayatEntity->fill((array)$item);
                $riwayatModel->insert($riwayatEntity);

                // === Perkuliahan ===
                $biayaKuliah = $biayaList[$item->id_prodi][$item->angkatan] ?? 0;

                $perkuliahanModel = new \App\Models\PerkuliahanMahasiswaModel();
                $perkuliahanEntity = new \App\Entities\AktivitasKuliahEntity();
                $perkuliahanEntity->fill([
                    'id'                   => Uuid::uuid4()->toString(),
                    'id_riwayat_pendidikan' => $item->id,
                    'id_mahasiswa'         => $item->id_mahasiswa,
                    'id_semester'          => $semester->id_semester,
                    'nama_semester'        => $semester->nama_semester,
                    'nim'                  => $item->nim,
                    'id_prodi'             => $item->id_prodi,
                    'id_status_mahasiswa'  => "N",
                    'ips'                  => '0',
                    'ipk'                  => '0',
                    'sks_semester'         => '0',
                    'sks_total'            => '0',
                    'id_pembiayaan'        => '1',
                    'biaya_kuliah_smt'     => $biayaKuliah
                ]);
                $perkuliahanModel->insert($perkuliahanEntity);
            }

            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'message' => 'Registrasi berhasil'
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function prosesPerkuliahanMandiri(string $semester)
    {
        $db = db_connect();
        $perkuliahanModel = new \App\Models\PerkuliahanMahasiswaModel();

        // Ambil data perkuliahan semester aktif
        $perkuliahanData = $perkuliahanModel
            ->where('id_semester', $semester)
            ->findAll();

        // Ambil semua nilai semester ini (untuk IPS)
        $nilaiIPS = $db->table('nilai_kelas nk')
            ->select('pk.id_riwayat_pendidikan, mat.sks_mata_kuliah, nk.nilai_indeks')
            ->join('peserta_kelas pk', 'pk.id = nk.id_nilai_kelas', 'left')
            ->join('kelas_kuliah kk', 'kk.id = pk.kelas_kuliah_id', 'left')
            ->join('matakuliah mat', 'mat.id = kk.matakuliah_id', 'left')
            ->where('kk.id_semester', $semester) // ✅ filter hanya semester ini
            ->get()->getResult();

        // Kelompokkan nilai IPS per mahasiswa
        $nilaiPerMahasiswa = [];
        foreach ($nilaiIPS as $n) {
            $nilaiPerMahasiswa[$n->id_riwayat_pendidikan][] = $n;
        }

        // Ambil semua nilai historis (untuk IPK, tanpa filter semester)
        $nilaiIPK = $db->table('nilai_kelas nk')
            ->select('pk.id_riwayat_pendidikan, mat.sks_mata_kuliah, nk.nilai_indeks')
            ->join('peserta_kelas pk', 'pk.id = nk.id_nilai_kelas', 'left')
            ->join('kelas_kuliah kk', 'kk.id = pk.kelas_kuliah_id', 'left')
            ->join('matakuliah mat', 'mat.id = kk.matakuliah_id', 'left')
            ->whereIn('pk.id_riwayat_pendidikan', array_map(fn($p) => $p->id_riwayat_pendidikan, $perkuliahanData))
            ->get()->getResult();

        // Kelompokkan nilai IPK per mahasiswa
        $nilaiKumulatif = [];
        foreach ($nilaiIPK as $n) {
            $nilaiKumulatif[$n->id_riwayat_pendidikan][] = $n;
        }

        // Siapkan data untuk updateBatch
        $updateData = [];
        foreach ($perkuliahanData as $p) {
            if ($p->id_status_mahasiswa == 'A') {
                // Hitung IPS
                $sksIPS = $nxIPS = 0;
                if (isset($nilaiPerMahasiswa[$p->id_riwayat_pendidikan])) {
                    foreach ($nilaiPerMahasiswa[$p->id_riwayat_pendidikan] as $nilai) {
                        $sksIPS += (int)$nilai->sks_mata_kuliah;
                        $nxIPS  += ((float)$nilai->nilai_indeks * (int)$nilai->sks_mata_kuliah);
                    }
                }
                $ips = $sksIPS > 0 ? round($nxIPS / $sksIPS, 2) : 0;

                // Hitung IPK
                $sksIPK = $nxIPK = 0;
                if (isset($nilaiKumulatif[$p->id_riwayat_pendidikan])) {
                    foreach ($nilaiKumulatif[$p->id_riwayat_pendidikan] as $nilai) {
                        $sksIPK += (int)$nilai->sks_mata_kuliah;
                        $nxIPK  += ((float)$nilai->nilai_indeks * (int)$nilai->sks_mata_kuliah);
                    }
                }
                $ipk = $sksIPK > 0 ? round($nxIPK / $sksIPK, 2) : 0;
            } else {
                // Mahasiswa tidak aktif
                $lastPerkuliahan = $perkuliahanModel
                    ->where('id_riwayat_pendidikan', $p->id_riwayat_pendidikan)
                    ->orderBy('id_semester', 'desc')
                    ->limit(1)
                    ->first();
                $ips = 0;
                $ipk = $lastPerkuliahan ? $lastPerkuliahan->ipk : 0;
            }

            $updateData[] = [
                'id'  => $p->id,
                'ips' => $ips,
                'ipk' => $ipk,
            ];
        }

        // Update batch
        $chunks = array_chunk($updateData, 500);
        foreach ($chunks as $chunk) {
            $perkuliahanModel->updateBatch($chunk, 'id');
        }

        return [
            'status'  => 'ok',
            'updated' => count($updateData)
        ];
    }

    public function hitungAKM(string $semester): ResponseInterface
    {
        $object         = new PerkuliahanMahasiswaModel();
        $peserta_kelas  = new PesertaKelasModel();
        $nilai_transfer = new NilaiTransferModel();

        // 1. Ambil semua riwayat pendidikan di semester
        $dataPerkuliahan = $object->where('id_semester', $semester)->findAll();
        if (empty($dataPerkuliahan)) {
            return $this->respond(['status' => true, 'data' => []]);
        }

        // Ambil id_riwayat_pendidikan
        $riwayatIds = array_map(fn($row) => $row->id_riwayat_pendidikan, $dataPerkuliahan);

        // 2. Ambil semua peserta kelas sekaligus
        $allPeserta = $peserta_kelas->select("
            peserta_kelas.id,
            peserta_kelas.id_riwayat_pendidikan,
            kelas_kuliah.id_semester,
            matakuliah.sks_mata_kuliah,
            nilai_kelas.nilai_indeks
        ")
            ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
            ->join('matakuliah', 'kelas_kuliah.matakuliah_id = matakuliah.id', 'left')
            ->join('nilai_kelas', 'nilai_kelas.id_nilai_kelas = peserta_kelas.id', 'left')
            ->whereIn('peserta_kelas.id_riwayat_pendidikan', $riwayatIds)
            ->where('kelas_kuliah.id_semester <=', $semester)
            ->findAll();

        // 3. Kelompokkan peserta berdasarkan riwayat pendidikan
        $pesertaByRiwayat = [];
        foreach ($allPeserta as $p) {
            $pesertaByRiwayat[$p->id_riwayat_pendidikan][] = $p;
        }

        // 4. Ambil semua nilai transfer sekaligus
        $allTransfer = $nilai_transfer
            ->whereIn('id_riwayat_pendidikan', $riwayatIds)
            ->findAll();

        $transferByRiwayat = [];
        foreach ($allTransfer as $t) {
            $transferByRiwayat[$t->id_riwayat_pendidikan][] = $t;
        }

        // 4b. Ambil IPK semester sebelumnya untuk semua mahasiswa sekaligus
        $db = \Config\Database::connect();
        $prevData = $db->table('perkuliahan_mahasiswa pm1')
            ->select('pm1.id_riwayat_pendidikan, pm1.ipk, pm1.sks_total')
            ->join('(
            SELECT id_riwayat_pendidikan, MAX(id_semester) as max_semester
            FROM perkuliahan_mahasiswa
            WHERE id_semester < ' . $db->escape($semester) . '
            GROUP BY id_riwayat_pendidikan
        ) pm2', 'pm1.id_riwayat_pendidikan = pm2.id_riwayat_pendidikan AND pm1.id_semester = pm2.max_semester', 'inner')
            ->whereIn('pm1.id_riwayat_pendidikan', $riwayatIds)
            ->get()
            ->getResult();

        // Mapping prev IPK
        $prevByRiwayat = [];
        foreach ($prevData as $row) {
            $prevByRiwayat[$row->id_riwayat_pendidikan] = $row;
        }

        // 5. Proses per mahasiswa
        foreach ($dataPerkuliahan as &$value) {
            $items     = $pesertaByRiwayat[$value->id_riwayat_pendidikan] ?? [];
            $transfers = $transferByRiwayat[$value->id_riwayat_pendidikan] ?? [];

            [$sks_total, $sks_semester, $ips, $ipk] = $this->hitungIPSIPK($items, $transfers, $semester);

            // jika tidak ada mk di semester tsb → ambil dari prev
            if ($sks_semester == 0 && isset($prevByRiwayat[$value->id_riwayat_pendidikan])) {
                $ipk       = $prevByRiwayat[$value->id_riwayat_pendidikan]->ipk;
                $sks_total = $prevByRiwayat[$value->id_riwayat_pendidikan]->sks_total;
            }

            $value->sks_total    = $sks_total;
            $value->sks_semester = $sks_semester;
            $value->ips          = $ips;
            $value->ipk          = $ipk;
        }

        // 6. Update ke database sekaligus
        $object->updateBatch($dataPerkuliahan, 'id');

        return $this->respond([
            'status' => true,
            'data'   => $dataPerkuliahan
        ]);
    }


    /**
     * Hitung IPS & IPK satu mahasiswa
     *
     * @param array $items     Daftar peserta kelas
     * @param array $transfers Daftar nilai transfer
     * @param string $semester Semester aktif
     * @return array [sks_total, sks_semester, ips, ipk]
     */
    private function hitungIPSIPK(array $items, array $transfers, string $semester): array
    {
        $sks_total    = 0;
        $nx_total     = 0;
        $sks_ipk      = 0;
        $sks_semester = 0;
        $nx_semester  = 0;

        // Hitung dari peserta kelas
        foreach ($items as $peserta) {
            if ($peserta->id_semester == $semester) {
                $sks_semester += $peserta->sks_mata_kuliah;
                $nx_semester  += ($peserta->sks_mata_kuliah * $peserta->nilai_indeks);
            }
            if ($peserta->nilai_indeks >= 2) {
                $nx_total += ($peserta->nilai_indeks * $peserta->sks_mata_kuliah);
                $sks_ipk  += $peserta->sks_mata_kuliah;
            }
            $sks_total += $peserta->sks_mata_kuliah;
        }

        // Hitung dari nilai transfer
        foreach ($transfers as $t) {
            if ($t->nilai_angkat_diakui >= 2) {
                $nx_total += ($t->nilai_angkat_diakui * $t->sks_mata_kuliah_diakui);
                $sks_ipk  += $t->sks_mata_kuliah_diakui;
            }
            $sks_total += $t->sks_mata_kuliah_diakui;
        }

        // IPS & IPK
        $ips = $sks_semester > 0 ? round($nx_semester / $sks_semester, 2) : 0;
        $ipk = $sks_total > 0 ? round($nx_total / $sks_total, 2) : 0;

        return [$sks_total, $sks_semester, $ips, $ipk];
    }

    public function UpdateMahasiswa()
    {
        $data = $this->request->getJSON();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            // if (!$this->validate('mahasiswa')) {
            //     $result = [
            //         "status" => false,
            //         "message" => $this->validator->getErrors(),
            //     ];
            //     return $this->failValidationErrors($result);
            // }
            $dataSimpan = [];
            $object = new MahasiswaModel();

            $object->updateBatch($data, 'nik');

            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (DatabaseException $th) {
            return $this->failValidationErrors([
                'status' => false,
                'message' => $th->getCode() == 1062 ? "Mahasiswa dengan nama, tempat, tanggal lahir dan ibu kandung yang sama sudah ada" : "Maaf, Terjadi kesalahan, silahkan hubungi bagian pengembang!",
            ]);
        }
    }

    public function mhskip($id_semester)
    {
        $object = new PerkuliahanMahasiswaModel();
        $param = $this->request->getJSON();
        $nims = array_map(fn($p) => $p->nim, $param);
        $result = $object->select("rpm.angkatan, rpm.nim, mahasiswa.nama_mahasiswa, sm.nama_status_mahasiswa as status")
            ->join('riwayat_pendidikan_mahasiswa rpm', 'rpm.id = perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
            ->join('mahasiswa', 'mahasiswa.id = rpm.id_mahasiswa', 'left')
            ->join('status_mahasiswa sm', 'sm.id_status_mahasiswa = perkuliahan_mahasiswa.id_status_mahasiswa', 'left')
            ->whereIn('rpm.nim', $nims)
            ->where('perkuliahan_mahasiswa.id_semester', $id_semester)
            ->findAll();
       
        return $this->respond($result);
    }
}

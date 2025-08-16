<?php

namespace App\Controllers;

use Ramsey\Uuid\Uuid;

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

    public function transkrip()
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
            // foreach ($final as $row) {
            // }
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
}

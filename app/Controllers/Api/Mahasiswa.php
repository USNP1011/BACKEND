<?php

namespace App\Controllers\Api;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Libraries\Rest;
use App\Models\AnggotaAktivitasModel;
use App\Models\MahasiswaLulusDOModel;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahKurikulumModel;
use App\Models\NilaiTransferModel;
use App\Models\PerkuliahanMahasiswaModel;
use App\Models\PesertaKelasModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use App\Models\TranskripModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;
use CodeIgniter\Database\Exceptions\DatabaseException;
use stdClass;

class Mahasiswa extends ResourceController
{
    protected $api;
    protected $token;
    public function __construct()
    {
        helper('semester');
    }
    public function show($id = null)
    {
        $mahasiswa = new MahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $mahasiswa
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, prodi.nama_program_studi, prodi.jenjang, riwayat_pendidikan_mahasiswa.angkatan, (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar, (SELECT status_mahasiswa.nama_status_mahasiswa FROM perkuliahan_mahasiswa LEFT JOIN status_mahasiswa ON status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa order by perkuliahan_mahasiswa.created_at desc limit 1) as nama_status_mahasiswa")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->groupBy('mahasiswa.id')
                ->findAll() : $mahasiswa
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, prodi.nama_program_studi, prodi.jenjang, riwayat_pendidikan_mahasiswa.angkatan, wilayah.nama_wilayah, agama.nama_agama, prodi.nama_program_studi, jenis_transportasi.nama_alat_transportasi")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->join('wilayah', 'wilayah.id_wilayah=mahasiswa.id_wilayah', 'left')
                ->join("agama", "agama.id_agama=mahasiswa.id_agama", "LEFT")
                ->join("prodi", "riwayat_pendidikan_mahasiswa.id_prodi=prodi.id_prodi", "LEFT")
                ->join("jenis_transportasi", "jenis_transportasi.id_alat_transportasi=mahasiswa.id_alat_transportasi", "LEFT")
                ->where('mahasiswa.id', $id)->first()
        ]);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function riwayatPendidikan($id = null)
    {
        $object = new RiwayatPendidikanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select('riwayat_pendidikan_mahasiswa.*, kelas.nama_kelas_kuliah')->where('id_mahasiswa', $id)->join('kelas', 'kelas.id=riwayat_pendidikan_mahasiswa.kelas_id', 'left')->findAll()
        ]);
    }

    public function nilaiTransfer($id = null)
    {
        $object = new NilaiTransferModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("nilai_transfer.*")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = nilai_transfer.id_riwayat_pendidikan', 'left')
                ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                ->where('mahasiswa.id', $id)->findAll()
        ]);
    }

    public function krsm($id = null, $id_semester = null)
    {
        $perkuliahan = new PerkuliahanMahasiswaModel();
        $profile = getProfileByMahasiswa($id);
        $dataPerkuliahan = $perkuliahan->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->where('id_semester', $id_semester)->first();
        $semester = getSemesterById($id_semester) ?? getSemesterAktif();
        $data = [
            'id_riwayat_pendidikan' => $profile->id_riwayat_pendidikan,
            'id_semester' => $semester->id_semester,
            'nama_semester' => $semester->nama_semester,
            'nama_mahasiswa' => $profile->nama_mahasiswa,
            'nim' => $profile->nim,
            'nama_program_studi' => $profile->nama_program_studi,
            'nama_kaprodi' => getKaprodi($profile->id_prodi)->nama_dosen,
            'sks_semester' => $dataPerkuliahan->sks_semester,
            'dosen_wali' => $profile->dosen_wali,
        ];
        $object = new PesertaKelasModel();
        $data['detail'] = $object->select("kelas_kuliah.id, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai, kelas.nama_kelas_kuliah, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, prodi.nama_program_studi, matakuliah_kurikulum.semester,
        (if(dosen_pengajar_kelas.id_registrasi_dosen IS NOT NULL , (SELECT penugasan_dosen.nidn FROM penugasan_dosen WHERE penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen LIMIT 1), (SELECT dosen.nidn FROM dosen WHERE dosen.id_dosen = dosen_pengajar_kelas.id_dosen))) as nidn, 
        (if(dosen_pengajar_kelas.id_registrasi_dosen IS NOT NULL , (SELECT penugasan_dosen.nama_dosen FROM penugasan_dosen WHERE penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen LIMIT 1), (SELECT dosen.nama_dosen FROM dosen WHERE dosen.id_dosen = dosen_pengajar_kelas.id_dosen))) as nama_dosen,")
            ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
            ->join('kelas', 'kelas.id = kelas_kuliah.kelas_id', 'left')
            ->join('matakuliah', 'matakuliah.id = kelas_kuliah.matakuliah_id', 'left')
            ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id = kelas_kuliah.id', 'left')
            ->join('prodi', 'prodi.id_prodi = kelas_kuliah.id_prodi', 'left')
            ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id = kelas_kuliah.matakuliah_id', 'left')
            ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
            ->where('riwayat_pendidikan_mahasiswa.id_mahasiswa', $id)
            ->where('kelas_kuliah.id_semester', $semester->id_semester)
            ->where('dosen_pengajar_kelas.mengajar', '1')
            ->findAll();
        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    public function khsm($id = null, $id_semester = null)
    {
        $semester = $id_semester ?? getSemesterAktif()->id_semester;
        $object = new PesertaKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("kelas_kuliah.*, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, prodi.nama_program_studi, nilai_kelas.nilai_angka, nilai_kelas.nilai_huruf, nilai_kelas.nilai_indeks, (matakuliah.sks_mata_kuliah*nilai_kelas.nilai_indeks) as nxsks, if(nilai_kelas.nilai_indeks>=2,'L', 'TL') as ket")
                ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                ->join('nilai_kelas', 'nilai_kelas.id_nilai_kelas=peserta_kelas.id', 'left')
                ->join('matakuliah', 'matakuliah.id = kelas_kuliah.matakuliah_id', 'left')
                ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id=kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi = kelas_kuliah.id_prodi', 'left')
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
                ->where('riwayat_pendidikan_mahasiswa.id_mahasiswa', $id)
                ->where('kelas_kuliah.id_semester', $semester)
                ->findAll()
        ]);
    }

    public function aktivitasKuliah($id = null)
    {
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object
                ->where('perkuliahan_mahasiswa.id_mahasiswa', $id)
                ->orderBy('id_semester', 'asc')
                ->findAll()
        ]);
    }

    public function byUserId($id = null)
    {
        $mahasiswa = new MahasiswaModel();

        return $this->respond([
            'status' => true,
            'data' => $mahasiswa
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan, wilayah.nama_wilayah, agama.nama_agama, prodi.nama_program_studi, jenis_transportasi.nama_alat_transportasi")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->join('wilayah', 'wilayah.id_wilayah=mahasiswa.id_wilayah', 'left')
                ->join("agama", "agama.id_agama=mahasiswa.id_agama", "LEFT")
                ->join("prodi", "riwayat_pendidikan_mahasiswa.id_prodi=prodi.id_prodi", "LEFT")
                ->join("jenis_transportasi", "jenis_transportasi.id_alat_transportasi=mahasiswa.id_alat_transportasi", "LEFT")
                ->where('id_user', $id)->first()
        ]);
    }

    public function transkrip($id = null)
    {
        $aktivitas = new AnggotaAktivitasModel();
        $lulus = new MahasiswaLulusDOModel();
        $mahasiswa = new RiwayatPendidikanMahasiswaModel();
        $itemMahasiswa = $mahasiswa
            ->select("riwayat_pendidikan_mahasiswa.*, mahasiswa.tempat_lahir, mahasiswa.tanggal_lahir, mahasiswa.nama_mahasiswa, prodi.nama_program_studi, prodi.jenjang")
            ->join("mahasiswa", "mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa", "LEFT")
            ->join("prodi", "prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi", "left")
            ->where('riwayat_pendidikan_mahasiswa.id_mahasiswa', $id)->first();
        $itemLulus = $lulus->where('id_riwayat_pendidikan', $itemMahasiswa->id)->first();
        $item = $aktivitas->select("aktivitas_mahasiswa.judul")->join("aktivitas_mahasiswa", "aktivitas_mahasiswa.id=anggota_aktivitas.aktivitas_mahasiswa_id", "LEFT")
            ->where("id_riwayat_pendidikan", $itemMahasiswa->id)
            ->where('id_jenis_aktivitas_mahasiswa', '2')
            ->first();
        if (is_null($item)) {
            $item = new stdClass();
        }
        $item->nomor_ijazah = !is_null($itemLulus) ? $itemLulus->nomor_ijazah : null;
        $item->nama_program_studi = $itemMahasiswa->nama_program_studi;
        $item->jenjang = $itemMahasiswa->jenjang;
        $item->nama_mahasiswa = $itemMahasiswa->nama_mahasiswa;
        $item->tempat_lahir = $itemMahasiswa->tempat_lahir;
        $item->tanggal_lahir = $itemMahasiswa->tanggal_lahir;
        $item->nim = $itemMahasiswa->nim;
        $item->tanggal_keluar = !is_null($itemLulus) ? $itemLulus->tanggal_keluar : date('Y-m-d');
        $item->warek = "JIM LAHALLO, ST., M.M.S.I";
        $item->nidn = "1418058001";

        $kurikulum = new MatakuliahKurikulumModel();
        $itemMatakuliah = $kurikulum
            ->select("matakuliah_kurikulum.matakuliah_id, matakuliah_kurikulum.kode_mata_kuliah, matakuliah_kurikulum.nama_mata_kuliah, matakuliah_kurikulum.sks_mata_kuliah")
            ->join('kurikulum', 'kurikulum.id=matakuliah_kurikulum.kurikulum_id', 'left')
            ->orderBy('matakuliah_kurikulum.semester', 'asc')
            ->where('kurikulum.id_prodi', $itemMahasiswa->id_prodi)->findAll();
        $object = new TranskripModel();
        $nilai = $object->select('matakuliah.id, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, transkrip.nilai_angka, transkrip.nilai_huruf, transkrip.nilai_indeks, (matakuliah.sks_mata_kuliah*transkrip.nilai_indeks) as nxsks')->join('matakuliah', 'matakuliah.id=transkrip.matakuliah_id', 'left')->where('id_riwayat_pendidikan', $itemMahasiswa->id)->findAll();
        foreach ($itemMatakuliah as $key => $matakuliah) {
            $matakuliah->nilai_angka = null;
            $matakuliah->nilai_huruf = null;
            $matakuliah->nilai_indeks = null;
            foreach ($nilai as $key => $value) {
                if ($matakuliah->matakuliah_id == $value->id) {
                    $matakuliah->nilai_angka = $value->nilai_angka;
                    $matakuliah->nilai_huruf = $value->nilai_huruf;
                    $matakuliah->nilai_indeks = $value->nilai_indeks;
                }
            }
        }
        $item->detail = $itemMatakuliah;
        return $this->respond([
            'status' => true,
            'data' => $item
        ]);
    }

    public function updateTranskrip($id = null)
    {
        $this->api = new Rest();
        $this->token = $this->api->getToken()->data->token;

        $mahasiswa = new RiwayatPendidikanMahasiswaModel();
        $itemMahasiswa = $mahasiswa->where('id_mahasiswa', $id)->first();

        $object = new \App\Models\TranskripModel();
        $matakuliah = new \App\Models\MatakuliahModel();
        $transfer = new \App\Models\NilaiTransferModel();
        $kelas = new \App\Models\KelasKuliahModel();
        $konversi = new \App\Models\KonversiKampusMerdekaModel();
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $conn = \Config\Database::connect();
        try {
            $data = $this->api->getData('GetTranskripMahasiswa', $this->token, "id_registrasi_mahasiswa='" . $itemMahasiswa->id_registrasi_mahasiswa . "'");
            $conn->transException(true)->transStart();
            $dataUpdate = [];
            $object->where("id_riwayat_pendidikan", $itemMahasiswa->id)->delete();
            foreach ($data->data as $key => $value) {
                $itemMatakuliah = $matakuliah->where('id_matkul', $value->id_matkul)->first();
                $itemRiwayat = $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first();
                $itemUpdate = [
                    'id' => Uuid::uuid4()->toString(),
                    'id_riwayat_pendidikan' => $itemRiwayat->id,
                    'matakuliah_id' => $itemMatakuliah->id,
                    'nilai_angka' => $value->nilai_angka,
                    'nilai_indeks' => $value->nilai_indeks,
                    'nilai_huruf' => $value->nilai_huruf,
                    'status_sync' => 'sudah sync'

                ];
                if (!is_null($value->id_kelas_kuliah)) {
                    $item = $kelas->where('id_kelas_kuliah', $value->id_kelas_kuliah)->first();
                    if (!is_null($item)) {
                        $itemUpdate['kelas_kuliah_id'] = $item->id;
                        $itemUpdate['konversi_kampus_merdeka_id'] = null;
                        $itemUpdate['nilai_transfer_id'] = null;
                    }
                } else if (!is_null($value->id_konversi_aktivitas)) {
                    $item = $konversi->where('id_konversi_aktivitas', $value->id_konversi_aktivitas)->first();
                    if (!is_null($item)) {
                        $itemUpdate['konversi_kampus_merdeka_id'] = $item->id;
                        $itemUpdate['kelas_kuliah_id'] = null;
                        $itemUpdate['nilai_transfer_id'] = null;
                    }
                } else if (!is_null($value->id_nilai_transfer)) {
                    $item = $transfer->where('id_transfer', $value->id_nilai_transfer)->first();
                    if (!is_null($item)) {
                        $itemUpdate['nilai_transfer_id'] = $item->id;
                        $itemUpdate['konversi_kampus_merdeka_id'] = null;
                        $itemUpdate['kelas_kuliah_id'] = null;
                    }
                }
                $model = new \App\Entities\TranskripEntity();
                $model->fill($itemUpdate);
                $object->insert($model);
            }
            $conn->transComplete();



            $aktivitas = new AnggotaAktivitasModel();
            $lulus = new MahasiswaLulusDOModel();
            $mahasiswa = new RiwayatPendidikanMahasiswaModel();
            $itemMahasiswa = $mahasiswa
                ->select("riwayat_pendidikan_mahasiswa.*, mahasiswa.tempat_lahir, mahasiswa.tanggal_lahir, mahasiswa.nama_mahasiswa, prodi.nama_program_studi, prodi.jenjang")
                ->join("mahasiswa", "mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa", "LEFT")
                ->join("prodi", "prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi", "left")
                ->where('riwayat_pendidikan_mahasiswa.id_mahasiswa', $id)->first();
            $itemLulus = $lulus->where('id_riwayat_pendidikan', $itemMahasiswa->id)->first();
            $item = $aktivitas->select("aktivitas_mahasiswa.judul")->join("aktivitas_mahasiswa", "aktivitas_mahasiswa.id=anggota_aktivitas.aktivitas_mahasiswa_id", "LEFT")
                ->where("id_riwayat_pendidikan", $itemMahasiswa->id)
                ->where('id_jenis_aktivitas_mahasiswa', '2')
                ->first();
            if (is_null($item)) {
                $item = new stdClass();
            }
            $item->nomor_ijazah = !is_null($itemLulus) ? $itemLulus->nomor_ijazah : null;
            $item->nama_program_studi = $itemMahasiswa->nama_program_studi;
            $item->jenjang = $itemMahasiswa->jenjang;
            $item->nama_mahasiswa = $itemMahasiswa->nama_mahasiswa;
            $item->tempat_lahir = $itemMahasiswa->tempat_lahir;
            $item->tanggal_lahir = $itemMahasiswa->tanggal_lahir;
            $item->nim = $itemMahasiswa->nim;
            $item->tanggal_keluar = !is_null($itemLulus) ? $itemLulus->tanggal_keluar : date('Y-m-d');
            $item->warek = "JIM LAHALLO, ST., M.M.S.I";
            $item->nidn = "1418058001";

            $kurikulum = new MatakuliahKurikulumModel();
            $itemMatakuliah = $kurikulum
                ->select("matakuliah_kurikulum.matakuliah_id, matakuliah_kurikulum.kode_mata_kuliah, matakuliah_kurikulum.nama_mata_kuliah, matakuliah_kurikulum.sks_mata_kuliah")
                ->orderBy('matakuliah_kurikulum.semester', 'asc')
                ->where('id_prodi', $itemMahasiswa->id_prodi)->findAll();
            $object = new TranskripModel();
            $nilai = $object->select('matakuliah.id, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, transkrip.nilai_angka, transkrip.nilai_huruf, transkrip.nilai_indeks, (matakuliah.sks_mata_kuliah*transkrip.nilai_indeks) as nxsks')->join('matakuliah', 'matakuliah.id=transkrip.matakuliah_id', 'left')->where('id_riwayat_pendidikan', $itemMahasiswa->id)->findAll();
            foreach ($itemMatakuliah as $key => $matakuliah) {
                $matakuliah->nilai_angka = null;
                $matakuliah->nilai_huruf = null;
                $matakuliah->nilai_indeks = null;
                foreach ($nilai as $key => $value) {
                    if ($matakuliah->matakuliah_id == $value->id) {
                        $matakuliah->nilai_angka = $value->nilai_angka;
                        $matakuliah->nilai_huruf = $value->nilai_huruf;
                        $matakuliah->nilai_indeks = $value->nilai_indeks;
                    }
                }
            }
            $item->detail = $itemMatakuliah;
            return $this->respond([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $item = $this->request->getJSON();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            if (!$this->validate('mahasiswa')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }

            $item->id = Uuid::uuid4()->toString();
            $object = new MahasiswaModel();
            $model = new EntitiesMahasiswa();
            $model->fill((array) $item);
            $object->insert($model);
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

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        try {
            $item = $this->request->getJSON();
            $object = new MahasiswaModel();
            $model = new EntitiesMahasiswa();
            $model->fill((array) $item);
            $object->save($item);
            return $this->respondUpdated([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        try {
            $object = new MahasiswaModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
                'data' => []
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function paginate($page = 1, $count = 10, $cari = null)
    {
        $param = $this->request->getJSON();
        $object = model(MahasiswaModel::class);
        $item = [
            'status' => true,
            'data' => $object->select("mahasiswa.id, 
    mahasiswa.nama_mahasiswa, 
    mahasiswa.id_user, 
    mahasiswa.jenis_kelamin, 
    mahasiswa.status_sync, 
    mahasiswa.sync_at, 
    mahasiswa.tanggal_lahir, 
    agama.nama_agama, 
    agama.id_agama, 
    riwayat_pendidikan_mahasiswa.nim, 
    riwayat_pendidikan_mahasiswa.angkatan, 
    prodi.nama_program_studi, 
    prodi.id_prodi, 

    (SELECT perkuliahan_mahasiswa.sks_total 
        FROM perkuliahan_mahasiswa 
        WHERE id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id 
        AND sks_total != '0' 
        ORDER BY id_semester DESC 
        LIMIT 1
    ) as sks_total,

    (SELECT jenis_keluar.jenis_keluar 
        FROM mahasiswa_lulus_do 
        LEFT JOIN jenis_keluar 
            ON jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar 
        WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  
        LIMIT 1
    ) as nama_jenis_keluar,

    CASE 
        WHEN (
            (SELECT jenis_keluar.jenis_keluar 
             FROM mahasiswa_lulus_do 
             LEFT JOIN jenis_keluar 
                ON jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar 
             WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  
             LIMIT 1
            ) IS NOT NULL
        )
        THEN (
            (SELECT jenis_keluar.jenis_keluar 
             FROM mahasiswa_lulus_do 
             LEFT JOIN jenis_keluar 
                ON jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar 
             WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  
             LIMIT 1
            )
        )
        ELSE (
            (SELECT status_mahasiswa.nama_status_mahasiswa 
             FROM perkuliahan_mahasiswa 
             LEFT JOIN status_mahasiswa 
                ON status_mahasiswa.id_status_mahasiswa = perkuliahan_mahasiswa.id_status_mahasiswa 
             WHERE perkuliahan_mahasiswa.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id 
             ORDER BY perkuliahan_mahasiswa.created_at DESC 
             LIMIT 1
            )
        )
    END as nama_status_mahasiswa")
                ->join("agama", "agama.id_agama=mahasiswa.id_agama", "LEFT")
                ->join("riwayat_pendidikan_mahasiswa", "riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id", "LEFT")
                ->join("prodi", "riwayat_pendidikan_mahasiswa.id_prodi=prodi.id_prodi", "LEFT")
                ->groupStart()
                ->like('mahasiswa.nama_mahasiswa', $param->cari)
                ->orLike('riwayat_pendidikan_mahasiswa.nim', $param->cari)
                ->orLike('prodi.nama_program_studi', $param->cari)
                ->groupEnd()
                ->orderBy(isset($param->order) && $param->order->field != "" ? $param->order->field : 'mahasiswa.created_at', isset($param->order) && $param->order->field != "" ? $param->order->direction : 'desc')

                ->paginate($param->count, 'default', $param->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

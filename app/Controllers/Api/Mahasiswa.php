<?php

namespace App\Controllers\Api;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
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

class Mahasiswa extends ResourceController
{
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
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan, (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar, (SELECT status_mahasiswa.nama_status_mahasiswa FROM perkuliahan_mahasiswa LEFT JOIN status_mahasiswa ON status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa order by perkuliahan_mahasiswa.created_at desc limit 1) as nama_status_mahasiswa")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->groupBy('mahasiswa.id')
                ->findAll() : $mahasiswa
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan, wilayah.nama_wilayah, agama.nama_agama, prodi.nama_program_studi, jenis_transportasi.nama_alat_transportasi")
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

    public function krsm($id = null)
    {
        $semester = getSemesterAktif();
        $object = new PesertaKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("kelas_kuliah.*, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, prodi.nama_program_studi")
                ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                ->join('matakuliah', 'matakuliah.id = kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi = kelas_kuliah.id_prodi', 'left')
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
                ->where('riwayat_pendidikan_mahasiswa.id_mahasiswa', $id)
                ->where('kelas_kuliah.id_semester', $semester->id_semester)
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
        $mahasiswa = new RiwayatPendidikanMahasiswaModel();
        $itemMahasiswa = $mahasiswa->where('id_mahasiswa', $id)->first();
        $kurikulum = new MatakuliahKurikulumModel();
        $itemMatakuliah = $kurikulum
            ->select("matakuliah_kurikulum.matakuliah_id, matakuliah_kurikulum.kode_mata_kuliah, matakuliah_kurikulum.nama_mata_kuliah, matakuliah_kurikulum.sks_mata_kuliah")->orderBy('matakuliah_kurikulum.kode_mata_kuliah', 'asc')
            ->where('id_prodi', $itemMahasiswa->id_prodi)->findAll();
        // if (is_null($id)) $profile = getProfile();
        // else $profile = getProfileByMahasiswa($id);
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
        return $this->respond([
            'status' => true,
            'data' => $itemMatakuliah
        ]);
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
            'data' => $object->select("mahasiswa.id, mahasiswa.nama_mahasiswa, mahasiswa.id_user, mahasiswa.jenis_kelamin, mahasiswa.status_sync, mahasiswa.sync_at, mahasiswa.tanggal_lahir, agama.nama_agama, agama.id_agama, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.angkatan, prodi.nama_program_studi, prodi.id_prodi, (SELECT perkuliahan_mahasiswa.sks_total from perkuliahan_mahasiswa where id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id AND sks_total != '0' order by id_semester desc limit 1) as sks_total, (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar, (SELECT status_mahasiswa.nama_status_mahasiswa FROM perkuliahan_mahasiswa LEFT JOIN status_mahasiswa ON status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa WHERE perkuliahan_mahasiswa.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id order by perkuliahan_mahasiswa.created_at desc limit 1) as nama_status_mahasiswa")
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

<?php

namespace App\Controllers\Api;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Models\MahasiswaModel;
use App\Models\NilaiTransferModel;
use App\Models\PerkuliahanMahasiswaModel;
use App\Models\PesertaKelasModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use App\Models\UserRoleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Shield\Entities\User;

class Mahasiswa extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }
    public function show($id = null, $req = null)
    {
        if (is_null($req)) {
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
        } else {
            if ($req == 'riwayat_pendidikan') {
                $object = new RiwayatPendidikanMahasiswaModel();
                return $this->respond([
                    'status' => true,
                    'data' => $object->where('id_mahasiswa', $id)->findAll()
                ]);
            } else if ($req == 'nilai_transfer') {
                $object = new NilaiTransferModel();
                return $this->respond([
                    'status' => true,
                    'data' => $object->select("nilai_transfer.*")
                        ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = nilai_transfer.id_riwayat_pendidikan', 'left')
                        ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                        ->where('mahasiswa.id', $id)->findAll()
                ]);
            } else if ($req == 'krsm') {
                $semester = getSemesterAktif();
                $object = new PesertaKelasModel();
                return $this->respond([
                    'status' => true,
                    'data' => $object->select("kelas_kuliah.*")
                        ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                        ->where('peserta_kelas.mahasiswa_id', $id)
                        ->where('kelas_kuliah.id_semester', $semester->id_semester)
                        ->findAll()
                ]);
            } else if ($req == 'aktivitas_kuliah') {
                $object = new PerkuliahanMahasiswaModel();
                return $this->respond([
                    'status' => true,
                    'data' => $object
                        ->where('perkuliahan_mahasiswa.id_mahasiswa', $id)
                        ->orderBy('id_semester', 'asc')
                        ->findAll()
                ]);
            } else {
                return $this->failNotFound("Parameter yang anda masukkan tidak sesuai");
            }
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
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
            $object->insert($item);
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $item
            ]);
        } catch (DatabaseException $th) {
            return $this->failValidationErrors([
                'status' => false,
                'message' => $th->getCode() == 1062 ? "Mahasiswa dengan nama, tempat, tanggal lahir dan ibu kandung yang sama sudah ada": "Maaf, Terjadi kesalahan, silahkan hubungi bagian pengembang!",
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
        $item = $this->request->getJSON();
        $object = model(MahasiswaModel::class);
        $item = [
            'status' => true,
            'data' => $object->select("mahasiswa.id, mahasiswa.nama_mahasiswa, mahasiswa.jenis_kelamin, mahasiswa.status_sync, mahasiswa.sync_at, mahasiswa.tanggal_lahir, agama.nama_agama, agama.id_agama, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.angkatan, prodi.nama_program_studi, prodi.id_prodi, (SELECT perkuliahan_mahasiswa.sks_total from perkuliahan_mahasiswa where id_mahasiswa = mahasiswa.id AND sks_total != '0' order by id_semester desc limit 1) as sks_total, , (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar, (SELECT status_mahasiswa.nama_status_mahasiswa FROM perkuliahan_mahasiswa LEFT JOIN status_mahasiswa ON status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa order by perkuliahan_mahasiswa.created_at desc limit 1) as nama_status_mahasiswa")
                ->join("agama", "agama.id_agama=mahasiswa.id_agama", "LEFT")
                ->join("riwayat_pendidikan_mahasiswa", "riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id", "LEFT")
                ->join("prodi", "riwayat_pendidikan_mahasiswa.id_prodi=prodi.id_prodi", "LEFT")
                ->like('mahasiswa.nama_mahasiswa', $item->cari)
                ->orLike('riwayat_pendidikan_mahasiswa.nim', $item->cari)
                ->orLike('prodi.nama_program_studi', $item->cari)
                ->paginate($item->count, 'default', $item->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

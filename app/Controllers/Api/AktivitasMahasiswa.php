<?php

namespace App\Controllers\Api;

use App\Models\AnggotaAktivitasModel;
use App\Models\BimbingMahasiswaModel;
use App\Models\UjiMahasiswaModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class AktivitasMahasiswa extends ResourceController
{
    protected $aktivitasMahasiswa;
    protected $semester;
    protected $conn;
    public function __construct()
    {
        helper('semester');
        $this->aktivitasMahasiswa = new \App\Models\AktivitasMahasiswaModel();
        $this->semester = getSemesterAktif();
        $this->conn = \Config\Database::connect();
    }

    public function show($id = null)
    {
        return $this->respond([
            'status' => true,
            'data' => $id == null ?
                $this->aktivitasMahasiswa
                ->select("aktivitas_mahasiswa.*, jenis_aktivitas.nama_jenis_aktivitas_mahasiswa, prodi.nama_program_studi, semester.nama_semester")
                ->join('jenis_aktivitas', 'jenis_aktivitas.id_jenis_aktivitas_mahasiswa= aktivitas_mahasiswa.id_jenis_aktivitas_mahasiswa', 'left')
                ->join('prodi', 'prodi.id_prodi= aktivitas_mahasiswa.id_prodi', 'left')
                ->join('semester', 'semester.id_semester= aktivitas_mahasiswa.id_semester', 'left')
                ->where('aktivitas_mahasiswa.id_semester', $this->semester->id_semester)->findAll() :
                $this->aktivitasMahasiswa
                ->select("aktivitas_mahasiswa.*, jenis_aktivitas.nama_jenis_aktivitas_mahasiswa, prodi.nama_program_studi, semester.nama_semester")
                ->join('jenis_aktivitas', 'jenis_aktivitas.id_jenis_aktivitas_mahasiswa= aktivitas_mahasiswa.id_jenis_aktivitas_mahasiswa', 'left')
                ->join('prodi', 'prodi.id_prodi= aktivitas_mahasiswa.id_prodi', 'left')
                ->join('semester', 'semester.id_semester= aktivitas_mahasiswa.id_semester', 'left')
                ->where('id', $id)->first()
        ]);
    }

    public function dosenPembimbing($id = null)
    {
        $object = new BimbingMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('aktivitas_mahasiswa_id', $id)->findAll()
        ]);
    }

    public function dosenPenguji($id = null)
    {
        $object = new UjiMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('aktivitas_mahasiswa_id', $id)->findAll()
        ]);
    }

    public function AnggotaAktivitasMahasiswa($id = null)
    {
        $object = new AnggotaAktivitasModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('aktivitas_mahasiswa_id', $id)->findAll()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('aktivitasMahasiswa')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\AktivitasMahasiswaModel();
            $model = new \App\Entities\AktivitasKuliahEntity();
            $model->fill((array)$item);
            $object->insert($model);
            return $this->respond([
                'status' => true,
                'data' => $object->getById($item->id)
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function createAnggota()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('anggotaAktivitasMahasiswa')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\AnggotaAktivitasModel();
            $model = new \App\Entities\AnggotaAktivitasMahasiswaEntity();
            $model->fill((array)$item);
            $object->insert($model);
            return $this->respond([
                'status' => true,
                'data' => $model
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function createPembimbing()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('bimbingMahasiswa')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\BimbingMahasiswaModel();
            $model = new \App\Entities\BimbingMahasiswaEntity();
            $model->fill((array)$item);
            $object->insert($model);
            return $this->respond([
                'status' => true,
                'data' => $object->getById($item->id)
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function createPenguji()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('ujiMahasiswa')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\UjiMahasiswaModel();
            $model = new \App\Entities\UjiMahasiswaEntity();
            $model->fill((array)$item);
            $object->insert($model);
            return $this->respond([
                'status' => true,
                'data' => $object->getById($item->id)
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function update($id = null)
    {
        try {
            $item = $this->request->getJSON();
            $itemData = [
                "id"=>$item->id,
                "jenis_anggota"=>$item->jenis_anggota,
                "id_jenis_aktivitas_mahasiswa"=>$item->id_jenis_aktivitas_mahasiswa,
                "id_prodi"=>$item->id_prodi,
                "judul"=>$item->judul,
                "keterangan"=>$item->keterangan,
                "lokasi"=>$item->lokasi,
                "sk_tugas"=>$item->sk_tugas,
                "tanggal_sk_tugas"=>$item->tanggal_sk_tugas,
            ];
            $object = new \App\Models\AktivitasMahasiswaModel();
            $model = new \App\Entities\AktivitasKuliahEntity();
            $model->fill($itemData);
            $object->save($model);
            return $this->respond([
                'status' => true,
                'data' => $model
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function delete($id = null)
    {
        try {
            $object = new \App\Models\AktivitasMahasiswaModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deleteAnggota($id = null)
    {
        try {
            $object = new \App\Models\AnggotaAktivitasModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deletePembimbing($id = null)
    {
        try {
            $object = new \App\Models\BimbingMahasiswaModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function deletePenguji($id = null)
    {
        try {
            $object = new \App\Models\UjiMahasiswaModel();
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
}

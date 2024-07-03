<?php

namespace App\Controllers\Api;

use App\Models\DosenPengajarKelasModel;
use App\Models\KelasKuliahModel;
use App\Models\PesertaKelasModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class KelasKuliah extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }
    /**
     * @param null $id
     * 
     * @return object
     */
    public function show($id = null, $req = null): object
    {
        if (is_null($req)) {
            $semester = getSemesterAktif();
            $object = new KelasKuliahModel();
            return $this->respond([
                'status' => true,
                'data' => $id == null ? $object
                    ->select("kelas_kuliah.*")
                    ->join('semester', 'semester.id_semester=kelas_kuliah.id_semester', 'left')
                    ->where('a_periode_aktif', '1')
                    ->findAll() : $object->where('id', $id)->first()
            ]);
        } else {
            if ($req == "peserta_kelas") {
                $object = new PesertaKelasModel();
                return $this->respond([
                    'status' => true,
                    'data' => $object->where('kelas_kuliah_id', $id)->findAll()
                ]);
            } else if ($req == "dosen_pengajar_kelas") {
                $object = new DosenPengajarKelasModel();
                return $this->respond([
                    'status' => true,
                    'data' => $object->where('kelas_kuliah_id', $id)->findAll()
                ]);
            } else {
                return $this->failNotFound("URL tidak ditemukan");
            }
        }
    }

    public function peserta_kelas($id = null): object
    {
        $object = new PesertaKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('kelas_kuliah_id', $id)->findAll()
        ]);
    }


    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('kelasKuliah')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\KelasKuliahModel();
            $model = new \App\Entities\KelasKuliahEntity();
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

    public function update($id = null)
    {
        try {
            $object = new \App\Models\KelasKuliahModel();
            $model = new \App\Entities\KelasKuliahEntity();
            $model->fill((array)$this->request->getJSON());
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
            $object = new \App\Models\KelasKuliahModel();
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

    public function paginate()
    {
        $item = $this->request->getJSON();
        $object = model(KelasKuliahModel::class);
        $item = [
            'status' => true,
            'data' => $object
                ->select("kelas_kuliah.*")
                ->join('semester', 'semester.id_semester=kelas_kuliah.id_semester', 'left')
                ->like('nama_kelas_kuliah', $item->cari)
                ->orLike('kode_mata_kuliah', $item->cari)
                ->orLike('nama_mata_kuliah', $item->cari)
                ->where('a_periode_aktif', '1')
                ->paginate($item->count, 'default', $item->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

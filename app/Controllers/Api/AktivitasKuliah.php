<?php

namespace App\Controllers\Api;

use App\Models\PerkuliahanMahasiswaModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class AktivitasKuliah extends ResourceController
{

    public function show($id = null)
    {
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object->findAll() : $object->where('id', $id)->first()
        ]);
    }

    public function showByMhs($id = null)
    {
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_riwayat_pendidikan', $id)->findAll()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('aktivitasKuliah')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $model = new \App\Entities\AktivitasKuliahEntity();
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
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $model = new \App\Entities\AktivitasKuliahEntity();
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
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
                'data'=>[]
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
        $object = new PerkuliahanMahasiswaModel();
        $item = [
            'status' => true,
            'data' => $object->paginate($param->count, 'default', $param->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
    
}

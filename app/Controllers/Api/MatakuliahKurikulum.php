<?php

namespace App\Controllers\Api;

use App\Models\MatakuliahKurikulumModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class MatakuliahKurikulum extends ResourceController
{

    public function show($id = null)
    {
        $object = new MatakuliahKurikulumModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object->findAll() : $object->where('id', $id)->first()
        ]);
    }

    public function by_kurikulum_id($id = null)
    {
        $object = new MatakuliahKurikulumModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('kurikulum_id', $id)->findAll()
        ]);
    }

    public function by_prodi($id = null)
    {
        $object = new MatakuliahKurikulumModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_prodi', $id)->findAll()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('matakuliahKurikulum')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\MatakuliahKurikulumModel();
            $model = new \App\Entities\MatakuliahKurikulumEntity();
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
            $object = new \App\Models\MatakuliahKurikulumModel();
            $model = new \App\Entities\MatakuliahKurikulumEntity();
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
            $object = new \App\Models\MatakuliahKurikulumModel();
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
}

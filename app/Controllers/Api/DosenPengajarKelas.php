<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class DosenPengajarKelas extends ResourceController
{

    /**
     * @param null $id
     * 
     * @return object
     */
    public function show($id = null):object
    {
        $object = new \App\Models\DosenPengajarKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object->findAll() : $object->where('id', $id)->first()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('pengajarKelas')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\DosenPengajarKelasModel();
            $model = new \App\Entities\DosenPengajarKelasEntity();
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
            $object = new \App\Models\DosenPengajarKelasModel();
            $model = new \App\Entities\DosenPengajarKelasEntity();
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
            $object = new \App\Models\DosenPengajarKelasModel();
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

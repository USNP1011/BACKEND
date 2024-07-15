<?php

namespace App\Controllers\Api;

use App\Models\DosenWaliModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class DosenWali extends ResourceController
{

    public function show($id = null, $req = null)
    {
        $object = new DosenWaliModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("dosen_wali.*, riwayat_pendidikan_mahasiswa.nim")->findAll()
        ]);
    }

    public function showByDsn($id = null)
    {
        $object = new DosenWaliModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_dosen', $id)->findAll()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('dosenWali')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\DosenWaliModel();
            $model = new \App\Entities\DosenWaliEntity();
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
            $object = new \App\Models\DosenWaliModel();
            $model = new \App\Entities\DosenWaliEntity();
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
            $object = new \App\Models\DosenWaliModel();
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

<?php

namespace App\Controllers\Api;

use App\Models\SkalaSKSModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Settings extends ResourceController
{
    protected $semesterAktif;
    public function __construct() {
        $this->semesterAktif = getSemesterAktif();
    }
    public function skalaSKS($id = null)
    {
        $object = new SkalaSKSModel();
        return $this->respond([
            'status' => true,
            'data' => is_null($id) ? $object->findAll() : $object->where('id', $id)->first()
        ]);
    }

    public function createSkalaSKS()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('skalaSKS')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $object = new \App\Models\SkalaSKSModel();
            $object->insert($item);
            $item->id = $object->getInsertID();
            return $this->respond([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }


    public function updateSkalaSKS($id = null)
    {
        try {
            $object = new \App\Models\SkalaSKSModel();
            $object->save($this->request->getJSON());
            return $this->respond([
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }


    public function deleteSkalaSKS($id = null)
    {
        try {
            $object = new \App\Models\SkalaSKSModel();
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
}
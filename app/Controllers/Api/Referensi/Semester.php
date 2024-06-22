<?php

namespace App\Controllers\Api\Referensi;

use App\Models\SemesterModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Semester extends ResourceController
{
    public function store($id=null)
    {
        $object = new SemesterModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object->findAll() : $object->where('id_semester', $id)->first()
        ]);
    }

    public function update($id = null)
    {
        $conn = \Config\Database::connect();
        $param = $this->request->getJSON();
        try {
            $conn->transBegin();
            $object = new \App\Models\SemesterModel();
            $model = new \App\Entities\Semester();
            $model->fill((array)$param);
            $object->set('a_periode_aktif', '0')->where('a_periode_aktif', '1')->update();
            $object->save($model);
            if($conn->transStatus()){
                $conn->transCommit();
                return $this->respond([
                    'status' => true,
                    'data' => $param
                ]); 
            }
            throw new \CodeIgniter\Database\Exceptions\DatabaseException();
        } catch (\Throwable $th) {
            $conn->transRollback();
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Controllers\Api\Referensi;

use App\Models\RuanganModel;
use CodeIgniter\RESTful\ResourceController;

class Ruangan extends ResourceController
{
    public function store($id=null)
    {
        $object = new RuanganModel();
        return $this->respond([
            'status' => true,
            'data' => is_null($id) ? $object->findAll() : $object->where('id', $id)->first()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            $object = new \App\Models\RuanganModel();
            $object->insert($item);
            $item->id = $object->getInsertID();
            return $this->respond([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }


    public function update($id = null)
    {
        try {
            $item = $this->request->getJSON();
            $object = new \App\Models\RuanganModel();
            $object->update($item->id, $item);
            return $this->respond([
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }


    public function delete($id = null)
    {
        try {
            $object = new \App\Models\RuanganModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

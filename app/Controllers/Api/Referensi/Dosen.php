<?php

namespace App\Controllers\Api\Referensi;

use App\Models\DosenModel;
use App\Models\NegaraModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Dosen extends ResourceController
{
    public function store($id=null)
    {
        $object = new DosenModel();
        return $this->respond([
            'status' => true,
            'data' => is_null($id) ?  $object->findAll(): $object->where('id_dosen', $id)->first()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            $item->id_dosen = Uuid::uuid4()->toString();
            $object = new \App\Models\DosenModel();
            $model = new \App\Entities\DosenEntity();
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
}

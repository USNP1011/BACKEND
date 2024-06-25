<?php

namespace App\Controllers\Api\Referensi;

use App\Models\DosenModel;
use App\Models\NegaraModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

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
}

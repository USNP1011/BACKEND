<?php

namespace App\Controllers\Api\Referensi;

use App\Models\DosenModel;
use App\Models\NegaraModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Dosen extends ResourceController
{
    public function store()
    {
        $object = new DosenModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

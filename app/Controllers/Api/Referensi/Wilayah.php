<?php

namespace App\Controllers\Api\Referensi;

use App\Models\WilayahModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Wilayah extends ResourceController
{
    public function store()
    {
        $object = new WilayahModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

<?php

namespace App\Controllers\Api\Referensi;

use App\Models\LevelWilayahModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LevelWilayah extends ResourceController
{
    public function store()
    {
        $object = new LevelWilayahModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

<?php

namespace App\Controllers\Api\Referensi;

use App\Models\ProdiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Prodi extends ResourceController
{
    public function store()
    {
        $object = new ProdiModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

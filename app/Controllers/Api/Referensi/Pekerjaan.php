<?php

namespace App\Controllers\Api\Referensi;

use App\Models\PekerjaanModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Pekerjaan extends ResourceController
{
    public function store()
    {
        $object = new PekerjaanModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

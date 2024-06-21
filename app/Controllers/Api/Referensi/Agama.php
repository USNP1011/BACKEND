<?php

namespace App\Controllers\Api\Referensi;

use App\Models\AgamaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Agama extends ResourceController
{
    public function store():ResponseInterface
    {
        $object = new AgamaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

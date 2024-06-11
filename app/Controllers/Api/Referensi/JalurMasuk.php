<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JalurMasukModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class JalurMasuk extends ResourceController
{
    public function store()
    {
        $object = new JalurMasukModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

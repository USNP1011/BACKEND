<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenisSmsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class JenisSMS extends ResourceController
{
    public function store()
    {
        $object = new JenisSmsModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

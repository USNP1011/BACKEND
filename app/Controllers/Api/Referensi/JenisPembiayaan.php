<?php

namespace App\Controllers\Api\Referensi;

use App\Models\PembiayaanModel;
use CodeIgniter\RESTful\ResourceController;

class JenisPembiayaan extends ResourceController
{
    public function store()
    {
        $object = new PembiayaanModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenisPeranModel;
use CodeIgniter\RESTful\ResourceController;

class JenisPeran extends ResourceController
{
    public function store()
    {
        $object = new JenisPeranModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

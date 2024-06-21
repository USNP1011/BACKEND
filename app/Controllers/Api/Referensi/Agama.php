<?php

namespace App\Controllers\Api\Referensi;

use App\Models\AgamaModel;
use CodeIgniter\RESTful\ResourceController;

class Agama extends ResourceController
{
    public function store()
    {
        $object = new AgamaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

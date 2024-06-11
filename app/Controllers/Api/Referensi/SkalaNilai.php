<?php

namespace App\Controllers\Api\Referensi;

use App\Models\SkalaNilaiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SkalaNilai extends ResourceController
{
    public function store()
    {
        $object = new SkalaNilaiModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

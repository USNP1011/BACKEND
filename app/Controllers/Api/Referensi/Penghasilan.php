<?php

namespace App\Controllers\Api\Referensi;

use App\Models\PenghasilanModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Penghasilan extends ResourceController
{
    public function store()
    {
        $object = new PenghasilanModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

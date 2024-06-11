<?php

namespace App\Controllers\Api\Referensi;

use App\Models\AgamaModel;
use App\Models\JenisTransportasiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Transportasi extends ResourceController
{
    public function store()
    {
        $object = new JenisTransportasiModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

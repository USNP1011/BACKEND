<?php

namespace App\Controllers\Api\Referensi;

use App\Models\AgamaModel;
use App\Models\JenisEvaluasiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Agama extends ResourceController
{
    public function store()
    {
        $object = new JenisEvaluasiModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenisEvaluasiModel;
use CodeIgniter\RESTful\ResourceController;

class JenisEvaluasi extends ResourceController
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

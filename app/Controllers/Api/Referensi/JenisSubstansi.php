<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenisSubstansiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class JenisSubstansi extends ResourceController
{
    public function store()
    {
        $object = new JenisSubstansiModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

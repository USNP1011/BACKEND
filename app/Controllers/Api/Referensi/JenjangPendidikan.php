<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenjangPendidikanModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class JenjangPendidikan extends ResourceController
{
    public function store()
    {
        $object = new JenjangPendidikanModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

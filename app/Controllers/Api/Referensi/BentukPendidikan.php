<?php

namespace App\Controllers\Api\Referensi;

use App\Models\BentukPendidikanModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class BentukPendidikan extends ResourceController
{
    public function store()
    {
        $object = new BentukPendidikanModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

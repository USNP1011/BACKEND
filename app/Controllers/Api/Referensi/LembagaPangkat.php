<?php

namespace App\Controllers\Api\Referensi;

use App\Models\LembagaPengangkatanModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LembagaPangkat extends ResourceController
{
    public function store()
    {
        $object = new LembagaPengangkatanModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

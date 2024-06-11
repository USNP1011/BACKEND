<?php

namespace App\Controllers\Api\Referensi;

use App\Models\KebutuhanKhususModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class KebutuhanKhusus extends ResourceController
{
    public function store()
    {
        $object = new KebutuhanKhususModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

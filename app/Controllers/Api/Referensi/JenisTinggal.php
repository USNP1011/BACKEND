<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenisTinggalModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class JenisTinggal extends ResourceController
{
    public function store()
    {
        $object = new JenisTinggalModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

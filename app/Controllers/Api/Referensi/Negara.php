<?php

namespace App\Controllers\Api\Referensi;

use App\Models\NegaraModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Negara extends ResourceController
{
    public function store()
    {
        $object = new NegaraModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

<?php

namespace App\Controllers\Api\Referensi;

use App\Controllers\BaseController;
use App\Models\AgamaModel;

class Agama extends BaseController
{
    public function store()
    {
        $object = new AgamaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

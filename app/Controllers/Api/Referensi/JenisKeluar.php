<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenisKeluarModel;
use App\Models\JenisPendaftaranModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class JenisPendaftaran extends ResourceController
{
    public function store()
    {
        $object = new JenisKeluarModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

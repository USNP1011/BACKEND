<?php

namespace App\Controllers\Api\Referensi;

use App\Models\PenugasanDosenModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PenugasanDosen extends ResourceController
{
    public function store()
    {
        $object = new PenugasanDosenModel();
        return $this->respond([
            'status' => true,
            'data' => $object->join('dosen', 'penugasan_dosen.id_dosen=dosen.id_dosen', 'left')->findAll()
        ]);
    }
}

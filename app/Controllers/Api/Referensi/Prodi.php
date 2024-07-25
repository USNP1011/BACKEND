<?php

namespace App\Controllers\Api\Referensi;

use App\Models\ProdiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Prodi extends ResourceController
{
    public function store()
    {
        $object = new ProdiModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("prodi.*, (SELECT nama_dosen FROM kaprodi LEFT JOIN dosen on dosen.id_dosen=kaprodi.id_dosen WHERE kaprodi.id_prodi=prodi.id_prodi AND kaprodi.status='1') as nama_dosen")->findAll()
        ]);
    }
}

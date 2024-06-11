<?php

namespace App\Controllers\Api\Referensi;

use App\Models\JenisAktivitasMahasiswaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class JenisAktivitas extends ResourceController
{
    public function store()
    {
        $object = new JenisAktivitasMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }
}

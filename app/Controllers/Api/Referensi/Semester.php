<?php

namespace App\Controllers\Api\Referensi;

use App\Models\SemesterModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Semester extends ResourceController
{
    public function store()
    {
        $semester = new SemesterModel();
        return $this->respond([
            'status' => true,
            'data' => $semester->findAll()
        ]);
    }
}

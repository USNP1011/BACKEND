<?php

namespace App\Controllers\Rest\Dosen;
use CodeIgniter\RESTful\ResourceController;

class Dosen extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }

    public function profile($id = null)
    {
        return $this->respond([
            'status' => true,
            'data' => getProfile()
        ]);
    }
}

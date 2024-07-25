<?php

namespace App\Controllers\Rest\Prodi;
use CodeIgniter\RESTful\ResourceController;

class Prodi extends ResourceController
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

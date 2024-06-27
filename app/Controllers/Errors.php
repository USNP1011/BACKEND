<?php

namespace App\Controllers;

class Errors extends BaseController
{
    public function show404()
    {
        return $this->failNotFound("URL yang anda tuju tidak terdaftar");
    }
}

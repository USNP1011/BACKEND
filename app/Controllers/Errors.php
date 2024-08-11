<?php

namespace App\Controllers;


class Errors extends BaseController
{
    public function show404()
    {
        return $this->failNotFound("URL ".base_url($this->request->getPath())." tidak ditemukan");
    }
}

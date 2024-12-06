<?php

namespace App\Controllers;


class Errors extends BaseController
{
    public function show404()
    {
        $error = "URL ".base_url($this->request->getPath())." tidak ditemukan";
        return $this->failNotFound($error);
    }
}

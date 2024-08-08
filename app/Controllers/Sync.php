<?php

namespace App\Controllers;

use stdClass;

class Sync extends BaseController
{
    public function getSync(): object
    {
        $conn = \Config\Database::connect();
        $data = new stdClass;

        // Periode Perkuliahan
        $object = new \App\Models\PeriodePerkuliahanModel();
        // $data->periode_perkuliahan = $conn->
        return $this->respond();
    }
}

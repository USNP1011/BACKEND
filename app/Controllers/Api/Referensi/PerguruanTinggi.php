<?php

namespace App\Controllers\Api\Referensi;

use App\Entities\PerguruanTinggi as EntitiesPerguruanTinggi;
use App\Models\PerguruanTinggiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PerguruanTinggi extends ResourceController
{
    public function store()
    {
        $item = new PerguruanTinggiModel();
        // $pt = new EntitiesPerguruanTinggi();
        $data = $item->first();
        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }
}

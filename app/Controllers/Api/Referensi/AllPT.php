<?php

namespace App\Controllers\Api\Referensi;

use App\Controllers\BaseController;
use App\Models\PTModel;

class AllPT extends BaseController
{
    public function store()
    {
        $param = $this->request->getJSON();
        $object = model(PTModel::class);
        $item = [
            'status' => true,
            'data' => $object
                ->like('kode_perguruan_tinggi', $param->cari)
                ->orLike('nama_perguruan_tinggi', $param->cari)
                ->orderBy('nama_perguruan_tinggi', 'asc')
                ->paginate($param->count, 'default', $param->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

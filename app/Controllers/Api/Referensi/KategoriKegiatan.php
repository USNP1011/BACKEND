<?php

namespace App\Controllers\Api\Referensi;

use App\Models\KategoriKegiatanModel;
use CodeIgniter\RESTful\ResourceController;

class KategoriKegiatan extends ResourceController
{
    public function store($req=null)
    {
        $object = new KategoriKegiatanModel();
        if($req == 'pembimbing'){
            return $this->respond([
                'status' => true,
                'data' => $object->where("SUBSTRING(id_kategori_kegiatan,1,4)='1104'")->findAll()
            ]);
        }else if($req=='penguji'){
            return $this->respond([
                'status' => true,
                'data' => $object->where("SUBSTRING(id_kategori_kegiatan,1,4)='1105'")->findAll()
            ]);
        }else{
            return $this->failNotFound("URL tujuan tidak terdaftar");
        }
    }
}

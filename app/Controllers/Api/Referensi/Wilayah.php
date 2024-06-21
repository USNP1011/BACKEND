<?php

namespace App\Controllers\Api\Referensi;

use App\Models\WilayahModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Wilayah extends ResourceController
{
    public function store()
    {
        $object = new WilayahModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }

    public function by_id($id_wilayah, $id_level_wilayah, $id_negara)
    {
        $object = new WilayahModel();
        if($id_level_wilayah=="0"){
            $setData = $object->where('id_level_wilayah', '1')->where('id_negara', $id_negara)->findAll();
        }
        else if($id_level_wilayah=="1"){
            $setData = $object->where("SUBSTRING(id_wilayah,1,2)=SUBSTRING('".$id_wilayah."',1,2)")->where('id_level_wilayah', '2')->findAll();
        }else if($id_level_wilayah=="2"){
            $setData = $object->where("SUBSTRING(id_wilayah,1,4)=SUBSTRING('".$id_wilayah."',1,4)")->where('id_level_wilayah', '3')->findAll();
        }
        return $this->respond([
            'status' => true,
            'data' => $setData
        ]);
    }
}

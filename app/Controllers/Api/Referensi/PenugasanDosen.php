<?php

namespace App\Controllers\Api\Referensi;

use App\Models\PenugasanDosenModel;
use CodeIgniter\RESTful\ResourceController;

class PenugasanDosen extends ResourceController
{
    public function store($id = null)
    {
        try {
            $object = new PenugasanDosenModel();
            if(is_null($id)){
                $data = $object->findAll();
            }else{
                $data = $object->select("penugasan_dosen.*, (SELECT tahun_ajaran.nama_tahun_ajaran FROM tahun_ajaran WHERE a_periode_aktif='1' LIMIT 1) as nama_tahun_ajaran")->where('id_registrasi_dosen', $id)->first();
            }
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

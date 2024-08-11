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
        $profile = getProfileProdi();
        $prodi = new \App\Models\ProdiModel();
        $itemProdi = $prodi->where('id_prodi', $profile->id_prodi)->first();
        $itemProdi->kaprodi = $profile->kaprodi;
        $itemProdi->kaprodi->nama_kaprodi = $profile->nama_dosen;
        return $this->respond([
            'status' => true,
            'data' => $itemProdi
        ]);
    }
}

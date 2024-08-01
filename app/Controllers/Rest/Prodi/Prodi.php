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
        $profile = getProfile();
        $prodi = new \App\Models\ProdiModel();
        $kaprodi = new \App\Models\KaprodiModel();
        $itemProdi = $prodi->where('id_prodi', $profile->id_prodi)->first();
        $itemProdi->kaprodi = $kaprodi->select('kaprodi.*, dosen.nama_dosen as nama_kaprodi, dosen.nidn')->join('dosen', 'dosen.id_dosen=kaprodi.id_dosen')->first();
        return $this->respond([
            'status' => true,
            'data' => $itemProdi
        ]);
    }
}

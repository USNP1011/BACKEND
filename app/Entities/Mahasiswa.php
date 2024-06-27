<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Mahasiswa extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_lahir', 'tanggal_lahir_ayah', 'tanggal_lahir_ibu', 'tanggal_lahir_wali', 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function setNamaMahasiswa($nama_mahasiswa) {
        $this->attributes['nama_mahasiswa'] = strtoupper($nama_mahasiswa);
        return $this;
    }
}

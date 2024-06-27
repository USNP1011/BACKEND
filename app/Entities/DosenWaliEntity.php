<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class DosenWaliEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function setNamaMahasiswa($nama_mahasiswa) {
        $this->attributes['nama_mahasiswa'] = strtoupper($nama_mahasiswa);
        return $this;
    }

    public function setNamaDosen($nama_dosen) {
        $this->attributes['nama_dosen'] = strtoupper($nama_dosen);
        return $this;
    }
}

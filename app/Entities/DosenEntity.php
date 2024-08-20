<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DosenEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_lahir', 'tanggal_sk_cpns'];
    protected $casts   = [];

    public function setNamaDosen($nama_dosen) {
        $this->attributes['nama_dosen'] = strtoupper($nama_dosen);
        return $this;
    }
}

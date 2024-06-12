<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class RiwayatPendidikanMahasiswa extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_daftar', 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    public function setId($id) {
        $this->attributes['id'] = Uuid::uuid4()->toString();
        return $this;
    }
}

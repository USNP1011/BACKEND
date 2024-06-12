<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RiwayatPendidikanMahasiswa extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_daftar', 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

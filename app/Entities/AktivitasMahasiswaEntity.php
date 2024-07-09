<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class AktivitasMahasiswaEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_sk_tugas', 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

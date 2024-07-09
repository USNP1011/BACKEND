<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class AktivitasKuliahEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_mulai_efektif', "tanggal_selesai_efektif", 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

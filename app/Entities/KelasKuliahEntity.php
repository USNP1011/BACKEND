<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class KelasKuliahEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_mulai_efektif', 'tanggal_akhir_efektif', 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

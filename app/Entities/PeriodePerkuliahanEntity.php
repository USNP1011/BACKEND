<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PeriodePerkuliahanEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_awal_perkuliahan', "tanggal_akhir_perkuliahan", 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

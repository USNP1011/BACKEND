<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Semester extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_mulai', 'tanggal_selesai', 'batas_pengisian_krsm'];
    protected $casts   = [];
}

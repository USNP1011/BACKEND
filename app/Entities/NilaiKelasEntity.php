<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class NilaiKelasEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

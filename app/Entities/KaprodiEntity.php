<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class KaprodiEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_sk', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

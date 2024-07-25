<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class KaprodiEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_sk'];
    protected $casts   = [];
}

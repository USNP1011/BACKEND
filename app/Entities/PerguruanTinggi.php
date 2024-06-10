<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PerguruanTinggi extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at', 'tanggal_sk_pendirian'];
    protected $casts   = [];
}

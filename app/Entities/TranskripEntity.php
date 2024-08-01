<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class TranskripEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['sync_at', 'created_at', 'updated_at'];
    protected $casts   = [];
}

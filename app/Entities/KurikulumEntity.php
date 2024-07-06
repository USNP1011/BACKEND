<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class KurikulumEntity extends Entity
{
    protected $datamap = [
        'nama_semester'=>'semester_mulai_berlaku'
    ];
    protected $dates   = ['sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    
}

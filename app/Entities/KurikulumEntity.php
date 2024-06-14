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

    public function setId($id) {
        if($id==null){
            $this->attributes['id'] = Uuid::uuid4()->toString();
            return $this;
        }else{
            $this->attributes['id'] = $id;
            return $this;
        } 
    }
}

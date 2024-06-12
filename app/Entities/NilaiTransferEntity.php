<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class NilaiTransferEntity extends Entity
{
    protected $datamap = [];
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
    public function setNamaMahasiswa($nama_mahasiswa) {
        $this->attributes['nama_mahasiswa'] = strtoupper($nama_mahasiswa);
        return $this;
    }
    public function setNamaMataKuliahAsal($nama_mata_kuliah_asal) {
        $this->attributes['nama_mata_kuliah_asal'] = strtoupper($nama_mata_kuliah_asal);
        return $this;
    }

    public function setNamaMataKuliahDiakui($nama_mata_kuliah_diakui) {
        $this->attributes['nama_mata_kuliah_diakui'] = strtoupper($nama_mata_kuliah_diakui);
        return $this;
    }
}

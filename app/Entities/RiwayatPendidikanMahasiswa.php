<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Ramsey\Uuid\Uuid;

class RiwayatPendidikanMahasiswa extends Entity
{
    protected $datamap = [];
    protected $dates   = ['tanggal_daftar','tanggal_keluar', 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
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
}

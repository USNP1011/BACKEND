<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class MahasiswaLulusDOEntity extends Entity
{
    protected $datamap = [
        'id_semester'=>'id_periode_keluar'
    ];
    protected $dates   = ['tanggal_keluar', 'tanggal_sk_yudisium', 'bulan_awal_bimbingan', 'bulan_akhir_bimbingan', 'sync_at', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
}

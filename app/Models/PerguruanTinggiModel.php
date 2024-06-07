<?php

namespace App\Models;

use CodeIgniter\Model;

class PerguruanTinggiModel extends Model
{
    protected $table            = 'profil_pt';
    protected $primaryKey       = 'id_perguruan_tinggi';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_perguruan_tinggi',
        'nama_perguruan_tinggi',
        'telepon',
        'faximile',
        'website',
        'jalan',
        'dusun',
        'rt_rw',
        'kelurahan',
        'kode_pos',
        'id_wilayah',
        'lintang_bujur',
        'bank',
        'unit_cabang',
        'nomor_rekening',
        'mbs',
        'luas_tanah_milik',
        'luas_tanah_bukan_milik',
        'sk_pendirian',
        'tanggal_sk_pendirian',
        'id_status_milik',
        'nama_status_milik',
        'status_perguruan_tinggi',
        'sk_izin_operasional',
        'tanggal_izin_operasional',
        'email',
        'nama_wilayah'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

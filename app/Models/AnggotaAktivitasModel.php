<?php namespace App\Models;

use CodeIgniter\Model;

class AnggotaAktivitasModel extends Model
{
    protected $table = 'anggota_aktivitas';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_anggota',
		'id_aktivitas',
		'id_registrasi_mahasiswa',
		'jenis_peran',
		'status_sync'
    ];
}

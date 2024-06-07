<?php namespace App\Models;

use CodeIgniter\Model;

class AnggotaAktivitasModel extends Model
{
    protected $table = 'anggota_aktivitas';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_anggota',
		'id_aktivitas',
        'judul',
		'id_registrasi_mahasiswa',
        'nim',
        'nama_mahasiswa',
		'jenis_peran',
		'nama_jenis_peran',
		'status_sync'
    ];
    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

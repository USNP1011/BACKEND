<?php namespace App\Models;

use CodeIgniter\Model;

class PerkuliahanMahasiswaModel extends Model
{
    protected $table = 'perkuliahan_mahasiswa';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\AktivitasKuliahEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_riwayat_pendidikan',
		'id_semester',
		'id_status_mahasiswa',
		'ips',
		'ipk',
		'sks_semester',
		'sks_total',
		'biaya_kuliah_smt',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at',
		'status_sync',
        'id_pembiayaan'
    ];
	protected bool $allowEmptyInserts = false;
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

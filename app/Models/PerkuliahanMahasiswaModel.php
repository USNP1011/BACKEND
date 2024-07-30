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
        'id_registrasi_mahasiswa',
        'id_riwayat_pendidikan',
		'id_semester',
        'nama_semester',
        'nama_program_studi',
		'id_status_mahasiswa',
		'nama_status_mahasiswa',
		'ips',
		'ipk',
		'sks_semester',
		'sks_total',
		'biaya_kuliah_smt',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at',
		'status_sync'
    ];
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

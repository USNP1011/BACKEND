<?php namespace App\Models;

use CodeIgniter\Model;

class PerkuliahanMahasiswaModel extends Model
{
    protected $table = 'perkuliahan_mahasiswa';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_registrasi_mahasiswa',
		'id_semester',
		'id_status_mahasiswa',
		'ips',
		'ipk',
		'sks_semester',
		'total_sks',
		'biaya_kuliah_smt',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at'
    ];
}

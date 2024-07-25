<?php namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semester';
    protected $primaryKey = 'id_semester';  
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\Semester';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'id_tahun_ajaran',
		'nama_semester',
		'semester',
		'a_periode_aktif',
		'tanggal_mulai',
		'tanggal_selesai',
        'status_kuliah'
    ];
}

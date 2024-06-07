<?php namespace App\Models;

use CodeIgniter\Model;

class KelasKuliahModel extends Model
{
    protected $table = 'kelas_kuliah';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_kelas_kuliah',
		'id_semester',
		'id_prodi',
		'id_matkul',
		'nama_program_studi',
		'nama_kelas_kuliah',
		'kode_mata_kuliah',
		'nama_mata_kuliah',
		'bahasan',
		'tanggal_mulai_efektif',
		'tanggal_akhir_efektif',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at'
    ];
}

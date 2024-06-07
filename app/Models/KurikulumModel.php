<?php namespace App\Models;

use CodeIgniter\Model;

class KurikulumModel extends Model
{
    protected $table = 'kurikulum';
    protected $primaryKey = 'id_kurikulum';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_kurikulum',
		'id_prodi',
		'id_semester',
		'jumlah_sks_lulus',
		'jumlah_sks_wajib',
		'jumlah_sks_pilihan',
		'nama_program_studi',
		'jumlah_sks_mata_kuliah_wajib',
		'jumlah_sks_mata_kuliah_pilihan',
		'semester_mulai_berlaku',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at'
    ];
}

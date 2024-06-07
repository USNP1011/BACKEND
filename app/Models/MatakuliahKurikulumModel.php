<?php namespace App\Models;

use CodeIgniter\Model;

class MatakuliahKurikulumModel extends Model
{
    protected $table = 'matakuliah_kurikulum';
    protected $primaryKey = 'id';   
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
		'id_kurikulum',
		'id_matkul',
        'id_semester',
		'sks_mata_kuliah',
		'sks_praktek',
		'sks_praktek_lapangan',
		'sks_simulasi',
		'apakah_wajib',
		'nama_kurikulum',
		'kode_mata_kuliah',
		'nama_mata_kuliah',
		'id_prodi',
		'nama_program_studi',
		'semester',
		'semester_mulai_berlaku',
		'sks_tatap_muka',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at',
		'tgl_create',
		'status_sync'
    ];
}

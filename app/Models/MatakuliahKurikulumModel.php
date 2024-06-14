<?php namespace App\Models;

use CodeIgniter\Model;

class MatakuliahKurikulumModel extends Model
{
    protected $table = 'matakuliah_kurikulum';
    protected $primaryKey = 'id';   
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\MatakuliahKurikulumEntity';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
		'kurikulum_id',
		'matakuliah_id',
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
		'sync_at',
		'status_sync'
    ];
	protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

<?php namespace App\Models;

use CodeIgniter\Model;

class KurikulumModel extends Model
{
    protected $table = 'kurikulum';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\KurikulumEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
		'id_kurikulum',
        'nama_kurikulum',
		'id_prodi',
		'id_semester',
		'jumlah_sks_lulus',
		'jumlah_sks_wajib',
		'jumlah_sks_pilihan',
		'nama_program_studi',
		'semester_mulai_berlaku',
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

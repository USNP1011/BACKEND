<?php namespace App\Models;

use CodeIgniter\Model;

class DosenWaliModel extends Model
{
    protected $table = 'dosen_wali';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\DosenWaliEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_riwayat_pendidikan',
        'id_dosen',
		'nama_mahasiswa',
        'nim',
        'id_prodi',
        'nama_program_studi',
		'nama_dosen',
		'nidn',
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

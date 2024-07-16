<?php namespace App\Models;

use CodeIgniter\Model;

class PesertaKelasModel extends Model
{
    protected $table = 'peserta_kelas';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\PesertaKelasEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
		'kelas_kuliah_id',
		'id_riwayat_pendidikan',
		'nilai_angka',
		'nilai_huruf',
		'nilai_indeks',
		'sync_at',
		'status_sync',
    ];
	protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

}

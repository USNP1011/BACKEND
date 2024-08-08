<?php namespace App\Models;

use CodeIgniter\Model;

class PeriodePerkuliahanModel extends Model
{
    protected $table = 'periode_perkuliahan';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\PeriodePerkuliahanEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_prodi',
		'id_semester',
		'jumlah_target_mahasiswa_baru',
		'jumlah_pendaftar_ikut_seleksi',
		'jumlah_pendaftar_lulus_seleksi',
		'jumlah_daftar_ulang',
		'jumlah_mengundurkan_diri',
		'jumlah_minggu_pertemuan',
		'tanggal_awal_perkuliahan',
		'tanggal_akhir_perkuliahan',
		'sync_at',
		'status_sync',
    ];
    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

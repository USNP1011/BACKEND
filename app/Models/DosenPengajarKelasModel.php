<?php namespace App\Models;

use CodeIgniter\Model;

class DosenPengajarKelasModel extends Model
{
    protected $table = 'dosen_pengajar_kelas';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\DosenPengajarKelasEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_aktivitas_mengajar',
        'id_registrasi_dosen',
        'id_dosen', 
		'kelas_kuliah_id',
        'nama_kelas_kuliah',
		'id_substansi',
		'sks_substansi_total',
		'rencana_minggu_pertemuan',
		'realisasi_minggu_pertemuan',
		'id_jenis_evaluasi',
        'nama_jenis_evaluasi',
        'status_sync',
        'sync_at'
    ];
    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

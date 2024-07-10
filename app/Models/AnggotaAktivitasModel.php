<?php namespace App\Models;

use CodeIgniter\Model;

class AnggotaAktivitasModel extends Model
{
    protected $table = 'anggota_aktivitas';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\AnggotaAktivitasMahasiswaEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_anggota',
		'aktivitas_mahasiswa_id',
		'id_riwayat_pendidikan',
		'jenis_peran',
		'nama_jenis_peran',
		'status_sync',
        'sync_at'
    ];
    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
}

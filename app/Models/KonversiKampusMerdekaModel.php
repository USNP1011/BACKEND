<?php namespace App\Models;

use CodeIgniter\Model;

class KonversiKampusMerdekaModel extends Model
{
    protected $table = 'konversi_kampus_merdeka';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\KonversiKampusMerdekaEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_konversi_aktivitas',
        'id_semester',
        'matakuliah_id',
        'anggota_aktivitas_id',
        'aktivitas_mahasiswa_id',
        'nilai_angka',
        'nilai_indeks',
        'nilai_huruf',
		'sync_at',
		'status_sync'
    ];
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

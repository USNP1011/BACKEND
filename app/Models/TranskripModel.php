<?php namespace App\Models;

use CodeIgniter\Model;

class TranskripModel extends Model
{
    protected $table = 'transkrip';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\TranskripEntity';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_riwayat_pendidikan',
        'matakuliah_id',
        'kelas_kuliah_id',
        'nilai_transfer_id',
        'konversi_kampus_merdeka_id',
        'nilai_angka',
        'nilai_huruf',
        'nilai_indeks',
		'sync_at',
		'status_sync'
    ];
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

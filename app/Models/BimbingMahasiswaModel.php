<?php namespace App\Models;

use CodeIgniter\Model;

class BimbingMahasiswaModel extends Model
{
    protected $table = 'bimbing_mahasiswa';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_bimbing_mahasiswa',
		'bimbing_mahasiswacol',
		'id_aktivitas',
		'id_kategori_kegiatan',
		'nama_kategori_kegiatan',
		'id_dosen',
		'nidn',
		'nama_dosen',
		'pembimbing_ke',
		'judul',
		'status_sync'
    ];
    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

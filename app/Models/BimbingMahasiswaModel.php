<?php namespace App\Models;

use CodeIgniter\Model;

class BimbingMahasiswaModel extends Model
{
    protected $table = 'bimbing_mahasiswa';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_bimbing_mahasiswa',
		'bimbing_mahasiswacol',
		'aktivitas_id',
		'id_kategori_kegiatan',
		'dosen_id_dosen',
		'status_sync'
    ];
}

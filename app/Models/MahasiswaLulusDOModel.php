<?php namespace App\Models;

use CodeIgniter\Model;

class MahasiswaLulusDOModel extends Model
{
    protected $table = 'mahasiswa_lulus_do';
    protected $primaryKey = 'id_riwayat_pendidikan';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\MahasiswaLulusDOEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_jenis_keluar',
        'tanggal_keluar',
        'keterangan',
        'nomor_sk_yudisium',
        'tanggal_sk_yudisium',
        'ipk',
        'nomor_ijazah',
        'jalur_skripsi',
        'judul_skripsi',
        'no_sertifikat_profesi',
        'bulan_awal_bimbingan',
        'bulan_akhir_bimbingan',
        'id_periode_keluar',
		'sync_at',
		'status_sync'
    ];
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

<?php namespace App\Models;

use CodeIgniter\Model;

class NilaiTransferModel extends Model
{
    protected $table = 'nilai_transfer';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\NilaiTransferEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_transfer',
		'id_riwayat_pendidikan',
		'id_registrasi_mahasiswa',
		'id_matkul',
		'id_prodi',
		'kode_mata_kuliah_asal',
		'nama_mata_kuliah_asal',
		'sks_mata_kuliah_asal',
		'sks_mata_kuliah_diakui',
		'nilai_huruf_asal',
		'nilai_huruf_diakui',
		'nilai_angka_diakui',
		'nim',
		'nama_mahasiswa',
		'nama_program_studi',
		'id_periode_masuk',
		'kode_matkul_diakui',
		'nama_mata_kuliah_diakui',
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

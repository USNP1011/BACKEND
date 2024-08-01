<?php namespace App\Models;

use CodeIgniter\Model;

class RiwayatPendidikanMahasiswaModel extends Model
{
    protected $table = 'riwayat_pendidikan_mahasiswa';
    protected $primaryKey = 'id';  
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\RiwayatPendidikanMahasiswa';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;  
    protected $allowedFields = [
		'id_registrasi_mahasiswa',
        'nim',
		'id_jenis_daftar',
		'id_jalur_daftar',
		'id_periode_masuk',
		'tanggal_daftar',
		'id_mahasiswa',
		'id_prodi',
		'id_perguruan_tinggi',
		'sks_diakui',
		'id_perguruan_tinggi_asal',
		'id_prodi_asal',
		'id_pembiayaan',
		'id_jenis_keluar',
		'kelas_id',
		'nama_jenis_daftar',
		'nama_periode_masuk',
		'nama_perguruan_tinggi',
		'keterangan_keluar',
		'nama_program_studi',
		'nama_perguruan_tinggi_asal',
		'nama_program_studi_asal',
		'nama_ibu_kandung',
		'nama_pembiayaan_awal',
		'biaya_masuk',
		'nm_bidang_minat',
		'tanggal_keluar',
		'angkatan',
		'status_sync',
		'last_update',
		'tgl_create',
		'jenis_kelamin',
		'id_bidang_minat',
		'id_periode_keluar'
    ];
	protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

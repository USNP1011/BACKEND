<?php namespace App\Models;

use CodeIgniter\Model;

class PesertaKelasModel extends Model
{
    protected $table = 'peserta_kelas';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
		'id_kelas_kuliah',
		'nama_kelas_kuliah',
		'id_registrasi_mahasiswa',
		'id_mahasiswa',
		'nim',
		'nama_mahasiswa',
		'id_matkul',
		'kode_mata_kuliah',
		'nama_mata_kuliah',
		'id_prodi',
		'nama_program_studi',
		'angkatan',
		'nilai_angka',
		'nilai_huruf',
		'nilai_indeks',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at',
		'status_sync',
    ];
}

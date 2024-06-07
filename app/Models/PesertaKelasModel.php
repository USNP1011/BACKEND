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
        'riwayat_pendidikan_mahasiswa_id',
		'kelas_kuliah_id',
		'id_registrasi_mahasiswa',
		'id_kelas_kuliah',
		'nilai_angka',
		'nilai_huruf',
		'nilai_indeks',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at'
    ];
}

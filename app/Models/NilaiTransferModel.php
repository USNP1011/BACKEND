<?php namespace App\Models;

use CodeIgniter\Model;

class NilaiTransferModel extends Model
{
    protected $table = 'nilai_transfer';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_transfer',
		'id_registrasi_mahasiswa',
		'id_matkul',
		'kode_mata_kuliah_asal',
		'nama_mata_kuliah_asal',
		'sks_mata_kuliah_asal',
		'sks_mata_kuliah_diakui',
		'nilai_huruf_asal',
		'nilai_huruf_diakui',
		'nilai_angka_diakui'
    ];
}

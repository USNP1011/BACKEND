<?php namespace App\Models;

use CodeIgniter\Model;

class PrestasiMahasiswaModel extends Model
{
    protected $table = 'prestasi_mahasiswa';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_prestasi',
		'mahasiswa_id',
		'id_jenis_prestasi',
		'id_tingkat_prestasi',
		'nama_prestasi',
		'tahun_prestasi',
		'penyelenggara',
		'peringkat'
    ];
}

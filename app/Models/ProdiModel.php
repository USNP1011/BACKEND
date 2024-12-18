<?php namespace App\Models;

use CodeIgniter\Model;

class ProdiModel extends Model
{
    protected $table = 'prodi';
    protected $primaryKey = 'id_prodi';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'kode_program_studi',
		'nama_program_studi',
		'status',
		'id_jenjang_pendidikan',
		'id_perguruan_tinggi',
		'nama_jenjang_pendidikan',
        'jenjang'
    ];
}

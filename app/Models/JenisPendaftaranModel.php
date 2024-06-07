<?php namespace App\Models;

use CodeIgniter\Model;

class JenisPendaftaranModel extends Model
{
    protected $table = 'jenis_pendaftaran';
    protected $primaryKey = 'id_jenis_daftar';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_jenis_daftar',
		'untuk_daftar_sekolah'
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class JenisKeluarModel extends Model
{
    protected $table = 'jenis_keluar';
    protected $primaryKey = 'id_jenis_keluar';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'jenis_keluar',
		'apa_mahasiswa'
    ];
}

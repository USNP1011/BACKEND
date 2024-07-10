<?php namespace App\Models;

use CodeIgniter\Model;

class JenisPeranModel extends Model
{
    protected $table = 'peran_peserta';
    protected $primaryKey = 'jenis_peran';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_jenis_peran'
    ];
}

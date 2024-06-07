<?php namespace App\Models;

use CodeIgniter\Model;

class JenisSubstansiModel extends Model
{
    protected $table = 'jenis_substansi';
    protected $primaryKey = 'id_jenis_substansi';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_jenis_substansi'
    ];
}

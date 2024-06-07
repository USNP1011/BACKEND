<?php namespace App\Models;

use CodeIgniter\Model;

class IkatanKerjaSdmModel extends Model
{
    protected $table = 'ikatan_kerja_sdm';
    protected $primaryKey = 'id_ikatan_kerja';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_ikatan_kerja'
    ];
}

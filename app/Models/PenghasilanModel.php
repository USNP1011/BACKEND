<?php namespace App\Models;

use CodeIgniter\Model;

class PenghasilanModel extends Model
{
    protected $table = 'penghasilan';
    protected $primaryKey = 'id_penghasilan';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_penghasilan'
    ];
}

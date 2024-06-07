<?php namespace App\Models;

use CodeIgniter\Model;

class PembiayaanModel extends Model
{
    protected $table = 'pembiayaan';
    protected $primaryKey = 'id_pembiayaan';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_pembiayaan'
    ];
}

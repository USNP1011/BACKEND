<?php namespace App\Models;

use CodeIgniter\Model;

class LembagaPengangkatanModel extends Model
{
    protected $table = 'lembaga_pengangkatan';
    protected $primaryKey = 'id_lembaga_angkat';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_lembaga_angkat'
    ];
}

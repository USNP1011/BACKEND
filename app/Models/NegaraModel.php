<?php namespace App\Models;

use CodeIgniter\Model;

class NegaraModel extends Model
{
    protected $table = 'negara';
    protected $primaryKey = 'id_negara';   
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
        'nama_negara'
    ];
}

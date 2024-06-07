<?php namespace App\Models;

use CodeIgniter\Model;

class PekerjaanModel extends Model
{
    protected $table = 'pekerjaan';
    protected $primaryKey = 'id_pekerjaan';   
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
        'nama_pekerjaan'
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class JafungModel extends Model
{
    protected $table = 'jafung';
    protected $primaryKey = 'id_jabatan_fungsional';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_jabatan_fungsional'
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class LevelWilayahModel extends Model
{
    protected $table = 'level_wilayah';
    protected $primaryKey = 'id_level_wilayah';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_level_wilayah'
    ];
}

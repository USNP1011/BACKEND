<?php namespace App\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'id_level_wilayah',
		'id_negara',
		'nama_wilayah'
    ];
}

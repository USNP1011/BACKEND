<?php namespace App\Models;

use CodeIgniter\Model;

class KriteriaModel extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nm_kriteria',
		'bobot',
		'jenis'
    ];
}

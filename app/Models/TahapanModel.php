<?php namespace App\Models;

use CodeIgniter\Model;

class TahapanModel extends Model
{
    protected $table = 'tahapan';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'tahapan',
        'urutan'
    ];
}

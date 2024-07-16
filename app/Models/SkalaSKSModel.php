<?php namespace App\Models;

use CodeIgniter\Model;

class SkalaSKSModel extends Model
{
    protected $table = 'skala_sks';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'ips_min',
        'ips_max',
        'sks_max',
    ];
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

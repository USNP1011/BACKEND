<?php namespace App\Models;

use CodeIgniter\Model;

class KebutuhanKhususModel extends Model
{
    protected $table = 'kebutuhan_khusus';
    protected $primaryKey = 'id_kebutuhan_khusus';   
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
        'nama_kebutuhan_khusus'
    ];
}

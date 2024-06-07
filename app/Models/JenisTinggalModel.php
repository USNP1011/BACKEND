<?php namespace App\Models;

use CodeIgniter\Model;

class JenisTinggalModel extends Model
{
    protected $table = 'jenis_tinggal';
    protected $primaryKey = 'id_jenis_tinggal';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_jenis_tinggal'
    ];
}

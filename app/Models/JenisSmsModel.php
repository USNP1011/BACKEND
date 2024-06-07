<?php namespace App\Models;

use CodeIgniter\Model;

class JenisSmsModel extends Model
{
    protected $table = 'jenis_sms';
    protected $primaryKey = 'id_jenis_sms';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_jenis_sms'
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class JalurMasukModel extends Model
{
    protected $table = 'jalur_masuk';
    protected $primaryKey = 'id_jalur_masuk';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_jalur_masuk'
    ];
}

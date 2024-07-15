<?php namespace App\Models;

use CodeIgniter\Model;

class RuanganModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_ruangan'
    ];
}

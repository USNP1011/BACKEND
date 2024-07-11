<?php namespace App\Models;

use CodeIgniter\Model;

class TingkatPrestasiModel extends Model
{
    protected $table = 'tingkat_prestasi';
    protected $primaryKey = 'id_tingkat_prestasi';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_tingkat_prestasi'
    ];
}

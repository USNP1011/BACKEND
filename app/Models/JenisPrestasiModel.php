<?php namespace App\Models;

use CodeIgniter\Model;

class JenisPrestasiModel extends Model
{
    protected $table = 'jenis_prestasi';
    protected $primaryKey = 'id_jenis_prestasi';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_jenis_prestasi'
    ];
}

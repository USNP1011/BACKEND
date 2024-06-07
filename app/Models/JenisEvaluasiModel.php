<?php namespace App\Models;

use CodeIgniter\Model;

class JenisEvaluasiModel extends Model
{
    protected $table = 'jenis_evaluasi';
    protected $primaryKey = 'id_jenis_evaluasi';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_jenis_evaluasi'
    ];
}

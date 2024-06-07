<?php namespace App\Models;

use CodeIgniter\Model;

class JenisSertifikasiModel extends Model
{
    protected $table = 'jenis_sertifikasi';
    protected $primaryKey = 'id_jenis_sertifikasi';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_jenis_sertifikasi'
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class JenisTransportasiModel extends Model
{
    protected $table = 'jenis_transportasi';
    protected $primaryKey = 'id_alat_transportasi';   
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
        'nama_alat_transportasi'
    ];
}

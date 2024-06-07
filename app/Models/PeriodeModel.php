<?php namespace App\Models;

use CodeIgniter\Model;

class PeriodeModel extends Model
{
    protected $table = 'periode';
    protected $primaryKey = 'id_periode';   
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
        "id_prodi","kode_prodi","nama_program_studi","status_prodi","jenjang_pendidikan","periode_pelaporan","tipe_periode"
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class StatusMahasiswaModel extends Model
{
    protected $table = 'status_mahasiswa';
    protected $primaryKey = 'id_status_mahasiswa';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'nama_status_mahasiswa'
    ];
}

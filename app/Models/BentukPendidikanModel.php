<?php namespace App\Models;

use CodeIgniter\Model;

class BentukPendidikanModel extends Model
{
    protected $table = 'bentuk_pendidikan';
    protected $primaryKey = 'id_bentuk_pendidikan';   
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
        'nama_bentuk_pendidikan'
    ];
}

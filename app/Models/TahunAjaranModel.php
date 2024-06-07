<?php namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends Model
{
    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';   
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true; 
    protected $allowedFields = [
        'nama_tahun_ajaran',
		'a_periode_aktif',
		'tanggal_mulai',
		'tanggal_selesai'
    ];
}

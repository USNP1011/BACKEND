<?php namespace App\Models;

use CodeIgniter\Model;

class KategoriKegiatanModel extends Model
{
    protected $table = 'kategori_kegiatan';
    protected $primaryKey = 'id_kategori_kegiatan';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_kategori_kegiatan'
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class JenisAktivitasMahasiswaModel extends Model
{
    protected $table = 'jenis_aktivitas';
    protected $primaryKey = 'id_jenis_aktivitas_mahasiswa';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_jenis_aktivitas_mahasiswa',
		'untuk_kampus_merdeka'
    ];
}

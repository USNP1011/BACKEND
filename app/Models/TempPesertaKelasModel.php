<?php namespace App\Models;

use CodeIgniter\Model;

class TempPesertaKelasModel extends Model
{
    protected $table = 'temp_peserta_kelas';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'kelas_kuliah_id',
        'id_riwayat_pendidikan',
        'temp_krsm_id',
    ];
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
}

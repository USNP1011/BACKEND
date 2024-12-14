<?php namespace App\Models;

use CodeIgniter\Model;

class NilaiPesertaKelasModel extends Model
{
    protected $table = 'nilai_kelas';
    protected $primaryKey = 'id_nilai_kelas';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\NilaiKelasEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_nilai_kelas',
        'nilai_angka',
        'nilai_huruf',
        'nilai_indeks',
        'sync_at',
        'status_sync'
    ];
    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

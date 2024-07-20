<?php namespace App\Models;

use CodeIgniter\Model;

class TempKrsmModel extends Model
{
    protected $table = 'temp_krsm';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\TempKrsmEntity';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_riwayat_pendidikan',
        'id_semester',
        'id_tahapan',
    ];
	protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}

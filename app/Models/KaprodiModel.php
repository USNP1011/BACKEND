<?php namespace App\Models;

use CodeIgniter\Model;

class KaprodiModel extends Model
{
    protected $table = 'kaprodi';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\KaprodiEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_dosen',
        'id_prodi',
        'no_sk',
        'tanggal_sk',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

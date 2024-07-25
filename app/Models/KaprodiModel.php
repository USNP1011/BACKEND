<?php namespace App\Models;

use CodeIgniter\Model;

class KaprodiModel extends Model
{
    protected $table = 'kaprodi';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\KaprodiEntity';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_dosen',
        'id_prodi',
        'no_sk',
        'tanggal_sk',
        'status'
    ];
}

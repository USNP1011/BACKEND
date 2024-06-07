<?php

namespace App\Models;

use CodeIgniter\Model;

class AgamaModel extends Model
{
    protected $table            = 'agama';
    protected $primaryKey       = 'id_agama';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_agama'
    ];
}

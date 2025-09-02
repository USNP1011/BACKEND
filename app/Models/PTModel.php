<?php

namespace App\Models;

use CodeIgniter\Model;

class PTModel extends Model
{
    protected $table            = 'pt';
    protected $primaryKey       = 'id_perguruan_tinggi';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_perguruan_tinggi',
        'nama_perguruan_tinggi',
        'nama_singkat'
    ];
}

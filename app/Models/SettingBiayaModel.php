<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingBiayaModel extends Model
{
    protected $table            = 'setting_biaya';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_prodi',
        'angkatan',
        'biaya',
    ];
}

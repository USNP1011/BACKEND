<?php 

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class MaintenanceMode extends BaseConfig
{

    //--------------------------------------------------------------------
    // maintenance mode file path
    //--------------------------------------------------------------------
    // 
    //
    public $FilePath = WRITEPATH . 'framework/';
    public $FileName = 'down';
}

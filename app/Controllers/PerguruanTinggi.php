<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Rest;
use App\Models\PerguruanTinggiModel;
use CodeIgniter\HTTP\ResponseInterface;

class PerguruanTinggi extends BaseController
{
    protected $api;
    protected $pt;
    public function __construct() {
        $this->api = new Rest();
        $this->pt = new PerguruanTinggiModel();
    }
    public function index()
    {
        $data = $this->api->getData();
        $this->pt->insert($data->data[0]);
        return response()->setJSON($data);
    }
}

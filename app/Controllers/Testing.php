<?php

namespace App\Controllers;

use Ramsey\Uuid\Uuid;
class Testing extends BaseController
{
    function read()
    {
        return "A";
    }
    public function uuid()
    {
        echo Uuid::uuid4()->toString();
    }
}

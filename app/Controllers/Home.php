<?php

namespace App\Controllers;


class Home extends BaseController
{
    public function index(): object
    {
        $routes = service('routes');
        $data = $routes->getRoutes();

        $propertyNames = array_keys($data);

        $routeNames = [];

        foreach ($propertyNames as $propertyName) {
            try {
                $name =$propertyName;
                if ($name) {
                    $routeNames[] = $name;
                }
            } catch (\Throwable $th) {
                continue;
            }
        }
        return $this->respond($routeNames);
    }
}

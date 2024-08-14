<?php

namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Exception;

class GeneralAuth implements FilterInterface
{
    use ResponseTrait;

    public function __construct()
    {
        // initialize
        helper('jwt');
    }
    /**
     * Do whatever processing this filter needs to do. By default it should not return anything during normal 
     * execution. However, when an abnormal state is found, it should return an instance of CodeIgniterHTTPResponse. 
     * If it does, script execution will end and that Response will be sent back to the client, allowing for 
     * error pages, redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticationHeader = $request->getServer('HTTP_AUTHORIZATION');
        try {
            $check = new \CodeigniterExt\MaintenanceMode\Controllers\MaintenanceMode();
            $itemCheck = $check->check();
            if (is_array($itemCheck)) {
                $admin = false;
                $mahasiswa = false;
                $keuangan = false;
                $dosen = false;
                $prodi = false;
                $all = false;
                foreach ($itemCheck['user'] as $key => $value) {
                    if ($value == "Admin") $admin = true;
                    else if($value == "Mahasiswa") $mahasiswa = true;
                    else if($value == "Dosen") $dosen = true;
                    else if($value == "Prodi") $prodi = true;
                    else if($value == "Keuangan") $keuangan = true;
                    else if($value == "All") $all = true;
                }
                if (($admin && $mahasiswa && $dosen && $prodi && $keuangan) || $all) throw new Exception("Sedang Maintenace", 503);
            }
            
            $encodedToken = getJWTFromRequest($authenticationHeader);
            validateJWTFromRequest($encodedToken);
            return $request;
        } catch (Exception $ex) {
            return Services::response()
                ->setJSON(
                    [
                        "status" => $ex->getCode(),
                        "error" => $ex->getCode(),
                        "messages" => [
                            "error" => $ex->getMessage()
                        ]
                    ]
                )
                ->setStatusCode($ex->getCode()!=0 ? $ex->getCode() : ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Allows After filters to inspect and modify the response object as needed. This method does not allow 
     * any way to stop execution of other after filters, short of throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 
    }
}

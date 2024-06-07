<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Auth extends BaseController
{
    // use ResponseTrait;
    /**
     * Register a new user
     * @return Response
     * @throws ReflectionException
     */
    use ResponseTrait;
    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login()
    {
        $input = $this->request->getJSON();

        return $this->getJWTForUser($input->username);
    }

    private function getJWTForUser(string $emailAddress, int $responseCode = ResponseInterface::HTTP_OK) 
    {
        try {
            $model = new UserModel();
            $user = ['nama'=>'Deni Malik', 'email'=>'deni@mail.com'];

            helper('jwt');

            return $this->respond(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        'access_token' => getSignedJWTForUser($user)
                    ]
                );
        } catch (Exception $ex) {
            return $this->respond(
                    [
                        'error' => $ex->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}
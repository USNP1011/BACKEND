<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Validation\ValidationRules;

class AuthController extends ResourceController
{
    public function register()
    {
        try {
            $rules = [
                "username" => "required",
                "email" => "required|valid_email|is_unique[auth_identities.secret]",
                "password" => "required"
            ];
            if (!$this->validate($rules)) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            } else {
                $userObject = auth()->getProvider();
                $userEntityObject = new User();
                $userEntityObject->fill((array)$this->request->getJSON());
                $userObject->save($userEntityObject);
                $itemData = $userObject->findById($userObject->getInsertID());
                $userObject->addToDefaultGroup($itemData);
                $result = [
                    "status" => true,
                    "message" => "User saved successfully",
                    "data" => []
                ];
                return $this->respond($result);
            }
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    function login(): ResponseInterface
    {
        helper('jwt');
        $itemRules = [
            'username' => 'required',
            'password' => 'required'
        ];
        $rules = $this->getValidationRules();
        if (!$this->validateData($this->request->getJSON(true), $itemRules, [], config('Auth')->DBGroup)) {
            return $this->fail(
                ['errors' => $this->validator->getErrors()],
                $this->codes['unauthorized']
            );
        }

        $credentials             = $this->request->getJsonVar(setting('Auth.validFields'));
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getJsonVar('password');
        $authenticator = auth('session')->getAuthenticator();

        $result = $authenticator->check($credentials);
        if (!$result->isOK()) {
            // @TODO Record a failed login attempt

            return $this->failUnauthorized($result->reason());
        }

        $user = $result->extraInfo();
        $userobject = new UserModel();

        $item = $userobject->findById($user->id);
        $set = [
            'uid'=>$item->id,
            'username'=>$item->username,
            'email'=>$item->email,
            'status'=>$item->status,
        ];

        return $this->respond([
            'message' => 'User authenticated successfully',
            'user' => $set,
            'access_token' => getSignedJWTForUser($set)
        ]);
    }

    protected function getValidationRules(): array
    {
        $rules = new ValidationRules();

        return $rules->getLoginRules();
    }
}

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
                $role = new \App\Models\UserRoleModel();
                $userObject = auth()->getProvider();
                $userEntityObject = new User();
                $userEntityObject->fill((array)$this->request->getJSON());
                $userObject->save($userEntityObject);
                $itemData = $userObject->findById($userObject->getInsertID());
                $userObject->addToDefaultGroup($itemData);
                $itemRole = [
                    'users_id' => $itemData->id,
                    'role_id' => '1'
                ];
                $role->insert($itemRole);
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
        try {
            helper('jwt');
            $itemRules = [
                'username' => 'required',
                'password' => 'required'
            ];
            if (!$this->validateData($this->request->getJSON(true), $itemRules, [], null)) {
                return $this->fail($this->validator->getErrors());
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
            if ($item->active == 0) {
                return $this->fail("Akun anda belum aktif");
            }
            $role = new \App\Models\UserRoleModel();
            $semester = new \App\Models\SemesterModel();

            $set = [
                'uid' => $item->id,
                'username' => $item->username,
                'email' => $item->email,
                'status' => $item->status,
                'semester' => $semester->select('id_semester, nama_semester, batas_pengisian_krsm')->where('a_periode_aktif', '1')->first(),
                'roles' => $role->select("role.role")->join('role', 'role.id=user_role.role_id', 'left')->where('user_role.users_id', $item->id)->findAll()
            ];

            if(!checkMahasiswa($set['roles'])){
                $set['status'] = getDosenByUser($item->id)->status;
            }

            return $this->respond([
                'message' => 'User authenticated successfully',
                'user' => $set,
                'access_token' => getSignedJWTForUser($set)
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    protected function getValidationRules(): array
    {
        $rules = new ValidationRules();

        return $rules->getLoginRules();
    }

    function resetPassword(): ResponseInterface
    {
        $request = $this->request->getJSON();
        if ($request->role == "Dosen") {
            $dsn = new \App\Models\DosenModel();
            $itemDosen = $dsn->where('id_user', $request->id)->first();
            $newPassword = $itemDosen->nidn;
        } else if ($request->role == "Mahasiswa") {
            $mhs = new \App\Models\MahasiswaModel();
            $itemMahasiswa = $mhs->select("nim")->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id')->where('id_user', $request->id)->first();
            $newPassword = $itemMahasiswa->nim;
        } else {
            return $this->fail("Role tidak ditemukan");
        }
        $users = auth()->getProvider();
        $user = $users->findById($request->id);
        $user->activate();
        $user->fill(['password' => $newPassword]);
        $cek = $users->save($user);
        return $this->respond([
            'status' => true
        ]);
    }

    function changePassword(): ResponseInterface
    {
        $credentials             = $this->request->getJsonVar(setting('Auth.validFields'));
        $credentials             = array_filter($credentials);
        $credentials['password'] = $this->request->getJsonVar('oldPassword');
        $authenticator = auth('session')->getAuthenticator();

        $result = $authenticator->check($credentials);
        if (!$result->isOK()) {
            return $this->failUnauthorized("Password lama yang anda masukkan tidak sesuai");
        }
        $users = auth()->getProvider();
        // $user = $users->findById($this->request->getJsonVar('id'));
        $user = $users->findByCredentials(['username'=>$credentials['username']]);
        $user->activate();
        $user->fill(['password' => $this->request->getJsonVar('newPassword')]);
        $cek = $users->save($user);
        return $this->respond([
            'status' => true
        ]);
       
    }

    function createUser(): ResponseInterface
    {
        $conn = \Config\Database::connect();
        $request = $this->request->getJSON();
        try {
            $conn->transException(true)->transStart();
            if ($request->role == "Dosen") {
                $dsn = new \App\Models\DosenModel();
                $itemDosen = $dsn->where('id_dosen', $request->id)->first();
                $itemUser = [
                    'username' => $itemDosen->nidn,
                    'email' => $itemDosen->email ?? ($itemDosen->nidn."@usn-papua.ac.id"),
                    'password' => $itemDosen->nidn,
                ];
            } else if ($request->role == "Mahasiswa") {
                $mhs = new \App\Models\MahasiswaModel();
                $itemMahasiswa = $mhs->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim")->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')->where('mahasiswa.id', $request->id)->first();
                $itemUser = [
                    'username' => $itemMahasiswa->nim,
                    'email' => $itemMahasiswa->email ?? $itemMahasiswa->nim."@usn-papua.ac.id",
                    'password' => $itemMahasiswa->nim,
                ];
            } else {
                return $this->fail("Role tidak ditemukan");
            }

            $rules = [
                "username" => "required",
                "email" => "required|valid_email|is_unique[auth_identities.secret]",
                "password" => "required"
            ];
            if (!$this->validateData($itemUser, $rules)) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $role = new \App\Models\UserRoleModel();
            $userObject = auth()->getProvider();
            $userEntityObject = new User();
            $userEntityObject->fill($itemUser);
            $userObject->save($userEntityObject);
            $itemData = $userObject->findById($userObject->getInsertID());
            $userObject->addToDefaultGroup($itemData);
            $itemRole = [
                'users_id' => $itemData->id,
                'role_id' => $request->role == "Dosen" ? 2 : 4
            ];
            $itemData->forcePasswordReset();
            $itemData->activate();
            $role->insert($itemRole);
            $this->updateUser($request->id, $request->role, $itemData->id);
            $conn->transComplete();
            return $this->respond([
                "status" => true,
                "message" => "User created successfully"
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
            // return $this->fail(handleErrorDB($th->getCode()));
        }
    }

    function updateUser($id, $role, $id_user): bool
    {
        if ($role == 'Dosen') {
            $object = new \App\Models\DosenModel();
            $object->update($id, ['id_user' => $id_user]);
            return true;
        } else if ($role == 'Mahasiswa') {
            $conn = \Config\Database::connect();
            $conn->query("UPDATE mahasiswa SET id_user='".$id_user."', sync_at=updated_at WHERE id='".$id."'");
            // $object = new \App\Models\MahasiswaModel();
            // $object->set("id_user='".$id_user."', sync_at=updated_at")->update($id);
            return true;
        }
    }
}

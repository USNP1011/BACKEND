<?php

namespace App\Controllers\Api;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Models\MahasiswaModel;
use App\Models\UserRoleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Shield\Entities\User;

class Mahasiswa extends ResourceController
{
    public function show($id = null)
    {
        $mahasiswa = new MahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $mahasiswa
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->findAll() : $mahasiswa
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->where('mahasiswa.id', $id)->first()
        ]);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $item = $this->request->getJSON();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $itemUser = [
                'username' => $item->email,
                'email' => $item->email,
                'password' => 'Usn1011!'
            ];
            if (!$this->validateData($itemUser, 'userMahasiswa')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            if (!$this->validate('mahasiswa')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $userObject = auth()->getProvider();
            $userEntityObject = new User();
            $userEntityObject->fill($itemUser);
            $userObject->save($userEntityObject);
            $itemData = $userObject->findById($userObject->getInsertID());
            $item->id_user = $userObject->getInsertID();
            $userObject->addToDefaultGroup($itemData);

            $role = [
                'users_id'=> $item->id_user,
                'role_id'=> '6'
            ];
            $userRole = new UserRoleModel();
            $userRole->insert($role);

            $item->id = Uuid::uuid4()->toString();
            $object = new MahasiswaModel();
            $model = new EntitiesMahasiswa();
            $model->fill((array) $item);
            $object->insert($item);
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $item
            ]);
        } catch (DatabaseException $th) {
            if ($th->getCode() == 1062) {
            }
            return $this->failValidationErrors([
                'status' => false,
                'message' => "Mahasiswa dengan nama, tempat, tanggal lahir dan ibu kandung yang sama sudah ada",
            ]);
        }
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        try {
            $item = $this->request->getJSON();
            $object = new MahasiswaModel();
            $model = new EntitiesMahasiswa();
            $model->fill((array) $item);
            $object->save($item);
            return $this->respondUpdated([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        try {
            $object = new MahasiswaModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
                'data' => []
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function paginate($page = 1, $count = 10, $cari = null)
    {
        $item = $this->request->getJSON();
        $object = model(MahasiswaModel::class);
        $item = [
            'status' => true,
            'data' => $object->like('nama_mahasiswa', $item->cari)->paginate($item->count, 'default', $item->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

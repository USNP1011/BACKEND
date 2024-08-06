<?php

namespace App\Controllers\Api;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Models\MahasiswaModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use App\Models\UserRoleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use Ramsey\Uuid\Uuid;

class RiwayatPendidikanMahasiswa extends ResourceController
{

    public function show($id = null)
    {
        $riwayat = new RiwayatPendidikanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $riwayat->findAll() : $riwayat->where('id', $id)->first()
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
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $item = $this->request->getJSON();
            $itemUser = [
                'username' => $item->nim,
                'email' => $item->nim.'@usn-papua.ac.id',
                'password' => $item->nim,
            ];
            if (!$this->validateData($itemUser, 'userMahasiswa')) {
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
            $itemData->forcePasswordReset();
            $itemData->activate();

            $mhs = new \App\Models\MahasiswaModel();
            $mhs->update($item->id_mahasiswa, ['id_user'=>$item->id_user]);

            $role = [
                'users_id' => $item->id_user,
                'role_id' => '4'
            ];
            $userRole = new UserRoleModel();
            $userRole->insert($role);

            $item->id = Uuid::uuid4()->toString();
            $item->angkatan = substr($item->nim,0,4);
            $object = new \App\Models\RiwayatPendidikanMahasiswaModel();
            $model = new \App\Entities\RiwayatPendidikanMahasiswa();
            $model->fill((array)$item);
            $object->insert($model);

            // $perkuliahan = new \App\Models\PerkuliahanMahasiswaModel();
            // $modelPerkuliahan = new \App\Entities\AktivitasKuliahEntity();
            // $semester = getSemesterAktif();
            // $itemKuliah = [
            //     'id'=> Uuid::uuid4()->toString(),
            //     'id_riwayat_pendidikan'=>$item->id,
            //     'id_mahasiswa'=>$item->id_mahasiswa,
            //     'id_semester'=>$semester->id_semester,
            //     'nama_semester'=>$semester->nama_semester,
            //     'nim'=>$item->nim,
            //     'id_prodi'=>$item->id_prodi,
            //     'id_status_mahasiswa'=>"N",

            // ];
            // $modelPerkuliahan->fill($itemKuliah);
            // $perkuliahan->insert($modelPerkuliahan);

            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $model
            ]); 
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
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
            $object = new \App\Models\RiwayatPendidikanMahasiswaModel();
            $model = new \App\Entities\RiwayatPendidikanMahasiswa();
            $model->fill((array)$this->request->getJSON());
            $object->save($model);
            return $this->respond([
                'status' => true,
                'data' => $model
            ]); 
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
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
            $object = new \App\Models\RiwayatPendidikanMahasiswaModel();
            $object->delete($id);
            return $this->respondDeleted([
                'status' => true,
                'message' => 'successful deleted',
                'data'=>[]
            ]); 
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}

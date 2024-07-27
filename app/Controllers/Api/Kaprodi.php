<?php

namespace App\Controllers\Api;

use App\Models\KaprodiModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Kaprodi extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }
    /**
     * @param null $id
     * 
     * @return object
     */
    public function store($id = null): object
    {
        $object = new KaprodiModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object
                ->select("kaprodi.*, dosen.nama_dosen, prodi.nama_program_studi")
                ->join('dosen', 'dosen.id_dosen=kaprodi.id_dosen', 'left')
                ->join('prodi', 'prodi.id_prodi=kaprodi.id_prodi', 'left')
                ->orderBy('status', 'desc')
                ->findAll() :
                $object
                ->select("kaprodi.*, dosen.nama_dosen, prodi.nama_program_studi")
                ->join('dosen', 'dosen.id_dosen=kaprodi.id_dosen', 'left')
                ->join('prodi', 'prodi.id_prodi=kaprodi.id_prodi', 'left')
                ->where('kaprodi.id', $id)->first()
        ]);
    }

    public function create()
    {
        $object = new KaprodiModel();
        $param = $this->request->getJSON();
        $conn = \Config\Database::connect();
        $role = new \App\Models\UserRoleModel();
        $dosen = new \App\Models\DosenModel();
        try {
            $conn->transException(true)->transStart();
            $param->id = Uuid::uuid4()->toString();
            $param->status = '1';
            // Get Kaprodi Lama dan set tidak aktif
            $itemOldKaprodi = $object->where('id_prodi', $param->id_prodi)->where('status', '1')->first();
            if (!is_null($itemOldKaprodi)) {
                $object->set('status', '1')->update();
                // Get Data Dosen berdasarkan Kaprodi Lama untuk menghapus userRole
                $itemOldDosen = $dosen->where('id_dosen', $itemOldKaprodi->id_dosen)->first();
                if (!is_null($itemOldDosen))
                    $role->where('role_id', '3')->where('users_id', $itemOldDosen->id_user)->delete();
                // End
            }
            // End
            // Tambahkan role prodi untuk kaprodi baru
            $itemDosen = $dosen->where('id_dosen', $param->id_dosen)->first();
            $itemRole = [
                'role_id' => '3',
                'users_id' => $itemDosen->id_user
            ];
            // End
            $role->insert($itemRole);
            $model = new \App\Entities\KaprodiEntity();
            $model->fill((array)$param);
            $object->insert($model);
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $param
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function update($id = null)
    {
        $object = new KaprodiModel();
        $param = $this->request->getJSON();
        try {
            $model = new \App\Entities\KaprodiEntity();
            $model->fill((array)$param);
            $object->save($model);
            return $this->respond([
                'status' => true,
                'data' => true
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

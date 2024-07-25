<?php

namespace App\Controllers\Api\Referensi;

use App\Models\KaprodiModel;
use App\Models\ProdiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Prodi extends ResourceController
{
    public function store()
    {
        $object = new ProdiModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("prodi.*, (SELECT nama_dosen FROM kaprodi LEFT JOIN dosen on dosen.id_dosen=kaprodi.id_dosen WHERE kaprodi.id_prodi=prodi.id_prodi AND kaprodi.status='1') as nama_dosen, (SELECT kaprodi.id FROM kaprodi LEFT JOIN dosen on dosen.id_dosen=kaprodi.id_dosen WHERE kaprodi.id_prodi=prodi.id_prodi AND kaprodi.status='1') as kaprodi_id")->findAll()
        ]);
    }

    public function kaprodi($id=null) {
        $object = new KaprodiModel();
        try {
            return $this->respond([
                'status'=>true,
                'data'=>$object->select('kaprodi.*, dosen.nama_dosen')
                ->join('dosen', 'dosen.id_dosen=kaprodi.id_dosen', 'left')
                ->where('kaprodi.id', $id)->first()
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function createKaprodi() {
        $object = new KaprodiModel();
        $param = $this->request->getJSON();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $param->id = Uuid::uuid4()->toString();
            $param->status = '1';
            // Get Kaprodi Lama dan set tidak aktif
            $itemOldKaprodi = $object->where('id_prodi', $param->id_prodi)->where('status', '1')->first();
            $object->set('status', '1')->update();
            // End
            $role = new \App\Models\UserRoleModel();
            $dosen = new \App\Models\DosenModel();
            // Get Data Dosen berdasarkan Kaprodi Lama untuk menghapus userRole
            $itemOldDosen = $dosen->where('id_dosen', $itemOldKaprodi->id_dosen)->first();
            $role->where('role_id', '3')->where('users_id', $itemOldDosen->id_user)->delete();
            // End
            // Tambahkan role prodi untuk kaprodi baru
            $itemDosen = $dosen->where('id_dosen', $param->id_dosen)->first();
            $itemRole = [
                'role_id'=>'3',
                'users_id'=>$itemDosen->id_user
            ];
            // End
            $role->insert($itemRole);
            $model = new \App\Entities\KaprodiEntity();
            $model->fill((array)$param);
            $object->insert($model);
            $conn->transComplete();
            return $this->respond([
                'status'=>true,
                'data'=>$param
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function UpdateKaprodi() {
        $object = new KaprodiModel();
        $param = $this->request->getJSON();
        try {
            $model = new \App\Entities\KaprodiEntity();
            $model->fill((array)$param);
            $object->save($model);
            return $this->respond([
                'status'=>true,
                'data'=>true
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

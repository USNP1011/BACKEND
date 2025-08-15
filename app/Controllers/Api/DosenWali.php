<?php

namespace App\Controllers\Api;

use App\Models\DosenWaliModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class DosenWali extends ResourceController
{

    public function show($id = null)
    {
        $object = new DosenWaliModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("dosen_wali.*, riwayat_pendidikan_mahasiswa.nim, mahasiswa.nama_mahasiswa, prodi.nama_program_studi, prodi.kode_program_studi, riwayat_pendidikan_mahasiswa.angkatan")
            ->join("riwayat_pendidikan_mahasiswa", "riwayat_pendidikan_mahasiswa.id=dosen_wali.id_riwayat_pendidikan", "LEFT")
            ->join("mahasiswa", "riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id", "LEFT")
            ->join("mahasiswa_lulus_do", "riwayat_pendidikan_mahasiswa.id=mahasiswa_lulus_do.id_riwayat_pendidikan", "LEFT")
            ->join("prodi", "prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi", "LEFT")
            ->where('dosen_wali.id_dosen', $id)
            ->where('mahasiswa_lulus_do.id_riwayat_pendidikan IS NULL')
            ->orderBy('dosen_wali.created_at', 'desc')
            ->findAll()
        ]);
    }

    public function showByDsn($id = null)
    {
        $object = new DosenWaliModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_dosen', $id)->findAll()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('dosenWali')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\DosenWaliModel();
            $model = new \App\Entities\DosenWaliEntity();
            $model->fill((array)$item);
            $object->insert($model);
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


    public function update($id = null)
    {
        $data = $this->request->getJSON();
        try {
            $object = new \App\Models\DosenWaliModel();
            $dataUpdate = [];
            foreach ($data as $key => $value) {
                $model = new \App\Entities\DosenWaliEntity();
                $model->fill((array)$value);
                $dataUpdate[] = $model;
            }
            $object->updateBatch($dataUpdate, "id");
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

    public function delete($id = null)
    {
        try {
            $object = new \App\Models\DosenWaliModel();
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
}

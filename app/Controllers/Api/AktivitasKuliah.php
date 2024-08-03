<?php

namespace App\Controllers\Api;

use App\Models\PerkuliahanMahasiswaModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class AktivitasKuliah extends ResourceController
{

    public function show($id = null)
    {
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => is_null($id) ? $object->findAll() : $object->select("perkuliahan_mahasiswa.*, mahasiswa.nama_mahasiswa")
            ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
            ->join('mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
            ->where('perkuliahan_mahasiswa.id', $id)->first()
        ]);
    }

    public function showByMhs($id = null)
    {
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("perkuliahan_mahasiswa.*")
            ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
            ->where('id_mahasiswa', $id)->findAll()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('aktivitasKuliah')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $model = new \App\Entities\AktivitasKuliahEntity();
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
        try {
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $model = new \App\Entities\AktivitasKuliahEntity();
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


    public function delete($id = null)
    {
        try {
            $object = new \App\Models\PerkuliahanMahasiswaModel();
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
        $param = $this->request->getJSON();
        $object = new PerkuliahanMahasiswaModel();
        $item = [
            'status' => true,
            'data' => $object->select("perkuliahan_mahasiswa.*, mahasiswa.nama_mahasiswa, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.id_mahasiswa as mahasiswa_id")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
                ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                ->groupStart()
                ->like('mahasiswa.nama_mahasiswa', $param->cari)
                ->orLike('riwayat_pendidikan_mahasiswa.nim', $param->cari)
                ->groupEnd()
                ->orderBy(isset($param->order) && $param->order != "" ? $param->order->field : 'perkuliahan_mahasiswa.created_at', isset($param->order) && $param->order != "" ? $param->order->direction : 'desc')
                ->paginate($param->count, 'default', $param->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

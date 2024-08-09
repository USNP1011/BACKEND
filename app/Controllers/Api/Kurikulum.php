<?php

namespace App\Controllers\Api;

use App\Models\KurikulumModel;
use App\Models\MatakuliahKurikulumModel;
use App\Models\MatakuliahModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Kurikulum extends ResourceController
{

    public function show($id = null, $id_prodi = null)
    {
        try {
            $object = new KurikulumModel();
            return $this->respond([
                'status' => true,
                'data' => $id == null ? $object->findAll() : $object->where('id', $id)->first()
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function matakuliah_kurikulum($id = null)
    {
        try {
            $object = new MatakuliahKurikulumModel();
            return $this->respond([
                'status' => true,
                'data' => $object
                ->select("matakuliah_kurikulum.id, matakuliah_kurikulum.semester, matakuliah_kurikulum.matakuliah_id, matakuliah_kurikulum.status_sync, matakuliah_kurikulum.sync_at, matakuliah_kurikulum.apakah_wajib, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, matakuliah.sks_tatap_muka, matakuliah.sks_praktek, matakuliah.sks_praktek_lapangan, matakuliah.sks_simulasi")
                ->join("matakuliah", "matakuliah.id = matakuliah_kurikulum.matakuliah_id", "left")
                ->orderBy('matakuliah_kurikulum.created_at', 'desc')
                ->where('kurikulum_id', $id)->findAll()
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage(),400);
        }
    }

    public function matakuliah_prodi($id = null)
    {
        $object = new MatakuliahKurikulumModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select('matakuliah.*')
            ->join('matakuliah', 'matakuliah.id=matakuliah_kurikulum.mahasiswa_id', 'left')
            ->where('matakuliah.id_prodi', $id)->findAll()
        ]);
    }


    public function create()
    {
        try {
            $item = $this->request->getJSON();
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\KurikulumModel();
            $model = new \App\Entities\KurikulumEntity();
            $model->fill((array)$item);
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

    public function create_matakuliah()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('matakuliahKurikulum')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\MatakuliahKurikulumModel();
            $model = new \App\Entities\MatakuliahKurikulumEntity();
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
            $object = new \App\Models\KurikulumModel();
            $model = new \App\Entities\KelasKuliahEntity();
            $model->fill((array)$this->request->getJSON());
            $object->save($model);
            return $this->respond([
                'status' => true,
                'data' => true
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function update_matakuliah($id = null)
    {
        try {
            $object = new \App\Models\MatakuliahKurikulumModel();
            $model = new \App\Entities\MatakuliahKurikulumEntity();
            $item = [
                'id'=>$this->request->getJsonVar('id'),
                'semester'=>$this->request->getJsonVar('semester'),
                'apakah_wajib'=>$this->request->getJsonVar('apakah_wajib')
            ];
            $model->fill((array)$item);
            $object->save($model);
            return $this->respond([
                'status' => true,
                'data' => true
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
            $object = new \App\Models\KurikulumModel();
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

    public function delete_matakuliah($id = null)
    {
        try {
            $object = new \App\Models\MatakuliahKurikulumModel();
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

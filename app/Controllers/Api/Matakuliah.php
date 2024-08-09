<?php

namespace App\Controllers\Api;

use App\Entities\MatakuliahEntity;
use App\Models\MatakuliahModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Matakuliah extends ResourceController
{
    public function show($id = null)
    {
        $matakuliah = new MatakuliahModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $matakuliah->select("matakuliah.*, prodi.nama_program_studi")
            ->join("prodi", "matakuliah.id_prodi=prodi.id_prodi", "left")
            ->findAll() : $matakuliah->select("matakuliah.*, prodi.nama_program_studi")
            ->join("prodi", "matakuliah.id_prodi=prodi.id_prodi", "left")
            ->where('id', $id)->first()
        ]);
    }

    public function by_prodi($id = null)
    {
        $object = new MatakuliahModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_prodi', $id)->findAll()
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
        $item->id = Uuid::uuid4()->toString();
        try {
            if (!$this->validate('matakuliah')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $object = new MatakuliahModel();
            $model = new MatakuliahEntity();
            $model->fill((array) $item);
            $object->insert($model);
            return $this->respond([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->failValidationErrors([
                'status' => false,
                'message' => $th->getMessage(),
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
            $object = new MatakuliahModel();
            $model = new MatakuliahEntity();
            $model->fill((array) $item);
            $object->save($model);
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
            $object = new MatakuliahModel();
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
        $object = model(MatakuliahModel::class);
        $item = [
            'status' => true,
            'data' => $object->select('matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, matakuliah.id_jenis_mata_kuliah, prodi.nama_program_studi, matakuliah.status_sync, matakuliah.sync_at')
            ->join('prodi', 'prodi.id_prodi=matakuliah.id_prodi')
            ->like('nama_mata_kuliah', $param->cari)
            ->orderBy(isset($param->order) && $param->order->field != "" ? $param->order->field : 'prodi.nama_program_studi', isset($param->order) && $param->order->field != "" ? $param->order->direction : 'desc')
            ->paginate($param->count, 'default', $param->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

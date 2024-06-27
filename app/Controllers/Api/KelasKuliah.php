<?php

namespace App\Controllers\Api;

use App\Models\KelasKuliahModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class KelasKuliah extends ResourceController
{
    public function __construct() {
        helper('semester');
    }
    /**
     * @param null $id
     * 
     * @return object
     */
    public function show($id = null):object
    {
        $semester = getSemesterAktif();
        $object = new KelasKuliahModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object->findAll() : $object->where('id', $id)->first()
        ]);
    }


    public function create()
    {
        try {
            $item = $this->request->getJSON();
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\KelasKuliahModel();
            $model = new \App\Entities\KelasKuliahEntity();
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
            $object = new \App\Models\KelasKuliahModel();
            $model = new \App\Entities\KelasKuliahEntity();
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
            $object = new \App\Models\KelasKuliahModel();
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

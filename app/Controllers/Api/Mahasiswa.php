<?php

namespace App\Controllers\Api;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Models\MahasiswaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Mahasiswa extends ResourceController
{

    public function show($id = null)
    {
        $mahasiswa = new MahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $mahasiswa->findAll() : $mahasiswa->where('id', $id)->first()
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
        try {
            $item = $this->request->getJSON();
            $item->id = Uuid::uuid4()->toString();
            $object = new MahasiswaModel();
            $object->save($item);
            return $this->respond([
                'status' => true,
                'data' => $item
            ]); 
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => $item
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
        //
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
        //
    }
}

<?php

namespace App\Controllers\Api\Referensi;

use App\Entities\PerguruanTinggi as EntitiesPerguruanTinggi;
use App\Models\PerguruanTinggiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PerguruanTinggi extends ResourceController
{
    public function store()
    {
        $item = new PerguruanTinggiModel();
        // $pt = new EntitiesPerguruanTinggi();
        $data = $item->first();
        return $this->respond([
            'status' => true,
            'data' => $data
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
        //
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

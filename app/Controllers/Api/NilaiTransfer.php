<?php

namespace App\Controllers\Api;

use App\Models\NilaiTransferModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class NilaiTransfer extends ResourceController
{

    public function show($id = null)
    {
        $object = new RiwayatPendidikanMahasiswaModel();
        $mahasiswa = $object->select("riwayat_pendidikan_mahasiswa.id as id_riwayat_pendidikan, id_registrasi_mahasiswa, prodi.id_prodi, prodi.nama_program_studi, nim, nama_mahasiswa, riwayat_pendidikan_mahasiswa.id_jenis_daftar, jenis_pendaftaran.nama_jenis_daftar")
            ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
            ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
            ->join('jenis_pendaftaran', 'jenis_pendaftaran.id_jenis_daftar=riwayat_pendidikan_mahasiswa.id_jenis_daftar', 'left')
            ->where('riwayat_pendidikan_mahasiswa.id_mahasiswa', $id)->first();
        $object = new NilaiTransferModel();
        $mahasiswa->matakuliah = $object->where('id_riwayat_pendidikan', $mahasiswa->id_riwayat_pendidikan)->findAll();
        return $this->respond([
            'status' => true,
            'data' => $mahasiswa
        ]);
        // $object = new NilaiTransferModel();
        // return $this->respond([
        //     'status' => true,
        //     'data' => $id == null ? $object->findAll() : $object->where('id', $id)->first()
        // ]);
    }

    public function showByMhs($id = null) {}

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
            $object = new \App\Models\NilaiTransferModel();
            $model = new \App\Entities\NilaiTransferEntity();
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
            $object = new \App\Models\NilaiTransferModel();
            $model = new \App\Entities\NilaiTransferEntity();
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
            $object = new \App\Models\NilaiTransferModel();
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

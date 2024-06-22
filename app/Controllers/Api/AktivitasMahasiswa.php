<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class AktivitasMahasiswa extends ResourceController
{
    protected $aktivitasMahasiswa;
    protected $semester;
    public function __construct() {
        helper('semester');
        $this->aktivitasMahasiswa = new \App\Models\AktivitasMahasiswaModel();
        $this->semester = getSemesterAktif();
    }

    public function show($id = null)
    {
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $this->aktivitasMahasiswa->where('id_semester', $this->semester->id_semester)->findAll() : $this->aktivitasMahasiswa->where('id', $id)->first()
        ]);
    }

    // public function showByMhs($id = null)
    // {
    //     $object = new PerkuliahanMahasiswaModel();
    //     return $this->respond([
    //         'status' => true,
    //         'data' => $object->where('id_riwayat_pendidikan', $id)->findAll()
    //     ]);
    // }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            $item->id = null;
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $model = new \App\Entities\AktivitasKuliahEntity();
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

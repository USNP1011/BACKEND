<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class AktivitasMahasiswa extends ResourceController
{
    protected $aktivitasMahasiswa;
    protected $semester;
    public function __construct()
    {
        helper('semester');
        $this->aktivitasMahasiswa = new \App\Models\AktivitasMahasiswaModel();
        $this->semester = getSemesterAktif();
    }

    public function show($id = null)
    {
        return $this->respond([
            'status' => true,
            'data' => $id == null ?
                $this->aktivitasMahasiswa
                ->select("aktivitas_mahasiswa.*, jenis_aktivitas.nama_jenis_aktivitas_mahasiswa, prodi.nama_program_studi, semester.nama_semester")
                ->join('jenis_aktivitas', 'jenis_aktivitas.id_jenis_aktivitas_mahasiswa= aktivitas_mahasiswa.id_jenis_aktivitas_mahasiswa', 'left')
                ->join('prodi', 'prodi.id_prodi= aktivitas_mahasiswa.id_prodi', 'left')
                ->join('semester', 'semester.id_semester= aktivitas_mahasiswa.id_semester', 'left')
                ->where('aktivitas_mahasiswa.id_semester', $this->semester->id_semester)->findAll() :
                $this->aktivitasMahasiswa
                ->select("aktivitas_mahasiswa.*, jenis_aktivitas.nama_jenis_aktivitas_mahasiswa, prodi.nama_program_studi, semester.nama_semester")
                ->join('jenis_aktivitas', 'jenis_aktivitas.id_jenis_aktivitas_mahasiswa= aktivitas_mahasiswa.id_jenis_aktivitas_mahasiswa', 'left')
                ->join('prodi', 'prodi.id_prodi= aktivitas_mahasiswa.id_prodi', 'left')
                ->join('semester', 'semester.id_semester= aktivitas_mahasiswa.id_semester', 'left')
                ->where('id', $id)->first()
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
}

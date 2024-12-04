<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class NilaiPesertaKelas extends ResourceController
{
    /**
     * @param null $id
     * 
     * @return object
     */
    public function kelas($id = null):object{
        $semester = new \App\Models\SemesterModel();
        $semesterAktif = $semester->where('a_periode_aktif', '1')->first();
        $kelas = new \App\Models\KelasKuliahModel();
        $dataKelas = $kelas
        ->select('kelas_kuliah.*, matakuliah.nama_mata_kuliah, matakuliah.kode_mata_kuliah')
        ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
        ->like('matakuliah.nama_mata_kuliah', $id)
        ->where('id_semester', $semesterAktif->id_semester)
        ->findAll(5);
        return $this->respond([
            'status' => true,
            'data' => $dataKelas
        ]); 
    }

    public function skala($id = null):object{
        $skala = new \App\Models\SkalaNilaiModel();
        $dataSkala = $skala->where('id_prodi', $id)->findAll();
        return $this->respond([
            'status' => true,
            'data' => $dataSkala
        ]); 
    }

    public function show($id = null):object 
    {
        $object = new \App\Models\PesertaKelasModel();
        $kelas = new \App\Models\KelasKuliahModel();
        $dataPeserta = $object
        ->select('nilai_kelas.*,peserta_kelas.id as peserta_kelas_id, peserta_kelas.id_riwayat_pendidikan, peserta_kelas.kelas_kuliah_id, riwayat_pendidikan_mahasiswa.nim, mahasiswa.nama_mahasiswa')
        ->join('nilai_kelas', 'nilai_kelas.peserta_kelas_id = peserta_kelas.id', 'left')->where('kelas_kuliah_id', $id)
        ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
        ->join('mahasiswa', 'mahasiswa.id = riwayat_pendidikan_mahasiswa.id_mahasiswa','left')
        ->findAll();
        return $this->respond([
            'status' => true,
            'data' => $dataPeserta
        ]);
    }
    

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('nilaiKelas')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id_nilai_kelas = Uuid::uuid4()->toString();
            $object = new \App\Models\NilaiPesertaKelasModel();
            $model = new \App\Entities\NilaiKelasEntity();
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
            $object = new \App\Models\PesertaKelasModel();
            $model = new \App\Entities\PesertaKelasEntity();
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
            $object = new \App\Models\PesertaKelasModel();
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

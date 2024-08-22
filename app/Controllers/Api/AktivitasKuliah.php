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
            'data' => is_null($id) ? $object->findAll() : $object->select("perkuliahan_mahasiswa.*, mahasiswa.nama_mahasiswa, pembiayaan.nama_pembiayaan, semester.nama_semester")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
                ->join('mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->join('pembiayaan', 'pembiayaan.id_pembiayaan=perkuliahan_mahasiswa.id_pembiayaan', 'left')
                ->join('semester', 'semester.id_semester=perkuliahan_mahasiswa.id_semester', 'left')
                ->where('perkuliahan_mahasiswa.id', $id)->first()
        ]);
    }

    public function showByMhs($id = null)
    {
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("perkuliahan_mahasiswa.*, semester.nama_semester, status_mahasiswa.nama_status_mahasiswa")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
                ->join('semester', 'semester.id_semester=perkuliahan_mahasiswa.id_semester', 'left')
                ->join('status_mahasiswa', 'status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa', 'left')
                ->orderBy('id_semester', 'asc')
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
        try {
            $param = $this->request->getJSON();
            $semester = getSemesterAktif();
            $object = new PerkuliahanMahasiswaModel();
            $item = [
                'status' => true,
                'data' => $object->select("perkuliahan_mahasiswa.*, mahasiswa.nama_mahasiswa, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.id_mahasiswa as mahasiswa_id, prodi.nama_program_studi, semester.nama_semester, status_mahasiswa.nama_status_mahasiswa, nama_pembiayaan")
                    ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
                    ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                    ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
                    ->join('semester', 'semester.id_semester=perkuliahan_mahasiswa.id_semester', 'left')
                    ->join('status_mahasiswa', 'status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa', 'left')
                    ->join('pembiayaan', 'pembiayaan.id_pembiayaan=perkuliahan_mahasiswa.id_pembiayaan', 'left')
                    ->groupStart()
                    ->like('mahasiswa.nama_mahasiswa', $param->cari)
                    ->orLike('riwayat_pendidikan_mahasiswa.nim', $param->cari)
                    ->groupEnd()
                    ->where('perkuliahan_mahasiswa.id_semester', $semester->id_semester)
                    ->orderBy(isset($param->order) && $param->order->field != "" ? $param->order->field : 'perkuliahan_mahasiswa.updated_at', isset($param->order) && $param->order->direction != "" ? $param->order->direction : 'desc')
                    ->paginate($param->count, 'default', $param->page),
                'pager' => $object->pager->getDetails()
            ];
            return $this->respond($item);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

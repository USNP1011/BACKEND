<?php

namespace App\Controllers\Api\Referensi;

use App\Models\SemesterModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Semester extends ResourceController
{
    public function store($id=null)
    {
        $object = new SemesterModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object->findAll() : $object->where('id_semester', $id)->first()
        ]);
    }

    public function update($id = null)
    {
        $conn = \Config\Database::connect();
        $param = $this->request->getJSON();
        try {
            $conn->transBegin();
            $object = new \App\Models\SemesterModel();
            $model = new \App\Entities\Semester();
            $itemSemester = $object->where('id_semester', $param->id_semester)->first();
            if($itemSemester->status_kuliah=='0' || is_null($itemSemester->status_kuliah)){
                $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
                $dataRiwayat = $riwayat->select("riwayat_pendidikan_mahasiswa.id, (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar")->findAll();
                $tempMhs = [];
                foreach ($dataRiwayat as $key => $value) {
                    if(is_null($value->nama_jenis_keluar)){
                        $item = [
                            'id'=>Uuid::uuid4()->toString(),
                            'id_riwayat_pendidikan'=>$value->id,
                            'id_semester'=>$itemSemester->id_semester,
                            'id_status_mahasiswa'=>"N",
                            'ips'=>"0",
                            'ipk'=>"0",
                            'sks_total'=>"0",
                            'sks_semester'=>"0",
                        ];
                        $tempMhs[] = $item;
                    }
                }
                $kuliah = new \App\Models\PerkuliahanMahasiswaModel();
                $kuliah->insertBatch($tempMhs);
                $param->status_kuliah = "1";
            }
            $model->fill((array)$param);
            $object->set('a_periode_aktif', '0')->where('a_periode_aktif', '1')->update();
            $object->save($model);
            if($conn->transStatus()){
                $conn->transCommit();
                return $this->respond([
                    'status' => true,
                    'data' => $param
                ]); 
            }
            throw new \CodeIgniter\Database\Exceptions\DatabaseException();
        } catch (\Throwable $th) {
            $conn->transRollback();
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}

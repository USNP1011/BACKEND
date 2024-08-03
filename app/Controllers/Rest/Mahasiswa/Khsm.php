<?php

namespace App\Controllers\Rest\Mahasiswa;

use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Khsm extends ResourceController
{
    public function show($id = null, $id_mahasiswa=null)
    {
        try {
            if(is_null($id_mahasiswa)){
                $profile = getProfile();
                
            }else $profile = getProfileByMahasiswa($id_mahasiswa);
            $object = new \App\Models\PesertaKelasModel();
            return $this->respond([
                'status' => true,
                'data' => $object->select("peserta_kelas.*, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, (matakuliah.sks_mata_kuliah*peserta_kelas.nilai_indeks) as nxsks")
                    ->join('kelas_kuliah', 'kelas_kuliah.id=peserta_kelas.kelas_kuliah_id', 'left')
                    ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id')
                    ->where('kelas_kuliah.id_semester', $id)
                    ->where('peserta_kelas.id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->findAll()
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
            // return $this->fail(handleErrorDB($th->getCode()));
        }
    }

    function create()
    {
        $conn = \Config\Database::connect();
        $profile = getProfile();
        $object = new \App\Models\PerkuliahanMahasiswaModel();
        $sum = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->countAllResults();
        $itemKuliah = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1, $sum > 1 ? 1 : 0)->first();
        $skala = new \App\Models\SkalaSKSModel();
        $itemSkala = $skala->where("ips_min<='" . $itemKuliah->ips . "' AND ips_max>='" . $itemKuliah->ips . "'")->first();
        try {
            $conn->transException(true)->transStart();
            $temKrsm = new \App\Models\TempKrsmModel();
            $param = $this->request->getJSON();
            if (array_reduce($param, function ($carry, $product) {
                return $carry + $product->sks_mata_kuliah;
            }, 0) > $itemSkala->sks_max) {
                throw new \Exception('SKS yang dipilih melebihi ' . $itemSkala->sks_max . 'SKS', 1);
            }
            if ($temKrsm->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->countAllResults() == 0) {
                $krsm = [
                    'id' => Uuid::uuid4()->toString(),
                    'id_riwayat_pendidikan' => $profile->id_riwayat_pendidikan,
                    'id_tahapan' => '1',
                    'id_semester' => getSemesterAktif()->id_semester
                ];
                $temKrsm->insert($krsm);
            } else {
                $krsm = $temKrsm->asArray()->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->first();
            }
            $temPeserta = new \App\Models\TempPesertaKelasModel();
            $numInsert = 0;
            foreach ($param as $key => $value) {
                if (is_null($value->id)) {
                    $value->id = Uuid::uuid4()->toString();
                    $value->temp_krsm_id = $krsm['id'];
                    $value->id_riwayat_pendidikan = $profile->id_riwayat_pendidikan;
                    $temPeserta->save($value);
                    $numInsert += 1;
                }
            }
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'message' => $numInsert . " Ditambahkan",
                'data' => $param
            ]);
        } catch (\Throwable $th) {
            $conn->transRollback();
            return $this->fail(handleErrorDB($th->getCode()));
        }
    }

    function deleted($id = null)
    {
        $peserta = new \App\Models\TempPesertaKelasModel();
        $peserta->delete($id);
        return $this->respondDeleted([
            'status' => true,
        ]);
    }
}

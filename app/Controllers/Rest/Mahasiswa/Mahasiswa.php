<?php

namespace App\Controllers\Rest\Mahasiswa;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahKurikulumModel;
use App\Models\NilaiTransferModel;
use App\Models\PerkuliahanMahasiswaModel;
use App\Models\PesertaKelasModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use App\Models\TranskripModel;
use CodeIgniter\RESTful\ResourceController;

class Mahasiswa extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }

    public function byUserId($id = null)
    {
        return $this->respond([
            'status' => true,
            'data' => getProfile()
        ]);
    }

    public function riwayatPendidikan()
    {
        $profile = getProfile();
        $object = new RiwayatPendidikanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id', $profile->id_riwayat_pendidikan)->findAll()
        ]);
    }

    public function nilaiTransfer()
    {
        $profile = getProfile();
        $object = new NilaiTransferModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->findAll()
        ]);
    }

    public function krsm()
    {
        $profile = getProfile();
        $semester = getSemesterAktif();
        $object = new PesertaKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("kelas_kuliah.*")
                ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)
                ->where('kelas_kuliah.id_semester', $semester->id)
                ->findAll()
        ]);
    }

    public function aktivitasKuliah()
    {
        $profile = getProfile();
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->orderBy('id_semester', 'asc')->findAll()
        ]);
    }

    public function transkrip()
    {
        $profile = getProfile();
        $object = new MatakuliahKurikulumModel();
        $itemKurikulum = $object->select('matakuliah_kurikulum.id, matakuliah_kurikulum.kurikulum_id, matakuliah_kurikulum.matakuliah_id, matakuliah_kurikulum.semester, matakuliah.nama_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah.sks_mata_kuliah')
            ->join('kurikulum', 'kurikulum.id=matakuliah_kurikulum.kurikulum_id', 'left')
            ->join('matakuliah', 'matakuliah.id=matakuliah_kurikulum.matakuliah_id', 'left')
            ->orderBy('semester', 'asc')
            ->where('kurikulum.id_prodi', $profile->id_prodi)->findAll();
        $object = new \App\Models\TranskripModel();
        $itemTranskrip = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->findAll();
        foreach ($itemKurikulum as $key => $kuri) {
            $item = null;
            foreach ($itemTranskrip as $key1 => $value) {
                if($kuri->matakuliah_id == $value->matakuliah_id){
                    if(is_null($item)) $item=$value;
                    else if(!is_null($item) && $item->nilai_indeks < $value->nilai_indeks){
                        $item = $value;
                    }
                }
            }
            if(!is_null($item)){
                $kuri->nilai_angka = $value->nilai_angka;
                $kuri->nilai_huruf = $value->nilai_huruf;
                $kuri->nilai_indeks = $value->nilai_indeks;
            }else{
                $kuri->nilai_angka = null;
                $kuri->nilai_huruf = null;
                $kuri->nilai_indeks = null;
            }
        }
        return $this->respond([
            'status' => true,
            'data' => $itemKurikulum
        ]);
    }



    public function update($id = null)
    {
        try {
            $item = $this->request->getJSON();
            $object = new MahasiswaModel();
            $model = new EntitiesMahasiswa();
            $model->fill((array) $item);
            $object->save($item);
            return $this->respondUpdated([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}

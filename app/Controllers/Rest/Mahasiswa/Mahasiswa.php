<?php

namespace App\Controllers\Rest\Mahasiswa;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Models\MahasiswaModel;
use App\Models\NilaiTransferModel;
use App\Models\PerkuliahanMahasiswaModel;
use App\Models\PesertaKelasModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use App\Models\UserRoleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Shield\Entities\User;

class Mahasiswa extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }

    public function byUserId($id = null)
    {
        $mahasiswa = new MahasiswaModel();
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

<?php

namespace App\Controllers\Rest;

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
            'data' => $mahasiswa
                ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan, wilayah.nama_wilayah, agama.nama_agama, prodi.nama_program_studi, jenis_transportasi.nama_alat_transportasi")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
                ->join('wilayah', 'wilayah.id_wilayah=mahasiswa.id_wilayah', 'left')
                ->join("agama", "agama.id_agama=mahasiswa.id_agama", "LEFT")
                ->join("prodi", "riwayat_pendidikan_mahasiswa.id_prodi=prodi.id_prodi", "LEFT")
                ->join("jenis_transportasi", "jenis_transportasi.id_alat_transportasi=mahasiswa.id_alat_transportasi", "LEFT")
                ->where('id_user', $id)->first()
        ]);
    }

    public function riwayatPendidikan($id = null)
    {
        $object = new RiwayatPendidikanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_mahasiswa', $id)->findAll()
        ]);
    }

    public function nilaiTransfer($id = null)
    {
        $object = new NilaiTransferModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("nilai_transfer.*")
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = nilai_transfer.id_riwayat_pendidikan', 'left')
                ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                ->where('mahasiswa.id', $id)->findAll()
        ]);
    }

    public function krsm($id = null)
    {
        $semester = getSemesterAktif();
        $object = new PesertaKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("kelas_kuliah.*")
                ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                ->where('peserta_kelas.mahasiswa_id', $id)
                ->where('kelas_kuliah.id_semester', $semester->id)
                ->findAll()
        ]);
    }

    public function aktivitasKuliah($id = null)
    {
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object
                ->where('perkuliahan_mahasiswa.id_mahasiswa', $id)
                ->orderBy('id_semester', 'asc')
                ->findAll()
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

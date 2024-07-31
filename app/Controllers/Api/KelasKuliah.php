<?php

namespace App\Controllers\Api;

use App\Models\DosenPengajarKelasModel;
use App\Models\KelasKuliahModel;
use App\Models\PenugasanDosenModel;
use App\Models\PesertaKelasModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class KelasKuliah extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }
    /**
     * @param null $id
     * 
     * @return object
     */
    public function show($id = null, $req = null): object
    {
        $object = new KelasKuliahModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object
                ->select("kelas_kuliah.*, semester.nama_semester, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, prodi.nama_program_studi, dosen_pengajar_kelas.nama_dosen, matakuliah.sks_mata_kuliah, (SELECT COUNT(*) FROM peserta_kelas WHERE peserta_kelas.kelas_kuliah_id=kelas_kuliah.id)as peserta_kelas")
                ->join('semester', 'semester.id_semester=kelas_kuliah.id_semester', 'left')
                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi=kelas_kuliah.id_prodi', 'left')
                ->where('a_periode_aktif', '1')
                ->findAll() :
                $object
                ->select("kelas_kuliah.*, kelas.nama_kelas_kuliah, semester.nama_semester, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, prodi.nama_program_studi, dosen_pengajar_kelas.nama_dosen, matakuliah.sks_mata_kuliah, (SELECT COUNT(*) FROM peserta_kelas WHERE peserta_kelas.kelas_kuliah_id=kelas_kuliah.id)as peserta_kelas")
                ->join('semester', 'semester.id_semester=kelas_kuliah.id_semester', 'left')
                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi=kelas_kuliah.id_prodi', 'left')
                ->join('kelas', 'kelas_kuliah.kelas_id=kelas.id', 'left')
                ->where('kelas_kuliah.id', $id)->first()
        ]);
    }

    public function pesertaKelas($id = null): object
    {
        $object = new PesertaKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object
                ->select("peserta_kelas.id, peserta_kelas.id_riwayat_pendidikan, peserta_kelas.kelas_kuliah_id, mahasiswa.id as mahasiswa_id, matakuliah.id as matakuliah_id, peserta_kelas.nilai_angka, peserta_kelas.nilai_huruf, peserta_kelas.nilai_indeks, peserta_kelas.sync_at, peserta_kelas.status_sync, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.angkatan, kelas.nama_kelas_kuliah, mahasiswa.nama_mahasiswa, mahasiswa.jenis_kelamin, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, kelas_kuliah.id_prodi, prodi.nama_program_studi")
                ->join('kelas_kuliah', 'kelas_kuliah.id=peserta_kelas.kelas_kuliah_id', 'left')
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=peserta_kelas.id_riwayat_pendidikan', 'left')
                ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('kelas', 'kelas_kuliah.kelas_id=kelas.id', 'left')
                ->orderBy('sync_at', 'desc')
                ->where('kelas_kuliah_id', $id)->findAll()
        ]);
    }

    public function mahasiswaProdi($id_prodi = null, $kelas_kuliah_id = null, $angkatan = null): object
    {
        $object = new RiwayatPendidikanMahasiswaModel();
        $where = "riwayat_pendidikan_mahasiswa.id_prodi='" . $id_prodi . "'" . (!is_null($angkatan) ? "AND riwayat_pendidikan_mahasiswa.angkatan='" . $angkatan . "'" : "");

        return $this->respond([
            'status' => true,
            'data' => $object
                ->select("riwayat_pendidikan_mahasiswa.*, mahasiswa.nama_mahasiswa, (SELECT peserta_kelas.kelas_kuliah_id FROM peserta_kelas WHERE kelas_kuliah_id='" . $kelas_kuliah_id . "' AND id_riwayat_pendidikan=riwayat_pendidikan_mahasiswa.id AND peserta_kelas.deleted_at IS NULL limit 1) AS kelas_kuliah_id")
                ->join("mahasiswa", "mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa")
                ->join("mahasiswa_lulus_do", "riwayat_pendidikan_mahasiswa.id=mahasiswa_lulus_do.id_riwayat_pendidikan", "left")
                ->where($where)
                ->where('mahasiswa_lulus_do.id_riwayat_pendidikan IS NULL')
                ->findAll()
        ]);
    }

    public function mahasiswaAll(): object
    {
        $param = $this->request->getJSON();
        $object = new RiwayatPendidikanMahasiswaModel();
        $data = $object->paginate(10, 'default', 1);
        $num = $param->count == 0 ? $object->pager->getDetails()['total'] : $param->count;
        return $this->respond([
            'status' => true,
            'data' => $object
                ->select("riwayat_pendidikan_mahasiswa.*, mahasiswa.nama_mahasiswa")
                ->join("mahasiswa", "mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa", 'left')
                ->join("mahasiswa_lulus_do", "riwayat_pendidikan_mahasiswa.id=mahasiswa_lulus_do.id_riwayat_pendidikan", "left")
                ->groupStart()
                ->like('nim', $param->cari)
                ->orLike('mahasiswa.nama_mahasiswa', $param->cari)
                ->groupEnd()
                ->where('mahasiswa_lulus_do.id_riwayat_pendidikan IS NULL')
                ->paginate($num, 'default', 1)
        ]);
    }

    public function dosenPengajarKelas($id = null): object
    {
        $object = new DosenPengajarKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object
                ->select("dosen_pengajar_kelas.*, penugasan_dosen.nama_dosen, penugasan_dosen.nidn")
                ->join("penugasan_dosen", "penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen", "left")
                ->where('kelas_kuliah_id', $id)->findAll()
        ]);
    }

    public function dosenAll($id = null): object
    {
        $object = new PenugasanDosenModel();
        return $this->respond([
            'status' => true,
            'data' => $object->findAll()
        ]);
    }

    public function create()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('kelasKuliah')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\KelasKuliahModel();
            $model = new \App\Entities\KelasKuliahEntity();
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

    public function createMahasiswa($data = null)
    {
        try {
            if (is_null($data)) {
                $item = $this->request->getJSON();
                if (!$this->validate('pesertaKelas')) {
                    $result = [
                        "status" => false,
                        "message" => $this->validator->getErrors(),
                    ];
                    return $this->failValidationErrors($result);
                }
            } else {
                $item = $data;
                if (!$this->validateData((array) $item, 'pesertaKelas')) {
                    $result = [
                        "status" => false,
                        "message" => $this->validator->getErrors(),
                    ];
                    return $this->failValidationErrors($result);
                }
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\PesertaKelasModel();
            $model = new \App\Entities\PesertaKelasEntity();
            $model->fill((array)$item);
            $object->insert($model);
            if (is_null($data)) {
                return $this->respond([
                    'status' => true,
                    'data' => $model
                ]);
            } else {
                return $model;
            }
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function createMahasiswaCollective()
    {
        $conn = \Config\Database::connect();
        $object = new \App\Models\PesertaKelasModel();
        try {
            $dataParam = $this->request->getJSON();
            $conn->transBegin();
            foreach ($dataParam as $key => $value) {
                try {
                    if ($value->checked == true) {
                        $this->createMahasiswa($value);
                    } else {
                        $object->where('kelas_kuliah_id', $value->kelas_kuliah_id)
                            ->where('id_riwayat_pendidikan', $value->id_riwayat_pendidikan)
                            ->delete();
                    }
                } catch (\Throwable $th) {
                    $conn->transRollback();
                    return $this->fail([
                        'status' => false,
                        'message' => $th->getMessage()
                    ]);
                }
            }
            $conn->transCommit();
            return $this->respond([
                'status' => true
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function createDosen()
    {
        try {
            $item = $this->request->getJSON();
            if (!$this->validate('pengajarKelas')) {
                $result = [
                    "status" => false,
                    "message" => $this->validator->getErrors(),
                ];
                return $this->failValidationErrors($result);
            }
            $item->id = Uuid::uuid4()->toString();
            $object = new \App\Models\DosenPengajarKelasModel();
            $model = new \App\Entities\DosenPengajarKelasEntity();
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
            $object = new \App\Models\KelasKuliahModel();
            $model = new \App\Entities\KelasKuliahEntity();
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

    public function updateMahasiswa($id = null)
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

    public function updateDosen($id = null)
    {
        try {
            $object = new \App\Models\DosenPengajarKelasModel();
            $model = new \App\Entities\DosenPengajarKelasEntity();
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
            $object = new \App\Models\KelasKuliahModel();
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

    public function deleteMahasiswa($id = null)
    {
        try {
            $object = new \App\Models\PesertaKelasModel();
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

    public function deleteDosen($id = null)
    {
        try {
            $object = new \App\Models\DosenPengajarKelasModel();
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

    public function paginate()
    {
        $item = $this->request->getJSON();
        $object = model(KelasKuliahModel::class);
        $item = [
            'status' => true,
            'data' => $object
                ->select("kelas_kuliah.id, kelas_kuliah.status_sync, kelas.nama_kelas_kuliah, semester.nama_semester, matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, prodi.nama_program_studi, dosen.nama_dosen, matakuliah.sks_mata_kuliah, (SELECT COUNT(*) FROM peserta_kelas WHERE peserta_kelas.kelas_kuliah_id=kelas_kuliah.id)as peserta_kelas")
                ->join('semester', 'semester.id_semester=kelas_kuliah.id_semester', 'left')
                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                ->join('penugasan_dosen', 'dosen_pengajar_kelas.id_registrasi_dosen=penugasan_dosen.id_registrasi_dosen', 'left')
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi=kelas_kuliah.id_prodi', 'left')
                ->join('kelas', 'kelas_kuliah.kelas_id=kelas.id', 'left')
                ->groupStart()
                ->like('kelas.nama_kelas_kuliah', $item->cari)
                ->orLike('matakuliah.kode_mata_kuliah', $item->cari)
                ->orLike('matakuliah.nama_mata_kuliah', $item->cari)
                ->orLike('prodi.nama_program_studi', $item->cari)
                ->groupEnd()
                ->where('a_periode_aktif', '1')
                ->paginate($item->count, 'default', $item->page),
            'pager' => $object->pager->getDetails()
        ];
        return $this->respond($item);
    }
}

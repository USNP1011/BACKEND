<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class NilaiPesertaKelas extends ResourceController
{
    /**
     * @param null $id
     * 
     * @return object
     */
    public function kelas($id = null): object
    {
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

    public function skala($id = null): object
    {
        $skala = new \App\Models\SkalaNilaiModel();
        $dataSkala = $skala->where('id_prodi', $id)->orderBy('nilai_indeks', 'asc')->findAll();
        return $this->respond([
            'status' => true,
            'data' => $dataSkala
        ]);
    }

    public function show($id = null): object
    {
        try {
            $object = new \App\Models\KelasKuliahModel();
            $data = $object->select("kelas_kuliah.id, kelas_kuliah.matakuliah_id, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai, ruangan.nama_ruangan, kelas.nama_kelas_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah_kurikulum.semester, prodi.id_prodi, prodi.nama_program_studi")
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi=kelas_kuliah.id_prodi', 'left')
                ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id=matakuliah.id', 'left')
                ->join('kelas', 'kelas.id=kelas_kuliah.kelas_id', 'left')
                ->join('ruangan', 'ruangan.id=kelas_kuliah.ruangan_id', 'left')
                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                ->join('penugasan_dosen', 'penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen', 'left')
                ->join('dosen', 'dosen.id_dosen=penugasan_dosen.id_dosen', 'left')
                ->where('kelas_kuliah.id', $id)
                ->first();

            $object = new \App\Models\PesertaKelasModel();
            $kelas = new \App\Models\KelasKuliahModel();
            $data->pesertaKelas = $object
                ->select('nilai_kelas.*, peserta_kelas.id as id_nilai_kelas, peserta_kelas.id_riwayat_pendidikan, peserta_kelas.kelas_kuliah_id, riwayat_pendidikan_mahasiswa.nim, mahasiswa.nama_mahasiswa')
                ->join('nilai_kelas', 'nilai_kelas.id_nilai_kelas = peserta_kelas.id', 'left')
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
                ->join('mahasiswa', 'mahasiswa.id = riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                ->where('kelas_kuliah_id', $id)
                ->findAll();
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
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

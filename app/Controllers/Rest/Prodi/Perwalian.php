<?php

namespace App\Controllers\Rest\Prodi;

use CodeIgniter\RESTful\ResourceController;

class Perwalian extends ResourceController
{
    public function show($id = null)
    {
        $profile = getProfile();
        $object = new \App\Models\RiwayatPendidikanMahasiswaModel();
        try {
            return $this->respond([
                'status' => true,
                'data' => $object->select("riwayat_pendidikan_mahasiswa.id, riwayat_pendidikan_mahasiswa.nim, mahasiswa.nama_mahasiswa, prodi.nama_program_studi, prodi.kode_program_studi, (SELECT perkuliahan_mahasiswa.sks_total from perkuliahan_mahasiswa where id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id AND sks_total != '0' order by id_semester desc limit 1) as sks_total, (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar, (SELECT status_mahasiswa.nama_status_mahasiswa FROM perkuliahan_mahasiswa LEFT JOIN status_mahasiswa ON status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa order by perkuliahan_mahasiswa.created_at desc limit 1) as nama_status_mahasiswa, (SELECT ips from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id order by id_semester desc limit 1,1) as ips, (SELECT ipk from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id order by id_semester desc limit 1,1) as ipk")
                    ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
                    ->join('kaprodi', 'kaprodi.id_prodi=prodi.id_prodi', 'left')
                    ->join('dosen', 'dosen.id_dosen=kaprodi.id_dosen', 'left')
                    ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                    ->where('dosen.id_user', $profile->id_user)->findAll()
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
            // return $this->fail(handleErrorDB($th->getCode()));
        }
    }

    public function pengajuan($id = null)
    {
        try {
            $profile = getProfile();
            $semester = getSemesterAktif();
            $object = new \App\Models\TempKrsmModel();
            $detail = new \App\Models\TempPesertaKelasModel();
            return $this->respond([
                'status' => true,
                'data' => is_null($id) ? $object->select("temp_krsm.id, temp_krsm.id_riwayat_pendidikan, temp_krsm.id_tahapan, temp_krsm.id_semester, temp_krsm.created_at as tanggal_pengajuan, mahasiswa.nama_mahasiswa, riwayat_pendidikan_mahasiswa.nim, prodi.nama_program_studi, prodi.kode_program_studi, (SELECT sum(matakuliah.sks_mata_kuliah) FROM `temp_peserta_kelas` LEFT JOIN `kelas_kuliah` ON `kelas_kuliah`.`id` = `temp_peserta_kelas`.`kelas_kuliah_id` LEFT JOIN `matakuliah` ON `kelas_kuliah`.`matakuliah_id` = `matakuliah`.`id`) as total_sks_pengajuan, (SELECT ips from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = temp_krsm.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ips, (SELECT ipk from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = temp_krsm.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ipk")
                    ->join("riwayat_pendidikan_mahasiswa", "riwayat_pendidikan_mahasiswa.id=temp_krsm.id_riwayat_pendidikan", "left")
                    ->join("mahasiswa", "mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa", "left")
                    ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
                    ->where('temp_krsm.id_semester', $semester->id_semester)->where('id_tahapan', 2)->findAll() :
                    $detail->select('temp_peserta_kelas.id, temp_peserta_kelas.kelas_kuliah_id, temp_peserta_kelas.id_riwayat_pendidikan, temp_peserta_kelas.temp_krsm_id, matakuliah.nama_mata_kuliah, matakuliah.kode_mata_kuliah, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai')
                    ->join('kelas_kuliah', 'kelas_kuliah.id=temp_peserta_kelas.kelas_kuliah_id', 'left')
                    ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                    ->where('temp_krsm_id', $id)
                    ->findAll()
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
            // return $this->fail(handleErrorDB($th->getCode()));
        }
    }

    function updatePengajuan()
    {
        $object = new \App\Models\TempKrsmModel();
        $tahapan = new \App\Models\TahapanModel();
        $param = $this->request->getJSON();
        try {
            $itemTahapan = $tahapan->where('id', ($param->id_tahapan + 1))->first();
            if ($itemTahapan) {
                $object->update($param->id, ['id_tahapan' => $itemTahapan->id]);
                return $this->respond([
                    'status' => true
                ]);
            }
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
            // return $this->fail(handleErrorDB($th->getCode()));
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

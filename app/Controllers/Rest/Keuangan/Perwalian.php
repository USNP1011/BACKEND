<?php

namespace App\Controllers\Rest\Keuangan;

use CodeIgniter\RESTful\ResourceController;

class Perwalian extends ResourceController
{
    public function pengajuan($id = null)
    {
        try {
            $profile = getProfile();
            $semester = getSemesterAktif();
            $object = new \App\Models\TempKrsmModel();
            $detail = new \App\Models\TempPesertaKelasModel();
            $tahapan = new \App\Models\TahapanModel();
            $itemTahapan = $tahapan->where('tahapan', 'Keuangan')->first();
            return $this->respond([
                'status' => true,
                'data' => is_null($id) ? $object->select("temp_krsm.id, temp_krsm.id_riwayat_pendidikan, temp_krsm.id_tahapan, temp_krsm.id_semester, temp_krsm.created_at as tanggal_pengajuan, mahasiswa.nama_mahasiswa, riwayat_pendidikan_mahasiswa.nim, prodi.nama_program_studi, prodi.kode_program_studi, (SELECT sum(matakuliah.sks_mata_kuliah) FROM `temp_peserta_kelas` LEFT JOIN `kelas_kuliah` ON `kelas_kuliah`.`id` = `temp_peserta_kelas`.`kelas_kuliah_id` LEFT JOIN `matakuliah` ON `kelas_kuliah`.`matakuliah_id` = `matakuliah`.`id`) as total_sks_pengajuan, (SELECT ips from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = temp_krsm.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ips, (SELECT ipk from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = temp_krsm.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ipk")
                    ->join("riwayat_pendidikan_mahasiswa", "riwayat_pendidikan_mahasiswa.id=temp_krsm.id_riwayat_pendidikan", "left")
                    ->join("mahasiswa", "mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa", "left")
                    ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
                    ->where('temp_krsm.id_semester', $semester->id_semester)
                    ->where('id_tahapan', $itemTahapan->id)
                    ->findAll() 
                    :
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
        $pengajuan = $object->where('id', $param->id)->first();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $itemTahapan = $tahapan->where('id', ($param->id_tahapan + 1))->first();
            if ($itemTahapan) {
                $object->update($param->id, ['id_tahapan' => $itemTahapan->id]);
                $conn->transComplete();
                return $this->respond([
                    'status' => true
                ]);
            }else{
                $pesertaTemp = new \App\Models\TempPesertaKelasModel();
                $itemPesertaTemp = $pesertaTemp->select("temp_peserta_kelas.*, matakuliah.sks_mata_kuliah")
                ->join('kelas_kuliah', 'kelas_kuliah.id=temp_peserta_kelas.kelas_kuliah_id', 'left')
                ->join('matakuliah', 'kelas_kuliah.matakuliah_id=matakuliah.id', 'left')
                ->where('temp_krsm_id', $param->id)->findAll();
                $peserta = new \App\Models\PesertaKelasModel();
                $sks = 0;
                foreach ($itemPesertaTemp as $key => $value) {
                    $model = new \App\Entities\PesertaKelasEntity();
                    $model->fill((array)$value);
                    $peserta->insert($model);
                    $sks += $value->sks_mata_kuliah;
                }
                $object->delete($param->id);
                $object = new \App\Models\PerkuliahanMahasiswaModel();
                $object->where('id_riwayat_pendidikan', $pengajuan->id_riwayat_pendidikan)->where('id_semester', $pengajuan->id_semester)->set('id_status_mahasiswa','A')->set('sks_semester', $sks)->update();
                $conn->transComplete();
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

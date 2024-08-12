<?php

namespace App\Controllers\Rest\Dosen;

use CodeIgniter\RESTful\ResourceController;

class Perwalian extends ResourceController
{
    public function show($id = null)
    {
        try {
            $profile = getProfile();
            $object = new \App\Models\DosenWaliModel();
            return $this->respond([
                'status' => true,
                'data' => $object->select("mahasiswa.id, dosen_wali.id_dosen, dosen_wali.id_riwayat_pendidikan, riwayat_pendidikan_mahasiswa.nim, mahasiswa.nama_mahasiswa, prodi.nama_program_studi, prodi.id_prodi, prodi.kode_program_studi, (SELECT perkuliahan_mahasiswa.sks_total from perkuliahan_mahasiswa where id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id AND sks_total != '0' order by id_semester desc limit 1) as sks_total, (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar, (SELECT status_mahasiswa.nama_status_mahasiswa FROM perkuliahan_mahasiswa LEFT JOIN status_mahasiswa ON status_mahasiswa.id_status_mahasiswa=perkuliahan_mahasiswa.id_status_mahasiswa order by perkuliahan_mahasiswa.created_at desc limit 1) as nama_status_mahasiswa, (SELECT ips from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = dosen_wali.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ips, (SELECT ipk from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = dosen_wali.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ipk")
                    ->join('dosen', 'dosen.id_dosen=dosen_wali.id_dosen', 'left')
                    ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=dosen_wali.id_riwayat_pendidikan')
                    ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                    ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
                    ->orderBy('prodi.nama_program_studi', 'asc')
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
            if (is_null($id)) {
                return $this->respond([
                    'status' => true,
                    'data' => $object->select("temp_krsm.id, temp_krsm.id_riwayat_pendidikan, temp_krsm.id_tahapan, temp_krsm.id_semester, temp_krsm.created_at as tanggal_pengajuan, mahasiswa.nama_mahasiswa, riwayat_pendidikan_mahasiswa.nim, prodi.id_prodi, prodi.nama_program_studi, prodi.kode_program_studi, (SELECT sum(matakuliah.sks_mata_kuliah) FROM `temp_peserta_kelas` LEFT JOIN `kelas_kuliah` ON `kelas_kuliah`.`id` = `temp_peserta_kelas`.`kelas_kuliah_id` LEFT JOIN `matakuliah` ON `kelas_kuliah`.`matakuliah_id` = `matakuliah`.`id`) as total_sks_pengajuan, (SELECT ips from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = temp_krsm.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ips, (SELECT ipk from perkuliahan_mahasiswa where perkuliahan_mahasiswa.id_riwayat_pendidikan = temp_krsm.id_riwayat_pendidikan order by id_semester desc limit 1,1) as ipk")
                        ->join("riwayat_pendidikan_mahasiswa", "riwayat_pendidikan_mahasiswa.id=temp_krsm.id_riwayat_pendidikan", "left")
                        ->join("mahasiswa", "mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa", "left")
                        ->join("dosen_wali", "dosen_wali.id_riwayat_pendidikan=riwayat_pendidikan_mahasiswa.id", "left")
                        ->join('prodi', 'prodi.id_prodi=riwayat_pendidikan_mahasiswa.id_prodi', 'left')
                        ->where('temp_krsm.id_semester', $semester->id_semester)->where('id_tahapan', 1)
                        ->where('dosen_wali.id_dosen', $profile->id_dosen)
                        ->where('id_tahapan', 1)
                        ->findAll()
                ]);
            } else {
                $tahapan = new \App\Models\TahapanModel();
                $itemMhs = $object->select('temp_krsm.*, mahasiswa.nama_mahasiswa, riwayat_pendidikan_mahasiswa.nim,riwayat_pendidikan_mahasiswa.angkatan')
                ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=temp_krsm.id_riwayat_pendidikan', 'left')
                ->join('mahasiswa', 'mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa', 'left')
                ->where('temp_krsm.id', $id)->first();
                $detail = $detail->select('temp_peserta_kelas.id, temp_peserta_kelas.kelas_kuliah_id, temp_peserta_kelas.id_riwayat_pendidikan, temp_peserta_kelas.temp_krsm_id, matakuliah.nama_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah.sks_mata_kuliah, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai, kelas.nama_kelas_kuliah, dosen.nama_dosen, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai, matakuliah_kurikulum.semester')
                    ->join('kelas_kuliah', 'kelas_kuliah.id=temp_peserta_kelas.kelas_kuliah_id', 'left')
                    ->join('kelas', 'kelas_kuliah.kelas_id=kelas.id', 'left')
                    ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                    ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                    ->join('dosen', 'dosen.id_dosen=dosen_pengajar_kelas.id_dosen', 'left')
                    ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id=matakuliah.id', 'left')
                    ->where('temp_krsm_id', $id)
                    ->findAll();
                $itemTahapan = $tahapan->where('id', $itemMhs->id_tahapan)->first();
                $object = new \App\Models\PerkuliahanMahasiswaModel();
                $sum = $object->where('id_riwayat_pendidikan', $itemMhs->id_riwayat_pendidikan)->countAllResults();
                $itemKuliah = $object->where('id_riwayat_pendidikan', $itemMhs->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1, $sum > 1 ? 1 : 0)->first();
                $skala = new \App\Models\SkalaSKSModel();
                $itemSkala = $skala->where("ips_min<='" . $itemKuliah->ips . "' AND ips_max>='" . $itemKuliah->ips . "'")->first();
                return $this->respond([
                    'status' => true,
                    'data' => [
                        "pengajuan" => $itemTahapan->tahapan,
                        "pesan"=> $itemMhs->pesan,
                        'matakuliah' => [
                            'id_riwayat_pendidikan' => $itemMhs->id_riwayat_pendidikan,
                            'id_semester' => $itemMhs->id_semester,
                            'nama_semester' => $semester->nama_semester,
                            'nama_mahasiswa'=>$itemMhs->nama_mahasiswa,
                            'nim'=>$itemMhs->nim,
                            'angkatan'=>$itemMhs->angkatan,
                            'id_tahapan'=>$itemTahapan->id,
                            'detail' => $detail
                        ],
                        'roles' => ['sks_max' => $sum <= 2 ? 20 : (int)$itemSkala->sks_max]
                    ]
                ]);
            }
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
        $tempKrsm = new \App\Models\TempKrsmModel();
        try {
            $itemTemp = $tempKrsm->where('id', $param->id)->first();
            $itemTahapan = $tahapan->where('id', ($itemTemp->id_tahapan + 1))->first();
            if (!is_null($itemTahapan)) {
                $object->update($param->id, ['id_tahapan' => $itemTahapan->id]);
                return $this->respond([
                    'status' => true
                ]);
            }else{
                
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

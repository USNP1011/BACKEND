<?php

namespace App\Controllers\Rest\Mahasiswa;

use App\Models\KelasKuliahModel;
use CodeIgniter\RESTful\ResourceController;

class Jadwal extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }

    public function show($id = null)
    {
        try {
            $profile = getProfile();
            $param = $this->request->getJSON();
            $semester = getSemesterAktif();
            $object = new KelasKuliahModel();
            return $this->respond([
                'status' => true,
                'data' => $object->select("`kelas_kuliah`.*,
                    `matakuliah`.`kode_mata_kuliah`,
                    `matakuliah`.`nama_mata_kuliah`,
                    `matakuliah`.`sks_mata_kuliah`,
                    `prodi`.`kode_program_studi`,
                    `prodi`.`nama_program_studi`,
                    `kelas`.`nama_kelas_kuliah`,
                   (if(dosen_pengajar_kelas.id_registrasi_dosen IS NOT NULL , (SELECT penugasan_dosen.nama_dosen FROM penugasan_dosen WHERE penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen LIMIT 1), (SELECT dosen.nama_dosen FROM dosen WHERE dosen.id_dosen = dosen_pengajar_kelas.id_dosen))) as nama_dosen,
                    `matakuliah_kurikulum`.`semester`,
                    `ruangan`.`nama_ruangan`")
                    ->join("matakuliah", "`kelas_kuliah`.`matakuliah_id` = `matakuliah`.`id`", "left")
                    ->join("prodi", "`matakuliah`.`id_prodi` = `prodi`.`id_prodi`", "left")
                    ->join("kelas", "`kelas`.`id` = `kelas_kuliah`.`kelas_id`", "left")
                    ->join("ruangan", "`ruangan`.`id` = `kelas_kuliah`.`ruangan_id`", "left")
                    ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                    ->join("matakuliah_kurikulum", "`matakuliah_kurikulum`.`matakuliah_id` = `matakuliah`.`id`", "left")
                    ->groupStart()
                    ->where("dosen_pengajar_kelas.mengajar", '1')
                    ->orWhere('dosen_pengajar_kelas.mengajar IS NULL')
                    ->groupEnd()
                    ->where("kelas_kuliah.id_prodi", $profile->id_prodi)
                    ->where("kelas_kuliah.id_semester", $semester->id_semester)
                    ->groupStart()
                    ->orLike('`matakuliah`.`nama_mata_kuliah`', $param->cari)
                    ->groupEnd()
                    ->paginate(200, 'default', 1)
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

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
                `dosen`.`nama_dosen`,
                `ruangan`.`nama_ruangan`")
                ->join("matakuliah", "`kelas_kuliah`.`matakuliah_id` = `matakuliah`.`id`", "left")
                ->join("prodi", "`matakuliah`.`id_prodi` = `prodi`.`id_prodi`", "left")
                ->join("kelas", "`kelas`.`id` = `kelas_kuliah`.`kelas_id`", "left")
                ->join("ruangan", "`ruangan`.`id` = `kelas_kuliah`.`ruangan_id`", "left")
                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id')
                ->join('dosen', 'dosen_pengajar_kelas.id_dosen=dosen.id_dosen')
                ->groupStart()
                ->like('`matakuliah`.`nama_mata_kuliah`', $param->cari)
                ->orLike('`dosen`.`nama_dosen`', $param->cari)
                ->orLike('`matakuliah`.`kode_mata_kuliah`', $param->cari)
                ->groupEnd()
                ->where("prodi.id_prodi", $profile->id_prodi)
                ->where("kelas_kuliah.id_semester", $semester->id_semester)
                ->paginate(5, 'default', 1)
        ]);
    }
}

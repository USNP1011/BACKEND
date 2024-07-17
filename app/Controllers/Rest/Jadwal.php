<?php

namespace App\Controllers\Rest;

use App\Models\KelasKuliahModel;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Jadwal extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }

    public function show($id=null)
    {
        $profile = getProfile();
        $param = $this->request->getJSON();
        $semester = getSemesterAktif();
        $object = new KelasKuliahModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("`kelas_kuliah`.*,
                `matakuliah`.`kode_mata_kuliah` AS `kode_mata_kuliah1`,
                `matakuliah`.`nama_mata_kuliah`,
                `matakuliah`.`sks_mata_kuliah`,
                `prodi`.`kode_program_studi`,
                `prodi`.`nama_program_studi` AS `nama_program_studi1`,
                `kelas`.`nama_kelas_kuliah`,
                `ruangan`.`nama_ruangan`")
                ->join("matakuliah", "`kelas_kuliah`.`matakuliah_id` = `matakuliah`.`id`", "left")
                ->join("prodi", "`matakuliah`.`id_prodi` = `prodi`.`id_prodi`", "left")
                ->join("kelas", "`kelas`.`id` = `kelas_kuliah`.`kelas_id`", "left")
                ->join("ruangan", "`ruangan`.`id` = `kelas_kuliah`.`ruangan_id`", "left")
                ->where("prodi.id_prodi", $profile->id_prodi)
                ->where("kelas_kuliah.id_semester", $semester->id_semester)
                ->groupStart()
                ->orLike('`matakuliah`.`nama_mata_kuliah`', $param->cari)
                ->groupEnd()
                ->paginate(5, 'default', 1)
        ]);
    }
}

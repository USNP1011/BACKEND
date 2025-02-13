<?php

namespace App\Controllers\Api\V2;

use App\Controllers\BaseController;
use App\Models\MatakuliahKurikulumModel;

class Kurikulum extends BaseController
{
    public function detail_kurikulum($id = null)
    {
        $object = new MatakuliahKurikulumModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah_kurikulum.sks_mata_kuliah, matakuliah_kurikulum.sks_praktek, matakuliah_kurikulum.sks_praktek_lapangan, matakuliah_kurikulum.sks_simulasi, matakuliah_kurikulum.semester, matakuliah_kurikulum.apakah_wajib")
            ->join('matakuliah', 'matakuliah.id=matakuliah_kurikulum.matakuliah_id', 'left')
            ->where('kurikulum_id', $id)
            ->orderBy('semester')
            ->findAll()
        ]);
    }
}

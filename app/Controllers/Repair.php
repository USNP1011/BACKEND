<?php

namespace App\Controllers;


class Repair extends BaseController
{
    public function repair()
    {
        $object = new \App\Models\KelasKuliahModel();
        $data = $object->where('kelas_id', '1')->where('id_semester', '20241')->findAll();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            foreach ($data as $key => $value) {
                $query = "UPDATE kelas_kuliah SET id_kelas_kuliah='".$value->id_kelas_kuliah."' WHERE matakuliah_id='".$value->matakuliah_id."' AND id_semester='20241' AND kelas_id != '1'";
                $conn->query($query);
            }
            $conn->transComplete();
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    // function dataKrsm() {
    //     $prodi = new \App\Models\ProdiModel();
    //     $conn = \Config\Database::connect();
    //     $data = $prodi->findAll();
    //     foreach ($data as $key => $value) {
    //         $value->totalKRSAktif = $conn->query("")
    //     }
    // }

    // function getBiaya($array, $item) {
    //     foreach ($array as $key => $value) {
    //         if($value->id_prodi==$item->id_prodi && $value->angkatan==$item->angkatan){
    //             return $value;
    //         }
    //     }
    //     return false;
    // }
}

<?php

namespace App\Controllers;

use Ramsey\Uuid\Uuid;
class Repair extends BaseController
{
    public function repair()
    {
        $peserta = new \App\Models\PesertaKelasModel();
        $dataPeserta = $peserta->where('nilai_indeks IS NOT NULL')->findAll();
        $object = new \App\Models\NilaiPesertaKelasModel();
        $dataInsert =[];
        foreach ($dataPeserta as $key => $value) {
            $item = [
                'id_nilai_kelas'=>Uuid::uuid4()->toString(),
                'nilai_angka'=>$value->nilai_angka,
                'nilai_huruf'=>$value->nilai_huruf,
                'nilai_indeks'=>$value->nilai_indeks,
                'status_sync'=>'sudah sync',
                'peserta_kelas_id'=>$value->id,
            ];
            $dataInsert[] = $item;
        }
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $object->insertBatch($dataInsert);
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

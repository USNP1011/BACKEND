<?php

namespace App\Controllers;


class Repair extends BaseController
{
    public function repair()
    {
        $object = new \App\Models\PerkuliahanMahasiswaModel();
        $biaya = new \App\Models\SettingBiayaModel();
        $pembiayaan = $biaya->findAll();
        $record = [];
        try {
            $data = $object->where('id_semester', '20241')->where("sks_total IS NULL AND sks_semester='0'")->findAll();
            foreach ($data as $key => $value) {
                $item = $object->where('id_riwayat_pendidikan', $value->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1,1)->first();
                $object->update($value->id, ['sks_total'=>($item->sks_total+$value->sks_semester)]);
            }
            // $kelas = new \App\Models\PesertaKelasModel();
            // $dataKelas = $kelas->select("peserta_kelas.*")
            // ->join('kelas_kuliah', 'kelas_kuliah.id=peserta_kelas.kelas_kuliah_id', 'left')
            // ->where('id_riwayat_pendidikan', 'e9a1306c-fbfb-414a-8e93-66f0609689a4')
            // ->where('id_semester', $itemOld->id_semester)->findAll();
            return $this->respond(true);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    function getBiaya($array, $item) {
        foreach ($array as $key => $value) {
            if($value->id_prodi==$item->id_prodi && $value->angkatan==$item->angkatan){
                return $value;
            }
        }
        return false;
    }
}

<?php

namespace App\Controllers;


class Repair extends BaseController
{
    public function repair()
    {
        $object = new \App\Models\PerkuliahanMahasiswaModel();
        $biaya = new \App\Models\SettingBiayaModel();
        $record = [];
        try {
            $data = $object->select('perkuliahan_mahasiswa.*, riwayat_pendidikan_mahasiswa.id_prodi, riwayat_pendidikan_mahasiswa.angkatan')
            ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
            ->where('id_semester', '20241')->where('biaya_kuliah_smt IS NULL')->findAll();
            $pembiayaan = $biaya->findAll();
            foreach ($data as $key => $value) {
                $itemBiaya = $this->getBiaya($pembiayaan, $value);
                if($itemBiaya != false){
                    $object->update($value->id, ['biaya_kuliah_smt'=>$itemBiaya->biaya]);
                }
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

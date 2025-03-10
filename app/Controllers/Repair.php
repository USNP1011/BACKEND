<?php

namespace App\Controllers;

use Ramsey\Uuid\Uuid;

class Repair extends BaseController
{
    protected $conn;
    public function __construct()
    {
        $this->conn = \Config\Database::connect();
    }
    public function repair()
    {
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        // $data = $riwayat->where('id_jenis_keluar IS NULL')->findAll();
        $pesertaKelas = new \App\Models\PesertaKelasModel();
        $dataPeserta = $pesertaKelas
            ->select("peserta_kelas.id_riwayat_pendidikan, peserta_kelas.kelas_kuliah_id, nilai_kelas.*, kelas_kuliah.id_semester, , kelas_kuliah.matakuliah_id, matakuliah.sks_mata_kuliah")
            ->join('kelas_kuliah', 'kelas_kuliah.id=peserta_kelas.kelas_kuliah_id', 'left')
            ->join('nilai_kelas', 'nilai_kelas.id_nilai_kelas = peserta_kelas.id', 'left')
            ->join('matakuliah', 'matakuliah.id = kelas_kuliah.matakuliah_id', 'left')
            ->where('id_semester', '20241')
            ->findAll();
        $perkuliahan = new \App\Models\PerkuliahanMahasiswaModel();
        $dataPerkuliahan = $perkuliahan->where('id_semester', '20241')->findAll();
        try {
            foreach ($dataPerkuliahan as $key => $value) {
                if ($value->id_status_mahasiswa == 'A') {
                    $value->ips = round($this->getKelas($dataPeserta, $value->id_riwayat_pendidikan), 2);
                    $dataTrans = $this->conn->query("SELECT 
                            SUM(sks_mata_kuliah * nilai_indeks) / SUM(sks_mata_kuliah) AS ipk
                        FROM 
                            transkrip
                        LEFT JOIN matakuliah on matakuliah.id = transkrip.matakuliah_id
                        WHERE id_riwayat_pendidikan = '" . $value->id_riwayat_pendidikan . "'")->getRow();
                    $value->ipk = round($dataTrans->ipk, 2);
                } else {
                    $lastPerkuliahan = $perkuliahan->where('id_riwayat_pendidikan', $value->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1, 1)->first();
                    $value->ips = "0";
                    $value->ipk = $lastPerkuliahan->ipk;
                }
                // $model = new \App\Entities\AktivitasKuliahEntity();
                // $model->fill((array)$value);
                $perkuliahan->update($value->id, ['ips'=>$value->ips, 'ipk'=>$value->ipk]);
            }
            return $this->respond($dataPerkuliahan);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
    function getKelas($array, $id)
    {
        $data = [];
        $object = new \App\Models\TranskripModel();
        try {
            $nxsks = 0;
            $sks = 0;
            foreach ($array as $key => $value) {
                if ($value->id_riwayat_pendidikan == $id and $value->id_semester == '20241') {
                    $this->conn->query("DELETE FROM transkrip WHERE id_riwayat_pendidikan = '" . $id . "' AND matakuliah_id='" . $value->matakuliah_id . "' AND kelas_kuliah_id='" . $value->kelas_kuliah_id . "'");
                    $item = [
                        'id' => Uuid::uuid4()->toString(),
                        'id_riwayat_pendidikan' => $value->id_riwayat_pendidikan,
                        'matakuliah_id' => $value->matakuliah_id,
                        'kelas_kuliah_id' => $value->kelas_kuliah_id,
                        'nilai_angka' => $value->nilai_angka,
                        'nilai_huruf' => $value->nilai_huruf,
                        'nilai_indeks' => $value->nilai_indeks,
                        'konversi_kampus_merdeka_id' => NULL,
                        'nilai_transfer_id' => NULL,
                    ];
                    if($value->nilai_indeks > 1){
                        $data[] = $item;
                    }
                    // $model = new \App\Entities\TranskripEntity();
                    // $model->fill($item);
                    $sks += (int)$value->sks_mata_kuliah;
                    $nxsks += ($value->sks_mata_kuliah * floatval($value->nilai_indeks));
                }
            }
            $object->insertBatch($data);
            return $nxsks / $sks;
        } catch (\Throwable $th) {
            return false;
        }
    }
}

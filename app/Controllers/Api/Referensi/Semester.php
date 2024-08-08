<?php

namespace App\Controllers\Api\Referensi;

use App\Models\SemesterModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Semester extends ResourceController
{
    public function store($id = null)
    {
        $object = new SemesterModel();
        return $this->respond([
            'status' => true,
            'data' => $id == null ? $object->where("id_semester>='20231'")->findAll() : $object->where('id_semester', $id)->first()
        ]);
    }

    public function update($id = null)
    {
        $conn = \Config\Database::connect();
        $param = $this->request->getJSON();
        try {
            $conn->transException(true)->transStart();
            $object = new \App\Models\SemesterModel();
            $model = new \App\Entities\Semester();
            $itemSemester = $object->where('id_semester', $param->id_semester)->first();
            if (($itemSemester->status_kuliah == '0' || is_null($itemSemester->status_kuliah)) && substr($param->id_semester, -1) < 3) {
                $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
                $dataRiwayat = $riwayat->select("riwayat_pendidikan_mahasiswa.id, riwayat_pendidikan_mahasiswa.angkatan, riwayat_pendidikan_mahasiswa.id_prodi, (SELECT jenis_keluar.jenis_keluar FROM mahasiswa_lulus_do LEFT JOIN jenis_keluar on jenis_keluar.id_jenis_keluar = mahasiswa_lulus_do.id_jenis_keluar WHERE mahasiswa_lulus_do.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id  limit 1) as nama_jenis_keluar")->findAll();
                $tempMhs = [];
                $biaya = new \App\Models\SettingBiayaModel();
                foreach ($dataRiwayat as $key => $value) {
                    $itemBiaya = $biaya->where('id_prodi', $value->id_prodi)->where('angkatan', $value->angkatan)->first();
                    if (!is_null($itemBiaya)) {
                        if (is_null($value->nama_jenis_keluar)) {
                            $item = [
                                'id' => Uuid::uuid4()->toString(),
                                'id_riwayat_pendidikan' => $value->id,
                                'id_semester' => $itemSemester->id_semester,
                                'id_status_mahasiswa' => "N",
                                'ips' => "0",
                                'ipk' => "0",
                                'sks_total' => "0",
                                'sks_semester' => "0",
                                'biaya_kuliah_smt' => $itemBiaya->biaya
                            ];
                            $tempMhs[] = $item;
                        }
                    } else {
                        throw new \Exception("Data Pembiayaan untuk angkatan " . $value->angkatan . " belum ada", 1);
                    }
                }
                $kuliah = new \App\Models\PerkuliahanMahasiswaModel();
                $kuliah->insertBatch($tempMhs);
                $param->status_kuliah = "1";
                $param->a_periode_aktif = "1";
            }
            $model->fill((array)$param);
            $object->set('a_periode_aktif', '0')->where('a_periode_aktif', '1')->update();
            $object->save($model);
            $object = new \App\Models\PeriodePerkuliahanModel();
            if ($object->where('id_semester', $param->id_semester)->countAllResults() == 0) {
                $prodi = new \App\Models\ProdiModel();
                $dataProdi = $prodi->where('status', 'A')->findAll();
                foreach ($dataProdi as $key => $value) {
                    $item = [
                        'id'=>Uuid::uuid4()->toString(),
                        'id_prodi' => $value->id_prodi,
                        'id_semester' => $param->id_semester,
                        'id_semester' => $param->id_semester,
                        'jumlah_target_mahasiswa_baru'=>0,
                        'jumlah_pendaftar_ikut_seleksi'=>0,
                        'jumlah_pendaftar_lulus_seleksi'=>0,
                        'jumlah_daftar_ulang'=>0,
                        'jumlah_mengundurkan_diri'=>0,
                        'jumlah_minggu_pertemuan'=>0,
                        'tanggal_awal_perkuliahan'=>$itemSemester->tanggal_mulai,
                        'tanggal_akhir_perkuliahan'=>$itemSemester->tanggal_selesai,
                    ];
                    $model = new \App\Entities\PeriodePerkuliahanEntity();
                    $model->fill($item);
                    $object->insert($model);
                }
            }
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $param
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

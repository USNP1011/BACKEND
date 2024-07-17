<?php

namespace App\Controllers\Rest;

use App\Models\KelasKuliahModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Ramsey\Uuid\Uuid;

class Krsm extends ResourceController
{
    public function show($id = null)
    {
        try {
            $profile = getProfile();
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            if ($object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->first()->sks_semester == 0) {
                $semester = new \App\Models\SemesterModel();
                $sum = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->countAllResults();
                $itemKuliah = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1, $sum > 1 ? 1 : 0)->first();
                $skala = new \App\Models\SkalaSKSModel();
                $itemSkala = $skala->where("ips_min<='" . $itemKuliah->ips . "' AND ips_max>='" . $itemKuliah->ips . "'")->first();
                if ($semester->where('a_periode_aktif', 1)->where("DATE(batas_pengisian_krsm)>=CURDATE()")->countAllResults() > 0) {
                    $object = new \App\Models\TempKrsmModel();
                    $data = $object->select('temp_krsm.*, semester.nama_semester')->join('semester', 'semester.id_semester=temp_krsm.id_semester')
                        ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->first();
                    if (!is_null($data)) {
                        $object = new \App\Models\TempPesertaKelasModel();
                        $data->detail = $object->select('temp_peserta_kelas.*, matakuliah.nama_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah.sks_mata_kuliah, kelas.nama_kelas_kuliah, dosen.nidn, dosen.nama_dosen')
                            ->join('kelas_kuliah', 'kelas_kuliah.id=temp_peserta_kelas.kelas_kuliah_id')
                            ->join('matakuliah', 'kelas_kuliah.matakuliah_id=matakuliah.id')
                            ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id')
                            ->join('dosen', 'dosen_pengajar_kelas.id_dosen=dosen.id_dosen')
                            ->join('kelas', 'kelas_kuliah.kelas_id=kelas.id')
                            ->where('temp_krsm_id', $data->id)->findAll();
                        $object = new \App\Models\TahapanModel();
                        return $this->respond([
                            'status' => true,
                            'data' => ["pengajuan" => $object->where('id', $data->id_tahapan)->first()->tahapan, "matakuliah" => $data],
                            'roles' => ['sks_max' => $sum<=2 ? 20 : (int)$itemSkala->sks_max]
                        ]);
                    } else {
                        return $this->respond([
                            'status' => true,
                            'data' => ["pengajuan" => "belum pengajuan", "matakuliah" => null],
                            'roles' => ['sks_max' => $sum<=2 ? 20 : (int)$itemSkala->sks_max]
                        ]);
                    }
                } else {
                    return $this->fail('Pengisian KRSM telah ditutup, silahkan hubungi bagian BAAK');
                }
            } else {
                $semester = getSemesterAktif();
                $object = new \App\Models\PesertaKelasModel();
                return $this->respond([
                    'status' => true,
                    'data' => [
                        "pengajuan" => "finish",
                        "matakuliah" => $object->select("kelas_kuliah.*")
                            ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                            ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
                            ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)
                            ->where('kelas_kuliah.id_semester', $semester->id_semester)
                            ->findAll()
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            return $this->fail(handleErrorDB($th->getCode()));
        }
    }

    function create()
    {
        $conn = \Config\Database::connect();
        $profile = getProfile();
        $object = new \App\Models\PerkuliahanMahasiswaModel();
        $sum = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->countAllResults();
        $itemKuliah = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1, $sum > 1 ? 1 : 0)->first();
        $skala = new \App\Models\SkalaSKSModel();
        $itemSkala = $skala->where("ips_min<='" . $itemKuliah->ips . "' AND ips_max>='" . $itemKuliah->ips . "'")->first();
        try {
            $conn->transException(true)->transStart();
            $temKrsm = new \App\Models\TempKrsmModel();
            $param = $this->request->getJSON();
            if (array_reduce($param, function ($carry, $product) {
                return $carry + $product->sks_mata_kuliah;
            }, 0) > $itemSkala->sks_max) {
                throw new \Exception('SKS yang dipilih melebihi ' . $itemSkala->sks_max . 'SKS', 1);
            }
            if ($temKrsm->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->countAllResults() == 0) {
                $krsm = [
                    'id' => Uuid::uuid4()->toString(),
                    'id_riwayat_pendidikan' => $profile->id_riwayat_pendidikan,
                    'id_tahapan' => '1',
                    'id_semester' => getSemesterAktif()->id_semester
                ];
                $temKrsm->insert($krsm);
            } else {
                $krsm = $temKrsm->asArray()->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->first();
            }
            foreach ($param as $key => $value) {
                $value->id = !isset($value->id) ? Uuid::uuid4()->toString() : $value->id;
                $value->temp_krsm_id = $krsm['id'];
                $value->id_riwayat_pendidikan = $profile->id_riwayat_pendidikan;
                $temPeserta = new \App\Models\TempPesertaKelasModel();
                $temPeserta->save($value);
            }
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $param
            ]);
        } catch (\Throwable $th) {
            $conn->transRollback();
            return $this->fail(handleErrorDB($th->getCode()));
        }
    }

    function deleted($id = null)
    {
        $peserta = new \App\Models\TempPesertaKelasModel();
        $peserta->delete($id);
        return $this->respondDeleted([
            'status' => true,
        ]);
    }
}

<?php

namespace App\Controllers\Rest\Mahasiswa;

use CodeIgniter\RESTful\ResourceController;
use Ramsey\Uuid\Uuid;

class Krsm extends ResourceController
{
    public function show($id = null)
    {
        try {
            $profile = getProfile();
            $semester = getSemesterAktif();
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $perkuliahan = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->where('id_semester', $semester->id_semester)->orderBy('id_semester', 'desc')->first();
            if(!is_null($perkuliahan)){
                if ($perkuliahan->sks_semester == 0) {
                    $sum = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->countAllResults();
                    $smt = new \App\Models\SemesterModel();
                    $itemKuliah = $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->orderBy('id_semester', 'desc')->limit(1, $sum > 1 ? 1 : 0)->first();
                    $skala = new \App\Models\SkalaSKSModel();
                    $itemSkala = $skala->where("ips_min<='" . $itemKuliah->ips . "' AND ips_max>='" . $itemKuliah->ips . "'")->first();
                    if ($smt->where('a_periode_aktif', 1)->where("DATE(batas_pengisian_krsm)>=CURDATE()")->countAllResults() > 0) {
                        $object = new \App\Models\TempKrsmModel();
                        $data = $object->select('temp_krsm.*, semester.nama_semester')->join('semester', 'semester.id_semester=temp_krsm.id_semester')
                            ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->first();
                        if (!is_null($data)) {
                            $object = new \App\Models\TempPesertaKelasModel();
                            $data->detail = $object->select('temp_peserta_kelas.*, matakuliah.nama_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah.sks_mata_kuliah, kelas.nama_kelas_kuliah, 
                            (if(dosen_pengajar_kelas.id_registrasi_dosen IS NOT NULL , (SELECT penugasan_dosen.nidn FROM penugasan_dosen WHERE penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen LIMIT 1), (SELECT dosen.nidn FROM dosen WHERE dosen.id_dosen = dosen_pengajar_kelas.id_dosen))) as nidn, 
                            (if(dosen_pengajar_kelas.id_registrasi_dosen IS NOT NULL , (SELECT penugasan_dosen.nama_dosen FROM penugasan_dosen WHERE penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen LIMIT 1), (SELECT dosen.nama_dosen FROM dosen WHERE dosen.id_dosen = dosen_pengajar_kelas.id_dosen))) as nama_dosen, 
                            kelas_kuliah.hari, 
                            kelas_kuliah.jam_mulai, jam_selesai')
                                ->join('kelas_kuliah', 'kelas_kuliah.id=temp_peserta_kelas.kelas_kuliah_id', 'left')
                                ->join('matakuliah', 'kelas_kuliah.matakuliah_id=matakuliah.id', 'left')
                                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                                ->join('kelas', 'kelas.id=kelas_kuliah.kelas_id', 'left')
                                ->where('temp_krsm_id', $data->id)
                                ->where("dosen_pengajar_kelas.mengajar", '1')
                                ->findAll();
                            $object = new \App\Models\TahapanModel();
                            return $this->respond([
                                'status' => true,
                                'data' => ["pengajuan" => $object->where('id', $data->id_tahapan)->first()->tahapan, "matakuliah" => $data, 'pesan'=> $data->pesan, 'roles' => ['sks_max' => $sum <= 2 ? 20 : (int)$itemSkala->sks_max]],
                            ]);
                        } else {
                            return $this->respond([
                                'status' => true,
                                'data' => [
                                    "pengajuan" => "belum pengajuan",
                                    "pesan"=>null,
                                    "matakuliah" => [
                                        'id_riwayat_pendidikan' => $profile->id_riwayat_pendidikan,
                                        'id_semester' => $semester->id_semester,
                                        'nama_semester' => $semester->nama_semester,
                                        'detail' => []
                                    ],
                                    'roles' => ['sks_max' => $sum <= 2 ? 20 : (int)$itemSkala->sks_max]
                                ]
                            ]);
                        }
                    } else {
                        return $this->fail('Pengisian KRSM telah ditutup, silahkan hubungi bagian BAAK');
                    }
                } else {
                    $object = new \App\Models\PesertaKelasModel();
                    $items = $object->select("kelas_kuliah.id, kelas_kuliah.id as kelas_kuliah_id,id_kelas_kuliah,kelas_kuliah.id_prodi,matakuliah_id,id_semester,nama_semester,kelas_kuliah.kelas_id,bahasan,lingkup,mode,kapasitas,hari,jam_mulai,jam_selesai,ruangan_id,kelas_kuliah.sync_at,kelas_kuliah.status_sync, kelas.nama_kelas_kuliah, 
                    matakuliah.kode_mata_kuliah, 
                    matakuliah.nama_mata_kuliah, 
                    matakuliah.sks_mata_kuliah, 
                    (if(dosen_pengajar_kelas.id_registrasi_dosen IS NOT NULL , (SELECT penugasan_dosen.nidn FROM penugasan_dosen WHERE penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen LIMIT 1), (SELECT dosen.nidn FROM dosen WHERE dosen.id_dosen = dosen_pengajar_kelas.id_dosen))) as nidn, 
                    (if(dosen_pengajar_kelas.id_registrasi_dosen IS NOT NULL , (SELECT penugasan_dosen.nama_dosen FROM penugasan_dosen WHERE penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen LIMIT 1), (SELECT dosen.nama_dosen FROM dosen WHERE dosen.id_dosen = dosen_pengajar_kelas.id_dosen))) as nama_dosen, 
                    ruangan.nama_ruangan")
                        ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                        ->join('matakuliah', 'kelas_kuliah.matakuliah_id = matakuliah.id', 'left')
                        ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id = kelas_kuliah.id', 'left')
                        ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
                        ->join('ruangan', 'ruangan.id = kelas_kuliah.ruangan_id', 'left')
                        ->join('kelas', 'kelas.id = kelas_kuliah.kelas_id', 'left')
                        ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)
                        ->where('kelas_kuliah.id_semester', $semester->id_semester)
                        ->where("dosen_pengajar_kelas.mengajar", '1')
                        ->findAll();
                    $sks_semester = 0;
                    foreach ($items as $key => $value) {
                        $sks_semester+=$value->sks_mata_kuliah;
                    }
                    $matakuliah = [
                        'id_riwayat_pendidikan' => $profile->id_riwayat_pendidikan,
                        'id_semester' => $semester->id_semester,
                        'nama_semester' => $semester->nama_semester,
                        'nama_mahasiswa' => $profile->nama_mahasiswa,
                        'nim' => $profile->nim,
                        'nama_program_studi' => $profile->nama_program_studi,
                        'nama_kaprodi' => getKaprodi($profile->id_prodi)->nama_dosen,
                        'dosen_wali' => $profile->dosen_wali,
                        'sks_semester'=>$sks_semester,
                        'detail' => $items
                    ];
                    return $this->respond([
                        'status' => true,
                        'data' => [
                            "pengajuan" => "Aktif",
                            "pesan"=>null,
                            "matakuliah" => $matakuliah,
                            "roles" => ['sks_max' => 0]
                        ]
                    ]);
                }
            }else throw new \Exception("Belum ada aktivitas kuliah, Segera melapor ke bagian BAAK", 1);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    // public function getKRSM($id_registrasi, $id_semester)
    // {
    //     $object = new \App\Models\PesertaKelasModel();
    //     return $this->respond([
    //         'status' => true,
    //         'data' => $object->select("kelas_kuliah.*, kelas.nama_kelas_kuliah, matakuliah.sks_mata_kuliah, penugasan_dosen.nidn, penugasan_dosen.nama_dosen")
    //             ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
    //             ->join('matakuliah', 'kelas_kuliah.matakuliah_id = matakuliah.id', 'left')
    //             ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id = kelas_kuliah.id', 'left')
    //             ->join('penugasan_dosen', 'penugasan_dosen.id_registrasi_dosen = dosen_pengajar_kelas.id_registrasi_dosen', 'left')
    //             ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id = peserta_kelas.id_riwayat_pendidikan', 'left')
    //             ->join('kelas', 'kelas.id = kelas_kuliah.kelas_id', 'left')
    //             ->where('id_riwayat_pendidikan', $id_registrasi)
    //             ->where('kelas_kuliah.id_semester', $id_semester)
    //             ->findAll()
    //     ]);
    // }

    function create($id=null)
    {
        $conn = \Config\Database::connect();
        $object = new \App\Models\PerkuliahanMahasiswaModel();
        $semester = getSemesterAktif();
        if(is_null($id)){
            $profile = getProfile();
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
                $tahapan = new \App\Models\TahapanModel();
                $itemTahapan = $tahapan->where('id', '1')->first();
                if ($temKrsm->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->countAllResults() == 0) {
                    $krsm = [
                        'id' => Uuid::uuid4()->toString(),
                        'id_riwayat_pendidikan' => $profile->id_riwayat_pendidikan,
                        'id_tahapan' => '1',
                        'id_semester' => $semester->id_semester
                    ];
                    $temKrsm->insert($krsm);
                    $krsm['nama_tahapan'] = $itemTahapan->tahapan;
                    $krsm['nama_semester'] = $semester->nama_semester;
                } else {
                    $krsm = $temKrsm->asArray()->select('temp_krsm.*, semester.nama_semester')
                        ->join('semester', 'semester.id_semester=temp_krsm.id_semester', 'left')
                        ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->first();
                }
                $temPeserta = new \App\Models\TempPesertaKelasModel();
                $numInsert = 0;
                foreach ($param as $key => $value) {
                    if (is_null($value->id)) {
                        $value->id = Uuid::uuid4()->toString();
                        $value->temp_krsm_id = $krsm['id'];
                        $value->id_riwayat_pendidikan = $profile->id_riwayat_pendidikan;
                        $temPeserta->save($value);
                        $numInsert += 1;
                    }
                }
                $krsm['detail'] = $param;
                $conn->transComplete();
                return $this->respond([
                    'status' => true,
                    'data' => [
                        "pengajuan" => $itemTahapan->tahapan,
                        "matakuliah" => $krsm,
                        "roles" => ['sks_max' => $itemSkala->sks_max]
                    ]
                ]);
            } catch (\Throwable $th) {
                $conn->transRollback();
                return $this->fail(handleErrorDB($th->getCode()));
            }
        }else{
            $sum = $object->where('id_riwayat_pendidikan', $id)->countAllResults();
            $itemKuliah = $object->where('id_riwayat_pendidikan', $id)->orderBy('id_semester', 'desc')->limit(1, $sum > 1 ? 1 : 0)->first();
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
                $tahapan = new \App\Models\TahapanModel();
                $itemTahapan = $tahapan->where('id', '1')->first();
                if ($temKrsm->where('id_riwayat_pendidikan', $id)->countAllResults() == 0) {
                    $krsm = [
                        'id' => Uuid::uuid4()->toString(),
                        'id_riwayat_pendidikan' => $id,
                        'id_tahapan' => '1',
                        'id_semester' => $semester->id_semester
                    ];
                    $temKrsm->insert($krsm);
                    $krsm['nama_tahapan'] = $itemTahapan->tahapan;
                    $krsm['nama_semester'] = $semester->nama_semester;
                } else {
                    $krsm = $temKrsm->asArray()->select('temp_krsm.*, semester.nama_semester')
                        ->join('semester', 'semester.id_semester=temp_krsm.id_semester', 'left')
                        ->where('id_riwayat_pendidikan', $id)->first();
                }
                $temPeserta = new \App\Models\TempPesertaKelasModel();
                $numInsert = 0;
                foreach ($param as $key => $value) {
                    if (is_null($value->id)) {
                        $value->id = Uuid::uuid4()->toString();
                        $value->temp_krsm_id = $krsm['id'];
                        $value->id_riwayat_pendidikan = $id;
                        $temPeserta->save($value);
                        $numInsert += 1;
                    }
                }
                $krsm['detail'] = $param;
                $conn->transComplete();
                return $this->respond([
                    'status' => true,
                    'data' => [
                        "pengajuan" => $itemTahapan->tahapan,
                        "matakuliah" => $krsm,
                        "roles" => ['sks_max' => $itemSkala->sks_max]
                    ]
                ]);
            } catch (\Throwable $th) {
                $conn->transRollback();
                return $this->fail(handleErrorDB($th->getCode()));
            }

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

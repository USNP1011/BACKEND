<?php

namespace App\Controllers;

use App\Libraries\Rest;
use stdClass;

class Sync extends BaseController
{
    protected $token;
    protected $api;
    public function __construct()
    {
        set_time_limit(0);
        try {
            $this->api = new Rest();
            $this->token = $this->api->getToken()->data->token;
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => "Server feeder sedang tidak aktif"
            ]);
        }
    }
    public function index()
    {
        return view('sync');
    }

    public function getSync(): object
    {
        try {
            $conn = \Config\Database::connect();
            $semester = getSemesterAktif();
            $array = [];
            $data = new stdClass;

            // Mahasiswa
            $object = new \App\Models\MahasiswaModel();
            $data->mahasiswa = $object->select("mahasiswa.id, (if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();
            $array[] = [
                'index' => 1,
                'target' => 'mahasiswa',
                'displayName' => 'Mahasiswa',
                'data' => $data->mahasiswa
            ];


            // History
            $object = new \App\Models\RiwayatPendidikanMahasiswaModel();
            $data->riwayat_pendidikan = $object->select("riwayat_pendidikan_mahasiswa.id, (if(id_registrasi_mahasiswa is null AND deleted_at is null, 'insert', if(id_registrasi_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_registrasi_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_registrasi_mahasiswa is null AND deleted_at is null, 'insert', if(id_registrasi_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_registrasi_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();
            $array[] = [
                'index' => 2,
                'target' => 'riwayat_pendidikan',
                'displayName' => 'Riwayat Pendidikan Mahasiswa',
                'data' => $data->riwayat_pendidikan
            ];

            // Nilai Transfer
            $object = new \App\Models\NilaiTransferModel();
            $data->nilai_transfer = $object->select("nilai_transfer.id, (if(id_transfer is null AND deleted_at is null, 'insert', if(id_transfer is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_transfer is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_transfer is null AND deleted_at is null, 'insert', if(id_transfer is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_transfer is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

            // Kelas Kuliah
            $object = new \App\Models\KelasKuliahModel();
            $data->kelas_kuliah = $object->select("kelas_kuliah.id, kelas_kuliah.id_kelas_kuliah,kelas_kuliah.id_prodi,matakuliah_id,kelas_kuliah.id_semester,kelas_kuliah.nama_semester,kelas_kuliah.nama_program_studi,kelas_kuliah.kode_mata_kuliah,kelas_kuliah.nama_mata_kuliah, kelas_kuliah.kelas_id, kelas_kuliah.bahasan,kelas_kuliah.tanggal_mulai_efektif,kelas_kuliah.tanggal_akhir_efektif,kelas_kuliah.lingkup,kelas_kuliah.mode,kelas_kuliah.kapasitas, (if(kelas_kuliah.id_kelas_kuliah is null AND deleted_at is null, 'insert', if(kelas_kuliah.id_kelas_kuliah is not null AND deleted_at is null and sync_at<updated_at, 'update', if(kelas_kuliah.id_kelas_kuliah is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(kelas_kuliah.id_kelas_kuliah is null AND deleted_at is null, 'insert', if(kelas_kuliah.id_kelas_kuliah is not null AND deleted_at is null and sync_at<updated_at, 'update', if(kelas_kuliah.id_kelas_kuliah is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")
                ->findAll();

            $array[] = [
                'index' => 3,
                'target' => 'kelas_kuliah',
                'displayName' => 'Kelas Perkuliahan',
                'data' => $data->kelas_kuliah
            ];

            // Peserta Kelas
            $object = new \App\Models\PesertaKelasModel();
            $data->peserta_kelas = $object->select("peserta_kelas.id, (if(peserta_kelas.status_sync is null AND peserta_kelas.deleted_at is null, 'insert', if(peserta_kelas.status_sync is not null AND peserta_kelas.deleted_at is null and peserta_kelas.sync_at<peserta_kelas.updated_at, 'update', if(peserta_kelas.status_sync is not null and peserta_kelas.deleted_at is not null and peserta_kelas.sync_at<peserta_kelas.deleted_at,'delete', null)))) as set_sync")->where("if(peserta_kelas.status_sync is null AND peserta_kelas.deleted_at is null, 'insert', if(peserta_kelas.status_sync is not null AND peserta_kelas.deleted_at is null and peserta_kelas.sync_at<peserta_kelas.updated_at, 'update', if(peserta_kelas.status_sync is not null and peserta_kelas.deleted_at is not null and peserta_kelas.sync_at<peserta_kelas.deleted_at,'delete', null))) IS NOT NULL")->withDeleted()->findAll();
            $array[] = [
                'index' => 4,
                'target' => 'peserta_kelas',
                'displayName' => 'Peserta Kelas',
                'data' => $data->peserta_kelas
            ];

            // Dosen Pengajar Kelas
            $object = new \App\Models\DosenPengajarKelasModel();
            $data->dosen_pengajar_kelas = $object->select("dosen_pengajar_kelas.id, matakuliah.nama_mata_kuliah, matakuliah.kode_mata_kuliah, dosen.nama_dosen, kelas_kuliah.kelas_id, semester, (if(id_aktivitas_mengajar is null AND dosen_pengajar_kelas.deleted_at is null, 'insert', if(id_aktivitas_mengajar is not null AND dosen_pengajar_kelas.deleted_at is null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at, 'update', if(id_aktivitas_mengajar is not null and dosen_pengajar_kelas.deleted_at is not null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at,'delete', null)))) as set_sync")
                ->join('penugasan_dosen', 'penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen', 'left')
                ->join('dosen', 'dosen.id_dosen=penugasan_dosen.id_dosen', 'left')
                ->join('kelas_kuliah', 'kelas_kuliah.id=dosen_pengajar_kelas.kelas_kuliah_id', 'left')
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id=matakuliah.id', 'left')
                ->where("if(id_aktivitas_mengajar is null AND dosen_pengajar_kelas.deleted_at is null, 'insert', if(id_aktivitas_mengajar is not null AND dosen_pengajar_kelas.deleted_at is null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at, 'update', if(id_aktivitas_mengajar is not null and dosen_pengajar_kelas.deleted_at is not null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at,'delete', null))) IS NOT NULL AND dosen_pengajar_kelas.status_pengajar='Dosen'")->findAll();

            // Nilai Peserta Kelas
            $object = \Config\Database::connect();
            $data->nilai_peserta_kelas = $object->query("SELECT nilai_kelas.*, kelas_kuliah.id_kelas_kuliah, riwayat_pendidikan_mahasiswa.id_registrasi_mahasiswa, mahasiswa.nama_mahasiswa, (if(nilai_kelas.status_sync is null AND nilai_kelas.deleted_at is null, 'insert', if(nilai_kelas.status_sync is not null AND nilai_kelas.deleted_at is null and nilai_kelas.sync_at<nilai_kelas.updated_at, 'update', if(nilai_kelas.status_sync is not null and nilai_kelas.deleted_at is not null and nilai_kelas.sync_at<nilai_kelas.deleted_at,'delete', null)))) as set_sync 
            FROM nilai_kelas 
            LEFT JOIN peserta_kelas on peserta_kelas.id=nilai_kelas.id_nilai_kelas
            LEFT JOIN riwayat_pendidikan_mahasiswa on riwayat_pendidikan_mahasiswa.id=peserta_kelas.id_riwayat_pendidikan
            LEFT JOIN mahasiswa on mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa
            LEFT JOIN kelas_kuliah on kelas_kuliah.id=peserta_kelas.kelas_kuliah_id 
            WHERE if(nilai_kelas.status_sync is null AND nilai_kelas.deleted_at is null, 'insert', if(nilai_kelas.status_sync is not null AND nilai_kelas.deleted_at is null and nilai_kelas.sync_at<nilai_kelas.updated_at, 'update', if(nilai_kelas.status_sync is not null and nilai_kelas.deleted_at is not null and nilai_kelas.sync_at<nilai_kelas.deleted_at,'delete', null))) IS NOT NULL")->getResult();

            $array[] = [
                'index' => 5,
                'target' => 'nilai_peserta_kelas',
                'displayName' => 'Nilai Peserta Kelas',
                'data' => $data->nilai_peserta_kelas
            ];



            // Aktivitas Mahasiswa
            $object = new \App\Models\AktivitasMahasiswaModel();
            $data->aktivitas_mahasiswa = $object->select("aktivitas_mahasiswa.id, (if(id_aktivitas is null AND deleted_at is null, 'insert', if(id_aktivitas is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_aktivitas is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_aktivitas is null AND deleted_at is null, 'insert', if(id_aktivitas is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_aktivitas is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

            // Anggota Aktivitas Mahasiswa
            $object = new \App\Models\AnggotaAktivitasModel();
            $data->anggota_aktivitas_mahasiswa = $object->select("anggota_aktivitas.id, (if(id_anggota is null AND deleted_at is null, 'insert', if(id_anggota is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_anggota is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_anggota is null AND deleted_at is null, 'insert', if(id_anggota is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_anggota is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

            // Bimbing Mahasiswa
            $object = new \App\Models\BimbingMahasiswaModel();
            $data->bimbing_mahasiswa = $object->select("bimbing_mahasiswa.id, (if(id_bimbing_mahasiswa is null AND deleted_at is null, 'insert', if(id_bimbing_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_bimbing_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_bimbing_mahasiswa is null AND deleted_at is null, 'insert', if(id_bimbing_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_bimbing_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

            // Uji Mahasiswa
            $object = new \App\Models\UjiMahasiswaModel();
            $data->uji_mahasiswa = $object->select("uji_mahasiswa.id, (if(id_uji is null AND deleted_at is null, 'insert', if(id_uji is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_uji is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_uji is null AND deleted_at is null, 'insert', if(id_uji is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_uji is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

            // Mahasiswa Lulus DO
            $object = new \App\Models\MahasiswaLulusDOModel();
            $data->mahasiswa_lulus_do = $object->select("mahasiswa_lulus_do.id_riwayat_pendidikan, (if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

            // Mahasiswa Lulus DO
            $object = new \App\Models\TranskripModel();
            $data->transkrip = $object->select("transkrip.id, (if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

            // AKM
            $object = new \App\Models\PerkuliahanMahasiswaModel();
            $data->perkuliahan_mahasiswa = $object->select("perkuliahan_mahasiswa.id, mahasiswa_lulus_do.id_jenis_keluar, (if(perkuliahan_mahasiswa.sync_at is null AND perkuliahan_mahasiswa.deleted_at is null, 'insert', if(perkuliahan_mahasiswa.sync_at is not null AND perkuliahan_mahasiswa.deleted_at is null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at, 'update', if(perkuliahan_mahasiswa.sync_at is not null and perkuliahan_mahasiswa.deleted_at is not null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at,'delete', null)))) as set_sync")
                ->join('mahasiswa_lulus_do', 'mahasiswa_lulus_do.id_riwayat_pendidikan=perkuliahan_mahasiswa.id_riwayat_pendidikan', 'left')
                ->where("if(perkuliahan_mahasiswa.sync_at is null AND perkuliahan_mahasiswa.deleted_at is null, 'insert', if(perkuliahan_mahasiswa.sync_at is not null AND perkuliahan_mahasiswa.deleted_at is null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at, 'update', if(perkuliahan_mahasiswa.sync_at is not null and perkuliahan_mahasiswa.deleted_at is not null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at,'delete', null))) IS NOT NULL")
                ->where('id_jenis_keluar IS NULL')
                ->findAll();

            return $this->respond($array);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    function syncMahasiswa()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT mahasiswa.*, (if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync FROM mahasiswa WHERE if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->getResult();
            foreach ($data as $key => $value) {
                $item = [
                    'nama_mahasiswa' => $value->nama_mahasiswa,
                    'jenis_kelamin' => $value->jenis_kelamin,
                    'jalan' => $value->jalan,
                    'rt' => $value->rt,
                    'rw' => $value->rw,
                    'dusun' => $value->dusun,
                    'kelurahan' => $value->kelurahan,
                    'kode_pos' => $value->kode_pos,
                    'nisn' => $value->nisn,
                    'nik' => $value->nik,
                    'tempat_lahir' => $value->tempat_lahir,
                    'tanggal_lahir' => $value->tanggal_lahir,
                    'nama_ayah' => $value->nama_ayah,
                    'tanggal_lahir_ayah' => $value->tanggal_lahir_ayah,
                    'nik_ayah' => $value->nik_ayah,
                    'id_pendidikan_ayah' => $value->id_pendidikan_ayah,
                    'id_pekerjaan_ayah' => $value->id_pekerjaan_ayah,
                    'id_penghasilan_ayah' => $value->id_penghasilan_ayah,
                    'id_kebutuhan_khusus_ayah' => $value->id_kebutuhan_khusus_ayah,
                    'nama_ibu_kandung' => $value->nama_ibu_kandung,
                    'tanggal_lahir_ibu' => $value->tanggal_lahir_ibu,
                    'nik_ibu' => $value->nik_ibu,
                    'id_pendidikan_ibu' => $value->id_pendidikan_ibu,
                    'id_pekerjaan_ibu' => $value->id_pekerjaan_ibu,
                    'id_penghasilan_ibu' => $value->id_penghasilan_ibu,
                    'id_kebutuhan_khusus_ibu' => $value->id_kebutuhan_khusus_ibu,
                    'nama_wali' => $value->nama_wali,
                    'tanggal_lahir_wali' => $value->tanggal_lahir_wali,
                    'id_pendidikan_wali' => $value->id_pendidikan_wali,
                    'id_pekerjaan_wali' => $value->id_pekerjaan_wali,
                    'id_penghasilan_wali' => $value->id_penghasilan_wali,
                    'id_kebutuhan_khusus_mahasiswa' => $value->id_kebutuhan_khusus_mahasiswa,
                    'telepon' => $value->telepon,
                    'handphone' => $value->handphone,
                    'email' => $value->email,
                    'penerima_kps' => $value->penerima_kps,
                    'nomor_kps' => $value->nomor_kps,
                    'no_kps' => $value->no_kps,
                    'npwp' => $value->npwp,
                    'id_wilayah' => $value->id_wilayah,
                    'id_jenis_tinggal' => $value->id_jenis_tinggal,
                    'id_agama' => $value->id_agama,
                    'id_alat_transportasi' => $value->id_alat_transportasi,
                    'nama_wilayah' => $value->nama_wilayah,
                    'kewarganegaraan' => $value->kewarganegaraan
                ];
                if ($value->set_sync == 'insert') {
                    $setData = (object) $item;
                    $result = $this->api->insertData('InsertBiodataMahasiswa', $this->token, $setData);
                    if ($result->error_code == "0") {
                        $query = "UPDATE mahasiswa SET id_mahasiswa='" . $result->data->id_mahasiswa . "', sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else {
                        $item['error'] = $result;
                        $record['gagal'][] = $item;
                    }
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncHistoryPendidikan()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT riwayat_pendidikan_mahasiswa.*, (if(id_registrasi_mahasiswa is null AND deleted_at is null, 'insert', if(id_registrasi_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_registrasi_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync FROM riwayat_pendidikan_mahasiswa WHERE if(id_registrasi_mahasiswa is null AND deleted_at is null, 'insert', if(id_registrasi_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_registrasi_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL LIMIT 30")->getResult();
            foreach ($data as $key => $value) {
                $mahasiswa = $object->query("SELECT mahasiswa.id, mahasiswa.id_mahasiswa FROM mahasiswa WHERE mahasiswa.id='" . $value->id_mahasiswa . "'")->getRowObject();
                if ($value->set_sync == 'insert') {
                    $item = [
                        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                        'nim' => $value->nim,
                        'id_jenis_daftar' => $value->id_jenis_daftar,
                        'id_jalur_daftar' => $value->id_jalur_daftar,
                        'id_periode_masuk' => $value->id_periode_masuk,
                        'tanggal_daftar' => $value->tanggal_daftar,
                        'id_prodi' => $value->id_prodi,
                        'id_perguruan_tinggi' => $value->id_perguruan_tinggi,
                        'sks_diakui' => $value->sks_diakui,
                        'id_perguruan_tinggi_asal' => $value->id_perguruan_tinggi_asal,
                        'id_prodi_asal' => $value->id_prodi_asal,
                        'id_pembiayaan' => $value->id_pembiayaan,
                        'biaya_masuk' => $value->biaya_masuk,
                    ];
                    $setData = (object) $item;
                    $result = $this->api->insertData('InsertRiwayatPendidikanMahasiswa', $this->token, $setData);
                    if ($result->error_code == "0") {
                        $query = "UPDATE riwayat_pendidikan_mahasiswa SET id_registrasi_mahasiswa='" . $result->data->id_registrasi_mahasiswa . "', sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else {
                        $item['error'] = $result;
                        $record['gagal'][] = $item;
                    }
                } else if ($value->set_sync == 'update') {
                    $item = [
                        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                        'nim' => $value->nim,
                        'id_jenis_daftar' => $value->id_jenis_daftar,
                        'id_jalur_daftar' => $value->id_jalur_daftar,
                        'id_periode_masuk' => $value->id_periode_masuk,
                        'tanggal_daftar' => $value->tanggal_daftar,
                        'id_prodi' => $value->id_prodi,
                        'id_perguruan_tinggi' => $value->id_perguruan_tinggi,
                        'sks_diakui' => $value->sks_diakui,
                        'id_perguruan_tinggi_asal' => $value->id_perguruan_tinggi_asal,
                        'id_prodi_asal' => $value->id_prodi_asal,
                        'id_pembiayaan' => $value->id_pembiayaan,
                        'biaya_masuk' => $value->biaya_masuk,
                    ];
                    $setData = (object) $item;
                    $itemKey = [
                        'id_registrasi_mahasiswa' => $value->id_registrasi_mahasiswa
                    ];
                    $setKey = (object) $itemKey;
                    $result = $this->api->updateData('UpdateRiwayatPendidikanMahasiswa', $this->token, $setData, $setKey);
                    if ($result->error_code == "0") {
                        $query = "UPDATE riwayat_pendidikan_mahasiswa SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else {
                        $item['error'] = $result;
                        $record['gagal'][] = $item;
                    }
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncKelasKuliah()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        $semester = getSemesterAktif();
        try {
            $data = $object->query("SELECT kelas_kuliah.id, kelas_kuliah.id_kelas_kuliah,kelas_kuliah.id_prodi,matakuliah_id,kelas_kuliah.id_semester,kelas_kuliah.nama_semester,kelas_kuliah.nama_program_studi,kelas_kuliah.kode_mata_kuliah,kelas_kuliah.nama_mata_kuliah,kelas_kuliah.bahasan,kelas_kuliah.tanggal_mulai_efektif,kelas_kuliah.tanggal_akhir_efektif,kelas_kuliah.lingkup,kelas_kuliah.mode,kelas_kuliah.kapasitas, kelas.nama_kelas_kuliah, matakuliah.id_matkul, matakuliah.sks_mata_kuliah, matakuliah.sks_tatap_muka, matakuliah.sks_praktek, matakuliah.sks_praktek_lapangan, matakuliah.sks_simulasi, (if(kelas_kuliah.id_kelas_kuliah is null AND kelas_kuliah.deleted_at is null, 'insert', if(kelas_kuliah.id_kelas_kuliah is not null AND kelas_kuliah.deleted_at is null and kelas_kuliah.sync_at<kelas_kuliah.updated_at, 'update', if(kelas_kuliah.id_kelas_kuliah is not null and kelas_kuliah.deleted_at is not null and kelas_kuliah.sync_at<kelas_kuliah.updated_at,'delete', null)))) as set_sync 
            FROM kelas_kuliah 
            LEFT JOIN kelas on kelas.id=kelas_kuliah.kelas_id
            LEFT JOIN matakuliah on matakuliah.id=kelas_kuliah.matakuliah_id 
            WHERE if(kelas_kuliah.id_kelas_kuliah is null AND kelas_kuliah.deleted_at is null, 'insert', if(kelas_kuliah.id_kelas_kuliah is not null AND kelas_kuliah.deleted_at is null and kelas_kuliah.sync_at<kelas_kuliah.updated_at, 'update', if(kelas_kuliah.id_kelas_kuliah is not null and kelas_kuliah.deleted_at is not null and kelas_kuliah.sync_at<kelas_kuliah.updated_at,'delete', null))) IS NOT NULL LIMIT 37")->getResult();
            foreach ($data as $key => $value) {
                $item = [
                    'id_semester' => $value->id_semester,
                    'id_prodi' => $value->id_prodi,
                    'id_matkul' => $value->id_matkul,
                    'nama_kelas_kuliah' => $value->nama_kelas_kuliah,
                    'sks_mk' => $value->sks_mata_kuliah,
                    'sks_tm' => $value->sks_tatap_muka,
                    'sks_prak' => $value->sks_praktek,
                    'sks_prak_lap' => $value->sks_praktek_lapangan,
                    'sks_sim' => $value->sks_simulasi,
                    'bahasan' => $value->bahasan,
                    'tanggal_mulai_efektif' => $value->tanggal_mulai_efektif,
                    'tanggal_akhir_efektif' => $value->tanggal_akhir_efektif,
                    'lingkup' => $value->lingkup,
                    'mode' => $value->mode,
                    'kapasitas' => $value->kapasitas,
                ];
                if ($value->set_sync == 'insert') {
                    if ($value->nama_kelas_kuliah == 'A') {
                        $setData = (object) $item;
                        $result = $this->api->insertData('InsertKelasKuliah', $this->token, $setData);
                        if ($result->error_code == "0") {
                            $query = "UPDATE kelas_kuliah SET id_kelas_kuliah='" . $result->data->id_kelas_kuliah . "', sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                            $object->query($query);
                            $record['berhasil'][] = $item;
                        } else {
                            $record['gagal'][] = $item;
                        }
                    } else {
                        $itemKelas = $object->query("SELECT * FROM kelas_kuliah WHERE matakuliah_id = '" . $value->matakuliah_id . "' AND kelas_id='1' AND id_semester = '" . $semester->id_semester . "'")->getRow();
                        if (!is_null($itemKelas) && !is_null($itemKelas->id_kelas_kuliah)) {
                            $query = "UPDATE kelas_kuliah SET id_kelas_kuliah='" . $itemKelas->id_kelas_kuliah . "', sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                            $object->query($query);
                            $record['berhasil'][] = $item;
                        }
                    }
                } else if ($value->set_sync == 'update') {
                    if ($value->nama_kelas_kuliah == 'A') {
                        $setData = (object) $item;
                        $key = (object) ['id_kelas_kuliah' => $value->id_kelas_kuliah];
                        $result = $this->api->updateData('UpdateKelasKuliah', $this->token, $setData, $key);
                        if ($result->error_code == "0") {
                            $query = "UPDATE kelas_kuliah SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                            $object->query($query);
                            $record['berhasil'][] = $item;
                        } else {
                            $record['gagal'][] = $item;
                        }
                    } else {
                        $query = "UPDATE kelas_kuliah SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    }
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncDeleteKelasKuliah()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT * FROM kelas_kuliah WHERE id_semester = '20241' AND kelas_id != '1'")->getResult();
            foreach ($data as $key => $value) {
                $item = [
                    'id_kelas_kuliah' => $value->id_kelas_kuliah
                ];
                if (!is_null($value->id_kelas_kuliah)) {
                    $setData = (object) $item;
                    $result = $this->api->deleteData('DeleteKelasKuliah', $this->token, $setData);
                    if ($result->error_code == "0") {
                        $record['berhasil'][] = $value;
                    } else {
                        $record['gagal'][] = $item;
                    }
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncPengajarKelas()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT kelas_kuliah.id_kelas_kuliah, kelas_kuliah.matakuliah_id, matakuliah.nama_mata_kuliah, kelas_kuliah.kelas_id, dosen_pengajar_kelas.*, (if(id_aktivitas_mengajar is null AND dosen_pengajar_kelas.deleted_at is null, 'insert', if(id_aktivitas_mengajar is not null AND dosen_pengajar_kelas.deleted_at is null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at, 'update', if(id_aktivitas_mengajar is not null and dosen_pengajar_kelas.deleted_at is not null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at,'delete', null)))) as set_sync 
            FROM dosen_pengajar_kelas 
            LEFT JOIN penugasan_dosen on penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen
            LEFT JOIN dosen on dosen.id_dosen=penugasan_dosen.id_dosen 
            LEFT JOIN kelas_kuliah on kelas_kuliah.id=dosen_pengajar_kelas.kelas_kuliah_id 
            LEFT JOIN matakuliah on matakuliah.id = kelas_kuliah.matakuliah_id
            WHERE if(id_aktivitas_mengajar is null AND dosen_pengajar_kelas.deleted_at is null, 'insert', if(id_aktivitas_mengajar is not null AND dosen_pengajar_kelas.deleted_at is null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at, 'update', if(id_aktivitas_mengajar is not null and dosen_pengajar_kelas.deleted_at is not null and dosen_pengajar_kelas.sync_at<dosen_pengajar_kelas.updated_at,'delete', null))) IS NOT NULL AND dosen_pengajar_kelas.status_pengajar='Dosen' ORDER BY id_kelas_kuliah, kelas_id LIMIT 50")->getResult();
            foreach ($data as $key => $value) {
                $item = [
                    'id_registrasi_dosen' => $value->id_registrasi_dosen,
                    'id_kelas_kuliah' => $value->id_kelas_kuliah,
                    'id_substansi' => $value->id_substansi,
                    'sks_substansi_total' => $value->sks_substansi_total,
                    'rencana_minggu_pertemuan' => $value->rencana_minggu_pertemuan,
                    'realisasi_minggu_pertemuan' => $value->realisasi_minggu_pertemuan,
                    'id_jenis_evaluasi' => $value->id_jenis_evaluasi,
                ];
                if ($value->set_sync == 'insert') {
                    if ($value->kelas_id == '1') {
                        $setData = (object) $item;
                        $result = $this->api->insertData('InsertDosenPengajarKelasKuliah', $this->token, $setData);
                        if ($result->error_code == "0") {
                            $query = "UPDATE dosen_pengajar_kelas SET id_aktivitas_mengajar='" . $result->data->id_aktivitas_mengajar . "', sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                            $object->query($query);
                            $record['berhasil'][] = $item;
                        } else {
                            $record['gagal'][] = $item;
                        }
                    } else {
                        $itemKelas = $object->query("SELECT
                            `kelas_kuliah`.`id_kelas_kuliah`,
                            `dosen_pengajar_kelas`.`id_aktivitas_mengajar`
                            FROM
                            `kelas_kuliah`
                            LEFT JOIN `dosen_pengajar_kelas` ON `kelas_kuliah`.`id` =
                            `dosen_pengajar_kelas`.`kelas_kuliah_id` WHERE id_kelas_kuliah = '" . $value->id_kelas_kuliah . "' AND kelas_id='1'")->getRow();
                        if (!is_null($itemKelas) && !is_null($itemKelas->id_aktivitas_mengajar)) {
                            $query = "UPDATE dosen_pengajar_kelas SET id_aktivitas_mengajar='" . $itemKelas->id_aktivitas_mengajar . "', sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                            $object->query($query);
                            $record['berhasil'][] = $item;
                        } else {
                            $record['gagal'][] = $item;
                        }
                    }
                } else if ($value->set_sync == 'update') {
                    if ($value->kelas_id == '1') {
                        $setData = (object) $item;
                        $key = (object) ['id_aktivitas_mengajar' => $value->id_aktivitas_mengajar];
                        $result = $this->api->updateData('UpdateDosenPengajarKelasKuliah', $this->token, $setData, $key);
                        if ($result->error_code == "0") {
                            $query = "UPDATE dosen_pengajar_kelas SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                            $object->query($query);
                            $record['berhasil'][] = $item;
                        } else {
                            $record['gagal'][] = $item;
                        }
                    } else {
                        $itemKelas = $object->query("SELECT
                            `kelas_kuliah`.`id_kelas_kuliah`,
                            `dosen_pengajar_kelas`.`id_aktivitas_mengajar`
                            FROM
                            `kelas_kuliah`
                            LEFT JOIN `dosen_pengajar_kelas` ON `kelas_kuliah`.`id` =
                            `dosen_pengajar_kelas`.`kelas_kuliah_id` WHERE id_kelas_kuliah = '" . $value->id_kelas_kuliah . "' AND kelas_id='1'")->getRow();
                        if (!is_null($itemKelas) && !is_null($itemKelas->id_aktivitas_mengajar)) {
                            $query = "UPDATE dosen_pengajar_kelas SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                            $object->query($query);
                            $record['berhasil'][] = $item;
                        } else {
                            $record['gagal'][] = $item;
                        }
                    }
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncDeletePengajarKelas()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT
            `dosen_pengajar_kelas`.`id_aktivitas_mengajar`,
            `kelas_kuliah`.`kelas_id`,
            `dosen`.`nama_dosen`
            FROM
            `dosen_pengajar_kelas`
            LEFT JOIN `kelas_kuliah` ON `dosen_pengajar_kelas`.`kelas_kuliah_id` =
            `kelas_kuliah`.`id`
            LEFT JOIN `penugasan_dosen` ON `dosen_pengajar_kelas`.`id_registrasi_dosen` =
            `penugasan_dosen`.`id_registrasi_dosen`
            LEFT JOIN `dosen` ON `penugasan_dosen`.`id_dosen` = `dosen`.`id_dosen`
            WHERE id_semester='20241' AND kelas_id != '1' AND dosen.status='Dosen'")->getResult();

            foreach ($data as $key => $value) {
                $item = [
                    'id_aktivitas_mengajar' => $value->id_aktivitas_mengajar
                ];
                $setData = (object) $item;
                $result = $this->api->deleteData('DeleteDosenPengajarKelasKuliah', $this->token, $setData);
                if ($result->error_code == "0") {
                    $record['berhasil'][] = $value;
                } else {
                    $record['gagal'][] = $item;
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncPesertaKelas()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT peserta_kelas.*, kelas_kuliah.id_kelas_kuliah, riwayat_pendidikan_mahasiswa.id_registrasi_mahasiswa, mahasiswa.nama_mahasiswa, (if(peserta_kelas.status_sync is null AND peserta_kelas.deleted_at is null, 'insert', if(peserta_kelas.status_sync is not null AND peserta_kelas.deleted_at is null and peserta_kelas.sync_at<peserta_kelas.updated_at, 'update', if(peserta_kelas.status_sync is not null and peserta_kelas.deleted_at is not null and peserta_kelas.sync_at<peserta_kelas.deleted_at,'delete', null)))) as set_sync 
            FROM peserta_kelas 
            LEFT JOIN riwayat_pendidikan_mahasiswa on riwayat_pendidikan_mahasiswa.id=peserta_kelas.id_riwayat_pendidikan
            LEFT JOIN mahasiswa on mahasiswa.id=riwayat_pendidikan_mahasiswa.id_mahasiswa
            LEFT JOIN kelas_kuliah on kelas_kuliah.id=peserta_kelas.kelas_kuliah_id 
            WHERE if(peserta_kelas.status_sync is null AND peserta_kelas.deleted_at is null, 'insert', if(peserta_kelas.status_sync is not null AND peserta_kelas.deleted_at is null and peserta_kelas.sync_at<peserta_kelas.updated_at, 'update', if(peserta_kelas.status_sync is not null and peserta_kelas.deleted_at is not null and peserta_kelas.sync_at<peserta_kelas.deleted_at,'delete', null))) IS NOT NULL")->getResult();
            foreach ($data as $key => $value) {
                $item = [
                    'id_registrasi_mahasiswa' => $value->id_registrasi_mahasiswa,
                    'id_kelas_kuliah' => $value->id_kelas_kuliah
                ];
                if ($value->set_sync == 'insert') {
                    $setData = (object) $item;
                    $result = $this->api->insertData('InsertPesertaKelasKuliah', $this->token, $setData);
                    if ($result->error_code == "0" || $result->error_code == "119") {
                        $query = "UPDATE peserta_kelas SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else {
                        $value->error = $result;
                        $record['gagal'][] = $value;
                    }
                } else if ($value->set_sync == 'update') {
                    $query = "UPDATE peserta_kelas SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                    $object->query($query);
                    $record['berhasil'][] = $item;
                } else if ($value->set_sync == 'delete') {
                    $setData = (object) $item;
                    $result = $this->api->deleteData('DeletePesertaKelasKuliah', $this->token, $setData);
                    if ($result->error_code == "0") {
                        $query = "UPDATE peserta_kelas SET sync_at = null, status_sync=null WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else if ($result->error_code == "117" || $result->error_code == "112") {
                        $query = "UPDATE peserta_kelas SET sync_at = null, status_sync=null WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else {
                        $value->error = $result;
                        $record['gagal'][] = $value;
                    }
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncDeletePesertaKelas()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT
            `peserta_kelas`.*,
            `kelas_kuliah`.`id_kelas_kuliah`,
            `riwayat_pendidikan_mahasiswa`.`id_registrasi_mahasiswa`
            FROM
            `peserta_kelas`
            LEFT JOIN `kelas_kuliah` ON `peserta_kelas`.`kelas_kuliah_id` =
            `kelas_kuliah`.`id`
            LEFT JOIN `riwayat_pendidikan_mahasiswa`
            ON `peserta_kelas`.`id_riwayat_pendidikan` =
            `riwayat_pendidikan_mahasiswa`.`id`
            WHERE id_semester='20241' AND kelas_kuliah.kelas_id != '1' AND peserta_kelas.status_sync='sudah sync' AND peserta_kelas.sync_at IS NOT NULL LIMIT 500")->getResult();
            foreach ($data as $key => $value) {
                $item = [
                    'id_registrasi_mahasiswa' => $value->id_registrasi_mahasiswa,
                    'id_kelas_kuliah' => $value->id_kelas_kuliah
                ];
                $setData = (object) $item;
                $result = $this->api->deleteData('DeletePesertaKelasKuliah', $this->token, $setData);
                if ($result->error_code == "0") {
                    $query = "UPDATE peserta_kelas SET sync_at = null, status_sync=null WHERE id = '" . $value->id . "'";
                    $object->query($query);
                    $record['berhasil'][] = $item;
                } else {
                    $value->error = $result;
                    $record['gagal'][] = $value;
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncPerkuliahanMahasiswa()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $object->query("SELECT perkuliahan_mahasiswa.*, riwayat_pendidikan_mahasiswa.id_registrasi_mahasiswa, (if(perkuliahan_mahasiswa.sync_at is null AND perkuliahan_mahasiswa.deleted_at is null, 'insert', if(perkuliahan_mahasiswa.sync_at is not null AND perkuliahan_mahasiswa.deleted_at is null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at, 'update', if(perkuliahan_mahasiswa.sync_at is not null and perkuliahan_mahasiswa.deleted_at is not null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at,'delete', null)))) as set_sync
            FROM perkuliahan_mahasiswa 
            LEFT JOIN riwayat_pendidikan_mahasiswa on riwayat_pendidikan_mahasiswa.id=perkuliahan_mahasiswa.id_riwayat_pendidikan
            WHERE if(perkuliahan_mahasiswa.status_sync is null AND perkuliahan_mahasiswa.deleted_at is null, 'insert', if(perkuliahan_mahasiswa.status_sync is not null AND perkuliahan_mahasiswa.deleted_at is null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at, 'update', if(perkuliahan_mahasiswa.status_sync is not null and perkuliahan_mahasiswa.deleted_at is not null and perkuliahan_mahasiswa.sync_at<perkuliahan_mahasiswa.updated_at,'delete', null))) IS NOT NULL")->getResult();
            foreach ($data as $key => $value) {
                if ($value->set_sync == 'insert') {
                    $item = [
                        'id_registrasi_mahasiswa' => $value->id_registrasi_mahasiswa,
                        'id_semester' => $value->id_semester,
                        'id_status_mahasiswa' => $value->id_status_mahasiswa,
                        'ips' => $value->ips,
                        'ipk' => $value->ipk,
                        'sks_semester' => $value->sks_semester,
                        'total_sks' => $value->sks_total,
                        'biaya_kuliah_smt' => $value->biaya_kuliah_smt,
                        'id_pembiayaan' => $value->id_pembiayaan
                    ];
                    $setData = (object) $item;
                    $result = $this->api->insertData('InsertPerkuliahanMahasiswa', $this->token, $setData);
                    if ($result->error_code == "0" || $result->error_code == "1260") {
                        $query = "UPDATE perkuliahan_mahasiswa SET sync_at = '" . date('Y-m-d H:i:s') . "', updated_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else {
                        $value->error = $result;
                        $record['gagal'][] = $value;
                    }
                } else {
                    $item = [
                        'id_status_mahasiswa' => $value->id_status_mahasiswa,
                        'ips' => $value->ips,
                        'ipk' => $value->ipk,
                        'sks_semester' => $value->sks_semester,
                        'total_sks' => $value->sks_total,
                        'biaya_kuliah_smt' => $value->biaya_kuliah_smt,
                        'id_pembiayaan' => $value->id_pembiayaan
                    ];
                    $setData = (object) $item;
                    $itemKey = [
                        'id_registrasi_mahasiswa' => $value->id_registrasi_mahasiswa,
                        'id_semester' => $value->id_semester
                    ];
                    $setKey = (object) $itemKey;
                    $result = $this->api->updateData('UpdatePerkuliahanMahasiswa', $this->token, $setData, $setKey);
                    if ($result->error_code == "0") {
                        $query = "UPDATE perkuliahan_mahasiswa SET sync_at = '" . date('Y-m-d H:i:s') . "', updated_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id = '" . $value->id . "'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
                    } else {
                        $value->error = $result;
                        $record['gagal'][] = $value;
                    }
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }

    function syncNilaiPesertaKelas()
    {
        $object = \Config\Database::connect();
        $record = ['berhasil' => [], 'gagal' => []];
        try {
            $data = $this->request->getJSON();
            foreach ($data as $key => $value) {
                $key = (object) [
                    'id_registrasi_mahasiswa' => $value->id_registrasi_mahasiswa,
                    'id_kelas_kuliah' => $value->id_kelas_kuliah
                ];
                $setData = (object) [
                    'nilai_angka' => $value->nilai_angka,
                    'nilai_huruf' => $value->nilai_huruf,
                    'nilai_indeks' => $value->nilai_indeks
                ];
                $result = $this->api->updateData('UpdateNilaiPerkuliahanKelas', $this->token, $setData, $key);
                if ($result->error_code == "0") {
                    $query = "UPDATE nilai_kelas SET sync_at = '" . date('Y-m-d H:i:s') . "', status_sync='sudah sync' WHERE id_nilai_kelas = '" . $value->id_nilai_kelas . "'";
                    $object->query($query);
                    $record['berhasil'][] = $key;
                } else {
                    $key->error = $result;
                    $record['gagal'][] = $key;
                }
            }
            return $this->respond($record);
        } catch (\Throwable $th) {
            return $this->fail($record);
        }
    }
}

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
        $this->api = new Rest();
        $this->token = $this->api->getToken()->data->token;
    }
    public function getSync(): object
    {
        $conn = \Config\Database::connect();
        $semester = getSemesterAktif();
        $data = new stdClass;

        // Mahasiswa
        $object = new \App\Models\MahasiswaModel();
        $data->mahasiswa = $object->select("mahasiswa.id, (if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

        // History
        $object = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $data->riwayat_pendidikan = $object->select("riwayat_pendidikan_mahasiswa.id, (if(id_registrasi_mahasiswa is null AND deleted_at is null, 'insert', if(id_registrasi_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_registrasi_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_registrasi_mahasiswa is null AND deleted_at is null, 'insert', if(id_registrasi_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_registrasi_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();
        // Nilai Transfer
        $object = new \App\Models\NilaiTransferModel();
        $data->nilai_transfer = $object->select("nilai_transfer.id, (if(id_transfer is null AND deleted_at is null, 'insert', if(id_transfer is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_transfer is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_transfer is null AND deleted_at is null, 'insert', if(id_transfer is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_transfer is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

        // Kelas Kuliah
        $object = new \App\Models\KelasKuliahModel();
        $data->kelas_kuliah = $object->select("kelas_kuliah.id, kelas_kuliah.id_kelas_kuliah,kelas_kuliah.id_prodi,matakuliah_id,kelas_kuliah.id_semester,kelas_kuliah.nama_semester,kelas_kuliah.nama_program_studi,kelas_kuliah.kode_mata_kuliah,kelas_kuliah.nama_mata_kuliah,kelas_kuliah.bahasan,kelas_kuliah.tanggal_mulai_efektif,kelas_kuliah.tanggal_akhir_efektif,kelas_kuliah.lingkup,kelas_kuliah.mode,kelas_kuliah.kapasitas, (if(kelas_kuliah.id_kelas_kuliah is null AND deleted_at is null, 'insert', if(kelas_kuliah.id_kelas_kuliah is not null AND deleted_at is null and sync_at<updated_at, 'update', if(kelas_kuliah.id_kelas_kuliah is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(kelas_kuliah.id_kelas_kuliah is null AND deleted_at is null, 'insert', if(kelas_kuliah.id_kelas_kuliah is not null AND deleted_at is null and sync_at<updated_at, 'update', if(kelas_kuliah.id_kelas_kuliah is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")
            ->findAll();

        // Peserta Kelas
        $object = new \App\Models\PesertaKelasModel();
        $data->peserta_kelas = $object->select("peserta_kelas.id, (if(status_sync is null AND deleted_at is null, 'insert', if(status_sync is not null AND deleted_at is null and sync_at<updated_at, 'update', if(status_sync is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(status_sync is null AND deleted_at is null, 'insert', if(status_sync is not null AND deleted_at is null and sync_at<updated_at, 'update', if(status_sync is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

        // Dosen Pengajar Kelas
        $object = new \App\Models\DosenPengajarKelasModel();
        $data->dosen_pengajar_kelas = $object->select("dosen_pengajar_kelas.id, (if(id_aktivitas_mengajar is null AND deleted_at is null, 'insert', if(id_aktivitas_mengajar is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_aktivitas_mengajar is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_aktivitas_mengajar is null AND deleted_at is null, 'insert', if(id_aktivitas_mengajar is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_aktivitas_mengajar is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

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
        $data->perkuliahan_mahasiswa = $object->select("perkuliahan_mahasiswa.id, (if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

        return $this->respond($data);
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
                        $query = "UPDATE mahasiswa SET id_mahasiswa='" . $result->data->id_mahasiswa . "', sync_at = '" . date('Y-m-d') . "', status_sync='sudah sync' WHERE id = '".$value->id."'";
                        $object->query($query);
                        $record['berhasil'][] = $item;
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
}

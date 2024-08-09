<?php

namespace App\Controllers;

use stdClass;

class Sync extends BaseController
{
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
        $data->kelas_kuliah = $object->select("kelas_kuliah.id, (if(id_kelas_kuliah is null AND deleted_at is null, 'insert', if(id_kelas_kuliah is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_kelas_kuliah is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(id_kelas_kuliah is null AND deleted_at is null, 'insert', if(id_kelas_kuliah is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_kelas_kuliah is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->where('id_semester', $semester->id_semester)->findAll();

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
        $data->mahasiswa_lulus_do = $object->select("mahasiswa_lulus_do.id, (if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

        // Mahasiswa Lulus DO
        $object = new \App\Models\TranskripModel();
        $data->transkrip = $object->select("transkrip.id, (if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

        // AKM
        $object = new \App\Models\PerkuliahanMahasiswaModel();
        $data->perkuliahan_mahasiswa = $object->select("perkuliahan_mahasiswa.id, (if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync")->where("if(sync_at is null AND deleted_at is null, 'insert', if(sync_at is not null AND deleted_at is null and sync_at<updated_at, 'update', if(sync_at is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->findAll();

        return $this->respond($data);
    }
}

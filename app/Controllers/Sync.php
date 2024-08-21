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
        $data = $object->query("SELECT mahasiswa.*, (if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null)))) as set_sync FROM mahasiswa WHERE if(id_mahasiswa is null AND deleted_at is null, 'insert', if(id_mahasiswa is not null AND deleted_at is null and sync_at<updated_at, 'update', if(id_mahasiswa is not null and deleted_at is not null and sync_at<updated_at,'delete', null))) IS NOT NULL")->getResult();
        foreach ($data as $key => $value) {
            $item = [
                'nama_mahasiswa' => $value->nama_mahasiswa,
                'jenis_kelamin' => $value->jenis_kelamin,
                'jalan' => $value->jalan,
                'rt' => $value->,
                'rw' => $value->,
                'dusun' => $value->,
                'kelurahan' => $value->,
                'kode_pos' => $value->,
                'nisn' => $value->,
                'nik' => $value->,
                'tempat_lahir' => $value->,
                'tanggal_lahir' => $value->,
                'nama_ayah' => $value->,
                'tanggal_lahir_ayah' => $value->,
                'nik_ayah' => $value->,
                'id_pendidikan_ayah' => $value->,
                'id_pekerjaan_ayah' => $value->,
                'id_penghasilan_ayah' => $value->,
                'id_kebutuhan_khusus_ayah' => $value->,
                'nama_ibu_kandung' => $value->,
                'tanggal_lahir_ibu' => $value->,
                'nik_ibu' => $value->,
                'id_pendidikan_ibu' => $value->,
                'id_pekerjaan_ibu' => $value->,
                'id_penghasilan_ibu' => $value->,
                'id_kebutuhan_khusus_ibu' => $value->,
                'nama_wali' => $value->,
                'tanggal_lahir_wali' => $value->,
                'id_pendidikan_wali' => $value->,
                'id_pekerjaan_wali' => $value->,
                'id_penghasilan_wali' => $value->,
                'id_kebutuhan_khusus_mahasiswa' => $value->,
                'telepon' => $value->,
                'handphone' => $value->,
                'email' => $value->,
                'penerima_kps' => $value->,
                'nomor_kps' => $value->,
                'no_kps' => $value->,
                'npwp' => $value->,
                'id_wilayah' => $value->,
                'id_jenis_tinggal' => $value->,
                'nama_jenis_tinggal' => $value->,
                'id_agama' => $value->,
                'nama_agama' => $value->,
                'id_alat_transportasi' => $value->,
                'nama_alat_transportasi' => $value->,
                'nama_wilayah' => $value->,
                'kewarganegaraan' => $value->,
                'nama_pendidikan_ayah' => $value->,
                'nama_pendidikan_ibu' => $value->,
                'nama_pendidikan_wali' => $value->,
                'nama_pekerjaan_ayah' => $value->,
                'nama_pekerjaan_ibu' => $value->,
                'nama_pekerjaan_wali' => $value->,
                'nama_penghasilan_ayah' => $value->,
                'nama_penghasilan_ibu' => $value->,
                'nama_penghasilan_wali' => $value->,
                'nama_kebutuhan_khusus_ayah' => $value->,
                'nama_kebutuhan_khusus_ibu' => $value->,
                'nama_kebutuhan_khusus_wali' => $value->,
                'nama_kebutuhan_khusus_mahasiswa' => $value->,
            ];
        }
        return $this->respond($data);
    }
}

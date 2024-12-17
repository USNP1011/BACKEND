<?php

namespace App\Controllers\Rest\Dosen;

use CodeIgniter\RESTful\ResourceController;

class Dosen extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }

    public function profile($id = null)
    {
        return $this->respond([
            'status' => true,
            'data' => getProfile()
        ]);
    }

    public function jadwalMengajar()
    {
        $object = new \App\Models\KelasKuliahModel();
        $profile = getProfile();
        $semester = getSemesterAktif();
        if ($profile->status == 'Dosen') {
            $data = $object->select("kelas_kuliah.id, kelas_kuliah.matakuliah_id, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai, ruangan.nama_ruangan, kelas.nama_kelas_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah_kurikulum.semester, prodi.id_prodi, prodi.nama_program_studi, (SELECT COUNT(id) FROM peserta_kelas WHERE kelas_kuliah_id=kelas_kuliah.id) as total_peserta, (SELECT COUNT(*) FROM nilai_kelas LEFT JOIN peserta_kelas on nilai_kelas.id_nilai_kelas=peserta_kelas.id WHERE peserta_kelas.kelas_kuliah_id=kelas_kuliah.id AND nilai_kelas.nilai_indeks IS NOT NULL) as peserta_dinilai")
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi=kelas_kuliah.id_prodi', 'left')
                ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id=matakuliah.id', 'left')
                ->join('kelas', 'kelas.id=kelas_kuliah.kelas_id', 'left')
                ->join('ruangan', 'ruangan.id=kelas_kuliah.ruangan_id', 'left')
                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                ->join('penugasan_dosen', 'penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen', 'left')
                ->join('dosen', 'dosen.id_dosen=penugasan_dosen.id_dosen', 'left')
                ->where('kelas_kuliah.id_semester', $semester->id_semester)
                ->where("dosen_pengajar_kelas.mengajar", '1')
                ->where('dosen.id_dosen', $profile->id_dosen)->findAll();
        } else {
            $data = $object->select("kelas_kuliah.id, kelas_kuliah.matakuliah_id, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai, ruangan.nama_ruangan, kelas.nama_kelas_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah_kurikulum.semester, prodi.id_prodi, prodi.nama_program_studi, (SELECT COUNT(id) FROM peserta_kelas WHERE kelas_kuliah_id=kelas_kuliah.id) as total_peserta")
                ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
                ->join('prodi', 'prodi.id_prodi=kelas_kuliah.id_prodi', 'left')
                ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id=matakuliah.id', 'left')
                ->join('kelas', 'kelas.id=kelas_kuliah.kelas_id', 'left')
                ->join('ruangan', 'ruangan.id=kelas_kuliah.ruangan_id', 'left')
                ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
                ->join('dosen', 'dosen.id_dosen=dosen_pengajar_kelas.id_dosen', 'left')
                ->where('kelas_kuliah.id_semester', $semester->id_semester)
                ->where("dosen_pengajar_kelas.mengajar", '1')
                ->where('dosen.id_dosen', $profile->id_dosen)->findAll();
        }
        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }

    public function pesertaKelas($id = null)
    {
        $object = new \App\Models\KelasKuliahModel();
        $profile = getProfile();
        $semester = getSemesterAktif();
        $data = $object->select("kelas_kuliah.id, kelas_kuliah.matakuliah_id, kelas_kuliah.hari, kelas_kuliah.jam_mulai, kelas_kuliah.jam_selesai, ruangan.nama_ruangan, kelas.nama_kelas_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, matakuliah.kode_mata_kuliah, matakuliah_kurikulum.semester, prodi.id_prodi, prodi.nama_program_studi")
            ->join('matakuliah', 'matakuliah.id=kelas_kuliah.matakuliah_id', 'left')
            ->join('prodi', 'prodi.id_prodi=kelas_kuliah.id_prodi', 'left')
            ->join('matakuliah_kurikulum', 'matakuliah_kurikulum.matakuliah_id=matakuliah.id', 'left')
            ->join('kelas', 'kelas.id=kelas_kuliah.kelas_id', 'left')
            ->join('ruangan', 'ruangan.id=kelas_kuliah.ruangan_id', 'left')
            ->join('dosen_pengajar_kelas', 'dosen_pengajar_kelas.kelas_kuliah_id=kelas_kuliah.id', 'left')
            ->join('penugasan_dosen', 'penugasan_dosen.id_registrasi_dosen=dosen_pengajar_kelas.id_registrasi_dosen', 'left')
            ->join('dosen', 'dosen.id_dosen=penugasan_dosen.id_dosen', 'left')
            ->where('kelas_kuliah.id', $id)
            ->first();

        // Mengambil peserta kelas
        $object = new \App\Models\PesertaKelasModel();
        $data->pesertaKelas = $object->select("peserta_kelas.id, riwayat_pendidikan_mahasiswa.nim, mahasiswa.nama_mahasiswa")
            ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=peserta_kelas.id_riwayat_pendidikan', 'left')
            ->join('mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
            ->where('kelas_kuliah_id', $data->id)
            ->findAll();
        return $this->respond([
            'status' => true,
            'data' => $data
        ]);
    }
}

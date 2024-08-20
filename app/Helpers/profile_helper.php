<?php

function getProfile()
{
    helper('request');
    $authenticationHeader = request()->getServer('HTTP_AUTHORIZATION');
    $encodedToken = getJWTFromRequest($authenticationHeader);
    $data = encodeJWTFromRequest($encodedToken);
    $mhs = false;
    $prodi = false;
    foreach ($data->roles as $key => $value) {
        if ($value->role == "Mahasiswa") {
            $mhs = true;
        }
        if ($value->role == "Prodi") {
            $prodi = true;
        }
    }
    if ($mhs) {
        $object = new \App\Models\MahasiswaModel();
        $profile = $object
            ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.id as id_riwayat_pendidikan, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.id_prodi, prodi.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan, wilayah.nama_wilayah, agama.nama_agama, prodi.nama_program_studi, jenis_transportasi.nama_alat_transportasi, (SELECT dosen.nama_dosen FROM dosen_wali LEFT JOIN dosen on dosen.id_dosen = dosen_wali.id_dosen WHERE dosen_wali.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id LIMIT 1) as dosen_wali")
            ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
            ->join('wilayah', 'wilayah.id_wilayah=mahasiswa.id_wilayah', 'left')
            ->join("agama", "agama.id_agama=mahasiswa.id_agama", "LEFT")
            ->join("prodi", "riwayat_pendidikan_mahasiswa.id_prodi=prodi.id_prodi", "LEFT")
            ->join("jenis_transportasi", "jenis_transportasi.id_alat_transportasi=mahasiswa.id_alat_transportasi", "LEFT")
            ->where('id_user', $data->uid)->first();
    } else {
        $object = new \App\Models\DosenModel();
        $profile = $object->select("dosen.*, prodi.id_prodi, prodi.nama_program_studi")
            ->join('penugasan_dosen', 'penugasan_dosen.id_dosen=dosen.id_dosen', 'left')
            ->join('prodi', 'penugasan_dosen.id_prodi=prodi.id_prodi', 'left')
            ->where('id_user', $data->uid)->first();
        if ($prodi) {
            $kaprodi = new \App\Models\KaprodiModel();
            $profile->kaprodi = $kaprodi->select('kaprodi.id, kaprodi.id_prodi, prodi.nama_program_studi, kaprodi.no_sk, kaprodi.tanggal_sk')
                ->join('prodi', 'prodi.id_prodi=kaprodi.id_prodi', 'left')
                ->where('id_dosen', $profile->id_dosen)->first();
        }
    }
    return $profile;
}

function getProfileByMahasiswa($id)
{
    $object = new \App\Models\MahasiswaModel();
    $profile = $object
        ->select("mahasiswa.*, riwayat_pendidikan_mahasiswa.id as id_riwayat_pendidikan, riwayat_pendidikan_mahasiswa.nim, riwayat_pendidikan_mahasiswa.id_prodi, prodi.nama_program_studi, riwayat_pendidikan_mahasiswa.angkatan, wilayah.nama_wilayah, agama.nama_agama, prodi.nama_program_studi, jenis_transportasi.nama_alat_transportasi, (SELECT dosen.nama_dosen FROM dosen_wali LEFT JOIN dosen on dosen.id_dosen = dosen_wali.id_dosen WHERE dosen_wali.id_riwayat_pendidikan = riwayat_pendidikan_mahasiswa.id LIMIT 1) as dosen_wali, (SELECT dosen.nama_dosen FROM kaprodi LEFT JOIN dosen on dosen.id_dosen = kaprodi.id_dosen WHERE kaprodi.id_prodi = prodi.id_prodi LIMIT 1) as nama_kaprodi")
        ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id_mahasiswa=mahasiswa.id', 'left')
        ->join('wilayah', 'wilayah.id_wilayah=mahasiswa.id_wilayah', 'left')
        ->join("agama", "agama.id_agama=mahasiswa.id_agama", "LEFT")
        ->join("prodi", "riwayat_pendidikan_mahasiswa.id_prodi=prodi.id_prodi", "LEFT")
        ->join("jenis_transportasi", "jenis_transportasi.id_alat_transportasi=mahasiswa.id_alat_transportasi", "LEFT")
        ->where('mahasiswa.id', $id)->first();
    return $profile;
}

function checkMahasiswa($array) {
    foreach ($array as $key => $value) {
        if($value->role=="Mahasiswa") return true;
    }
    return false;
}

function getProfileProdi()
{
    helper('request');
    $authenticationHeader = request()->getServer('HTTP_AUTHORIZATION');
    $encodedToken = getJWTFromRequest($authenticationHeader);
    $data = encodeJWTFromRequest($encodedToken);
    $object = new \App\Models\DosenModel();
    $profile = $object->where('id_user', $data->uid)->first();
    $kaprodi = new \App\Models\KaprodiModel();
    $profile->kaprodi = $kaprodi->select('kaprodi.id, kaprodi.id_prodi, prodi.nama_program_studi, prodi.kode_program_studi, kaprodi.no_sk, kaprodi.tanggal_sk')
        ->join('prodi', 'prodi.id_prodi=kaprodi.id_prodi', 'left')
        ->where('id_dosen', $profile->id_dosen)->first();
    $profile->nama_program_studi = $profile->kaprodi->nama_program_studi;
    $profile->kode_program_studi = $profile->kaprodi->kode_program_studi;
    $profile->id_prodi = $profile->kaprodi->id_prodi;
    return $profile;
}

function getKaprodi($id_prodi = null) {
    $object = new \App\Models\KaprodiModel();
    return $object->select('kaprodi.*, dosen.nama_dosen')->join('dosen', 'dosen.id_dosen=kaprodi.id_dosen', 'left')->where('kaprodi.id_prodi', $id_prodi)->first();
}

function getDosenByUser($id_user = null) {
    $object = new \App\Models\DosenModel();
    return $object->where('id_user', $id_user)->first();
}
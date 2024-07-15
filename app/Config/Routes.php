<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->set404Override('App\Controllers\Errors::show404');
$routes->get('/', 'Home::index');

service('auth')->routes($routes);
$routes->group('pt', static function($routes){
    $routes->get('read', 'PerguruanTinggi::index');
});

$routes->group('auth', static function($routes){
    $routes->post('login', 'Auth::login');
});

$routes->group('get_data', static function($routes){
    $routes->get('/', 'GetData::index');
    $routes->get('profile_pt', 'GetData::profile_pt');
    $routes->get('agama', 'GetData::agama');
    $routes->get('mahasiswa', 'GetData::mahasiswa');
    $routes->get('prodi', 'GetData::prodi');
    $routes->get('periode', 'GetData::periode');
    $routes->get('riwayat_pendidikan', 'GetData::riwayat_pendidikan');
    $routes->get('ta', 'GetData::ta');
    $routes->get('semester', 'GetData::semester');
    $routes->get('kurikulum', 'GetData::kurikulum');
    $routes->get('matakuliah', 'GetData::matakuliah');
    $routes->get('kurikulum_detail', 'GetData::kurikulum_detail');
    $routes->get('kelas_kuliah', 'GetData::kelas_kuliah');
    $routes->get('dosen', 'GetData::dosen');
    $routes->get('penugasan_dosen', 'GetData::penugasan_dosen');
    $routes->get('jenis_evaluasi', 'GetData::jenis_evaluasi');
    $routes->get('pengajar_kelas', 'GetData::pengajar_kelas');
    $routes->get('jenis_tinggal', 'GetData::jenis_tinggal');
    $routes->get('jenis_keluar', 'GetData::jenis_keluar');
    $routes->get('pembiayaan', 'GetData::pembiayaan');
    $routes->get('GetJenisSertifikasi', 'GetData::GetJenisSertifikasi');
    $routes->get('jenis_pendaftaran', 'GetData::jenis_pendaftaran');
    $routes->get('jenis_sms', 'GetData::jenis_sms');
    $routes->get('bentuk_pendidikan', 'GetData::bentuk_pendidikan');
    $routes->get('jalur_masuk', 'GetData::jalur_masuk');
    $routes->get('transportasi', 'GetData::transportasi');
    $routes->get('nilai_transfer', 'GetData::nilai_transfer');
    $routes->get('jenis_substansi', 'GetData::jenis_substansi');
    $routes->get('jenjang_pendidikan', 'GetData::jenjang_pendidikan');
    $routes->get('kategori_kegiatan', 'GetData::kategori_kegiatan');
    $routes->get('kebutuhan_khusus', 'GetData::kebutuhan_khusus');
    $routes->get('lembaga_pangkat', 'GetData::lembaga_pangkat');
    $routes->get('level_wilayah', 'GetData::level_wilayah');
    $routes->get('nagara', 'GetData::nagara');
    $routes->get('pekerjaan', 'GetData::pekerjaan');
    $routes->get('penghasilan', 'GetData::penghasilan');
    $routes->get('status_mahasiswa', 'GetData::status_mahasiswa');
    $routes->get('wilayah', 'GetData::wilayah');
    $routes->get('skala_nilai', 'GetData::skala_nilai');
    $routes->get('jenis_aktivitas', 'GetData::jenis_aktivitas');
    $routes->get('aktivitas_kuliah', 'GetData::aktivitas_kuliah');
    $routes->get('aktivitas_mahasiswa', 'GetData::aktivitas_mahasiswa');
    $routes->get('anggota_aktivitas_mahasiswa', 'GetData::anggota_aktivitas_mahasiswa');
    $routes->get('bimbing_mahasiswa', 'GetData::bimbing_mahasiswa');
    $routes->get('peserta_kelas', 'GetData::peserta_kelas');
    $routes->get('nilai_kelas', 'GetData::nilai_kelas');
    $routes->get('dosen_wali', 'GetData::dosenWali');
});

$routes->group('api', ['namespace'=> 'App\Controllers\Api'], static function($routes){
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->get('perguruanTinggi', 'Referensi\PerguruanTinggi::store', ['filter' => 'auth']);
    $routes->get('prodi', 'Referensi\Prodi::store', ['filter' => 'auth']);
    $routes->get('agama', 'Referensi\Agama::store', ['filter' => 'auth']);
    $routes->get('penghasilan', 'Referensi\Penghasilan::store', ['filter' => 'auth']);
    $routes->get('jenis_evaluasi', 'Referensi\JenisEvaluasi::store', ['filter' => 'auth']);
    $routes->get('jenis_tinggal', 'Referensi\JenisTinggal::store', ['filter' => 'auth']);
    $routes->get('jenis_keluar', 'Referensi\jenisKeluar::store', ['filter' => 'auth']);
    $routes->get('jenis_sertifikasi', 'Referensi\JenisSertifikasi::store', ['filter' => 'auth']);
    $routes->get('jenis_pendaftaran', 'Referensi\JenisPendaftaran::store', ['filter' => 'auth']);
    $routes->get('jenis_sms', 'Referensi\JenisSMS::store', ['filter' => 'auth']);
    $routes->get('jenis_peran', 'Referensi\JenisPeran::store', ['filter' => 'auth']);
    $routes->get('bentuk_pendidikan', 'Referensi\BentukPendidikan::store', ['filter' => 'auth']);
    $routes->get('jalur_masuk', 'Referensi\JalurMasuk::store', ['filter' => 'auth']);
    $routes->get('transportasi', 'Referensi\Transportasi::store', ['filter' => 'auth']);
    $routes->get('jenis_substansi', 'Referensi\JenisSubstansi::store', ['filter' => 'auth']);
    $routes->get('jenjang_pendidikan', 'Referensi\JenjangPendidikan::store', ['filter' => 'auth']);
    $routes->get('kebutuhan_khusus', 'Referensi\KebutuhanKhusus::store', ['filter' => 'auth']);
    $routes->get('kategori_kegiatan/(:hash)', 'Referensi\KategoriKegiatan::store/$1', ['filter' => 'auth']);
    $routes->get('lembaga_pangkat', 'Referensi\LembagaPangkat::store', ['filter' => 'auth']);
    $routes->get('level_wilayah', 'Referensi\LevelWilayah::store', ['filter' => 'auth']);
    $routes->get('negara', 'Referensi\Negara::store', ['filter' => 'auth']);
    $routes->get('pekerjaan', 'Referensi\Pekerjaan::store', ['filter' => 'auth']);
    $routes->get('status_mahasiswa', 'Referensi\StatusMahasiswa::store', ['filter' => 'auth']);
    $routes->get('wilayah', 'Referensi\Wilayah::store', ['filter' => 'auth']);
    $routes->get('get_wilayah/(:any)/(:any)/(:any)', 'Referensi\Wilayah::by_id/$1/$2/$3', ['filter' => 'auth']);
    $routes->get('jenis_aktivitas', 'Referensi\JenisAktivitas::store', ['filter' => 'auth']);
    $routes->get('pengajar_kelas', 'Referensi\PengajarKelas::store', ['filter' => 'auth']);
    $routes->get('dosen', 'Referensi\Dosen::store', ['filter' => 'auth']);
    $routes->get('dosen/(:any)', 'Referensi\Dosen::store/$1', ['filter' => 'auth']);

    $routes->group('kelas', function($routes){
        $routes->get('', 'Referensi\Kelas::store');
        $routes->get('(:hash)', 'Referensi\Kelas::store/$1');
        $routes->post('', 'Referensi\Kelas::create', ['filter' => 'auth']);
        $routes->put('', 'Referensi\Kelas::update', ['filter' => 'auth']);
        $routes->delete('(:hash)', 'Referensi\Kelas::delete/$1', ['filter' => 'auth']);
    });

    $routes->group('ruangan', function($routes){
        $routes->get('', 'Referensi\Ruangan::store');
        $routes->get('(:hash)', 'Referensi\Ruangan::store/$1');
        $routes->post('', 'Referensi\Ruangan::create', ['filter' => 'auth']);
        $routes->put('', 'Referensi\Ruangan::update', ['filter' => 'auth']);
        $routes->delete('(:hash)', 'Referensi\Ruangan::delete/$1', ['filter' => 'auth']);
    });

    $routes->get('penugasan_dosen', 'Referensi\PenugasanDosen::store', ['filter' => 'auth']);
    $routes->get('penugasan_dosen/(:hash)', 'Referensi\PenugasanDosen::store/$1', ['filter' => 'auth']);
    
    $routes->get('skala_nilai', 'Referensi\SkalaNilai::store', ['filter' => 'auth']);
    $routes->get('skala_nilai/(:hash)', 'Referensi\SkalaNilai::store/$1', ['filter' => 'auth']);

    $routes->get('ta', 'Referensi\TahunAjaran::store', ['filter' => 'auth']);
    $routes->get('ta/(:any)', 'Referensi\TahunAjaran::store/$1', ['filter' => 'auth']);

    $routes->get('semester', 'Referensi\Semester::store', ['filter' => 'auth']);
    $routes->get('semester/(:any)', 'Referensi\Semester::store/$1', ['filter' => 'auth']);
    $routes->put('semester', 'Referensi\Semester::update', ['filter' => 'auth']);


    // $routes->group('mahasiswa', function($routes){
    //     $routes->get('', 'Mahasiswa::show', ['filter' => 'auth']);
    //     $routes->get('(:hash)', 'Mahasiswa::show/$1', ['filter' => 'auth']);
    //     $routes->get('(:hash)/riwayat_pendidikan', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    //     $routes->get('(:hash)/nilai_transfer', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    //     $routes->get('(:hash)/krsm', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    //     $routes->get('(:hash)/aktivitas_kuliah', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    //     $routes->post('mahasiswa_paginate', 'Mahasiswa::paginate', ['filter' => 'auth']);
    //     $routes->post('', 'Mahasiswa::create', ['filter' => 'auth']);
    //     $routes->put('', 'Mahasiswa::update', ['filter' => 'auth']);
    //     $routes->delete('(:hash)', 'Mahasiswa::delete/$1', ['filter' => 'auth']);
    // });
    
    $routes->get('mahasiswa', 'Mahasiswa::show', ['filter' => 'auth']);
    $routes->get('mahasiswa/(:hash)', 'Mahasiswa::show/$1', ['filter' => 'auth']);
    $routes->get('mahasiswa/(:any)/riwayat_pendidikan', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    $routes->get('mahasiswa/(:any)/nilai_transfer', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    $routes->get('mahasiswa/(:any)/krsm', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    $routes->get('mahasiswa/(:any)/aktivitas_kuliah', 'Mahasiswa::show/$1/$2', ['filter' => 'auth']);
    $routes->post('mahasiswa_paginate', 'Mahasiswa::paginate', ['filter' => 'auth']);
    $routes->post('mahasiswa', 'Mahasiswa::create', ['filter' => 'auth']);
    $routes->put('mahasiswa', 'Mahasiswa::update', ['filter' => 'auth']);
    $routes->delete('mahasiswa/(:any)', 'Mahasiswa::delete/$1', ['filter' => 'auth']);

    $routes->get('matakuliah', 'Matakuliah::show', ['filter' => 'auth']);
    $routes->get('matakuliah/(:hash)', 'Matakuliah::show/$1', ['filter' => 'auth']);
    $routes->get('matakuliah/byprodi/(:hash)', 'Matakuliah::by_prodi/$1', ['filter' => 'auth']);
    $routes->post('matakuliah_paginate', 'Matakuliah::paginate', ['filter' => 'auth']);
    $routes->post('matakuliah', 'Matakuliah::create', ['filter' => 'auth']);
    $routes->put('matakuliah', 'Matakuliah::update', ['filter' => 'auth']);
    $routes->delete('matakuliah/(:any)', 'Matakuliah::delete/$1', ['filter' => 'auth']);

    $routes->get('riwayat_pendidikan_mahasiswa', 'RiwayatPendidikanMahasiswa::show', ['filter' => 'auth']);
    $routes->get('riwayat_pendidikan_mahasiswa/(:any)', 'RiwayatPendidikanMahasiswa::show/$1', ['filter' => 'auth']);
    $routes->post('riwayat_pendidikan_mahasiswa', 'RiwayatPendidikanMahasiswa::create', ['filter' => 'auth']);
    $routes->put('riwayat_pendidikan_mahasiswa', 'RiwayatPendidikanMahasiswa::update', ['filter' => 'auth']);
    $routes->delete('riwayat_pendidikan_mahasiswa/(:any)', 'RiwayatPendidikanMahasiswa::delete/$1', ['filter' => 'auth']);

    $routes->get('nilai_transfer', 'NilaiTransfer::show', ['filter' => 'auth']);
    $routes->get('nilai_transfer/(:any)', 'NilaiTransfer::show/$1', ['filter' => 'auth']);
    $routes->get('nilai_transfer_by_mahasiswa/(:any)', 'NilaiTransfer::showByMhs/$1', ['filter' => 'auth']);
    $routes->post('nilai_transfer', 'NilaiTransfer::create', ['filter' => 'auth']);
    $routes->put('nilai_transfer', 'NilaiTransfer::update', ['filter' => 'auth']);
    $routes->delete('nilai_transfer/(:any)', 'NilaiTransfer::delete/$1', ['filter' => 'auth']);

    $routes->get('aktivitas_kuliah', 'AktivitasKuliah::show', ['filter' => 'auth']);
    $routes->get('aktivitas_kuliah/(:any)', 'AktivitasKuliah::show/$1', ['filter' => 'auth']);
    $routes->get('aktivitas_kuliah_by_mahasiswa/(:any)', 'AktivitasKuliah::showByMhs/$1', ['filter' => 'auth']);
    $routes->post('aktivitas_kuliah', 'AktivitasKuliah::create', ['filter' => 'auth']);
    $routes->put('aktivitas_kuliah', 'AktivitasKuliah::update', ['filter' => 'auth']);
    $routes->delete('aktivitas_kuliah/(:any)', 'AktivitasKuliah::delete/$1', ['filter' => 'auth']);

    $routes->group('dosen_wali', ['filter'=>'auth'], function($routes){
        $routes->get('mahasiswa/(:hash)', 'DosenWali::show/$1');
        $routes->post('mahasiswa', 'DosenWali::create');
        $routes->put('mahasiswa', 'DosenWali::update');
        $routes->delete('mahasiswa/(:hash)', 'DosenWali::delete/$1');
    });

    $routes->group('kelas_kuliah', ['filter' => 'auth'], function($routes){
        $routes->get('', 'KelasKuliah::show');
        $routes->get('(:hash)', 'KelasKuliah::show/$1');
        $routes->get('mahasiswa/(:hash)/kelas', 'KelasKuliah::pesertaKelas/$1');
        $routes->get('mahasiswa/(:hash)/(:hash)/prodi', 'KelasKuliah::mahasiswaProdi/$1/$2');
        $routes->get('mahasiswa/(:hash)/(:hash)/prodi/(:hash)', 'KelasKuliah::mahasiswaProdi/$1/$2/$3');
        $routes->post('mahasiswa/all', 'KelasKuliah::mahasiswaAll');
        $routes->get('dosen/(:hash)/kelas', 'KelasKuliah::dosenPengajarKelas/$1');
        $routes->get('dosen/all', 'KelasKuliah::dosenAll/$1');
        $routes->post('kelas_kuliah', 'KelasKuliah::create');
        $routes->post('mahasiswa', 'KelasKuliah::createMahasiswa');
        $routes->post('mahasiswa/kolektif', 'KelasKuliah::createMahasiswaCollective');
        $routes->post('dosen', 'KelasKuliah::createDosen');
        $routes->post('paginate', 'KelasKuliah::paginate');
        $routes->put('kelas_kuliah', 'KelasKuliah::update');
        $routes->put('mahasiswa', 'KelasKuliah::updateMahasiswa');
        $routes->put('dosen', 'KelasKuliah::updateDosen');
        $routes->delete('(:hash)', 'KelasKuliah::delete/$1');
        $routes->delete('mahasiswa/(:hash)', 'KelasKuliah::deleteMahasiswa/$1');
        $routes->delete('dosen/(:hash)', 'deleteDosen::delete/$1');
    });
    
    $routes->get('kurikulum', 'Kurikulum::show', ['filter' => 'auth']);
    $routes->get('kurikulum/(:hash)', 'Kurikulum::show/$1', ['filter' => 'auth']);
    $routes->get('kurikulum/matakuliah/(:hash)/kurikulum', 'Kurikulum::matakuliah_kurikulum/$1', ['filter' => 'auth']);
    $routes->get('kurikulum/matakuliah/(:hash)/prodi', 'Kurikulum::matakuliah_prodi/$1', ['filter' => 'auth']);
    $routes->post('kurikulum', 'Kurikulum::create', ['filter' => 'auth']);
    $routes->post('kurikulum/matakuliah', 'Kurikulum::create_matakuliah', ['filter' => 'auth']);
    $routes->put('kurikulum', 'Kurikulum::update', ['filter' => 'auth']);
    $routes->put('kurikulum/matakuliah', 'Kurikulum::update_matakuliah', ['filter' => 'auth']);
    $routes->delete('kurikulum/(:hash)', 'Kurikulum::delete/$1', ['filter' => 'auth']);
    $routes->delete('kurikulum/matakuliah/(:hash)', 'Kurikulum::delete_matakuliah/$1', ['filter' => 'auth']);

    $routes->get('matakuliah_kurikulum', 'MatakuliahKurikulum::show', ['filter' => 'auth']);
    $routes->get('matakuliah_kurikulum_kurikulum_id/(:any)', 'MatakuliahKurikulum::by_kurikulum_id/$1', ['filter' => 'auth']);
    $routes->get('matakuliah_kurikulum_by_prodi/(:any)', 'MatakuliahKurikulum::by_prodi/$1', ['filter' => 'auth']);
    $routes->post('matakuliah_kurikulum', 'MatakuliahKurikulum::create', ['filter' => 'auth']);
    $routes->put('matakuliah_kurikulum', 'MatakuliahKurikulum::update', ['filter' => 'auth']);
    $routes->delete('matakuliah_kurikulum/(:any)', 'MatakuliahKurikulum::delete/$1', ['filter' => 'auth']);

    $routes->get('dosen_pengajar', 'DosenPengajarKelas::show', ['filter' => 'auth']);
    $routes->get('dosen_pengajar/(:any)', 'DosenPengajarKelas::show/$1', ['filter' => 'auth']);
    $routes->post('dosen_pengajar', 'DosenPengajarKelas::create', ['filter' => 'auth']);
    $routes->put('dosen_pengajar', 'DosenPengajarKelas::update', ['filter' => 'auth']);
    $routes->delete('dosen_pengajar/(:any)', 'DosenPengajarKelas::delete/$1', ['filter' => 'auth']);

    $routes->get('peserta_kelas/(:any)', 'PesertaKelas::show/$1', ['filter' => 'auth']);
    $routes->post('peserta_kelas', 'PesertaKelas::create', ['filter' => 'auth']);
    $routes->put('peserta_kelas', 'PesertaKelas::update', ['filter' => 'auth']);
    $routes->delete('peserta_kelas/(:any)', 'PesertaKelas::delete/$1', ['filter' => 'auth']);

    $routes->group('aktivitas_mahasiswa', ['filter' => 'auth'], function($routes){
        $routes->get('', 'AktivitasMahasiswa::show');
        $routes->get('(:hash)', 'AktivitasMahasiswa::show/$1');
        $routes->get('mahasiswa/(:hash)', 'AktivitasMahasiswa::AnggotaAktivitasMahasiswa/$1');
        $routes->get('dosen/(:hash)/pembimbing', 'AktivitasMahasiswa::dosenPembimbing/$1');
        $routes->get('dosen/(:hash)/penguji', 'AktivitasMahasiswa::dosenPenguji/$1');
        $routes->post('', 'AktivitasMahasiswa::create');
        $routes->post('mahasiswa', 'AktivitasMahasiswa::createAnggota');
        $routes->post('dosen/pembimbing', 'AktivitasMahasiswa::createPembimbing');
        $routes->post('dosen/penguji', 'AktivitasMahasiswa::createPenguji');
        $routes->put('', 'AktivitasMahasiswa::update');
        $routes->delete('(:hash)', 'AktivitasMahasiswa::delete/$1');
        $routes->delete('mahasiswa/(:hash)', 'AktivitasMahasiswa::deleteAnggota/$1');
        $routes->delete('dosen/(:hash)/pembimbing', 'AktivitasMahasiswa::deletePembimbing/$1');
        $routes->delete('dosen/(:hash)/penguji', 'AktivitasMahasiswa::deletePenguji/$1');
    });

    $routes->get('anggota_aktivitas_mahasiswa', 'AnggotaAktivitasMahasiswa::show', ['filter' => 'auth']);
    $routes->get('anggota_aktivitas_mahasiswa/(:any)', 'AnggotaAktivitasMahasiswa::show/$1', ['filter' => 'auth']);
    $routes->post('anggota_aktivitas_mahasiswa', 'AnggotaAktivitasMahasiswa::create', ['filter' => 'auth']);
    $routes->put('anggota_aktivitas_mahasiswa', 'AnggotaAktivitasMahasiswa::update', ['filter' => 'auth']);
    $routes->delete('anggota_aktivitas_mahasiswa/(:any)', 'AnggotaAktivitasMahasiswa::delete/$1', ['filter' => 'auth']);
});

$routes->group('rest', ['namespace'=> 'App\Controllers\Rest'], static function($routes){
    $routes->group('mahasiswa', ['filter' => 'mahasiswa'], function($routes){
        $routes->get('user/(:hash)', 'Mahasiswa::byUserId/$1');
        $routes->get('riwayat_pendidikan/(:hash)', 'Mahasiswa::riwayatPendidikan/$1');
        $routes->get('nilai_transfer/(:hash)', 'Mahasiswa::nilaiTransfer/$1');
        $routes->get('krsm/(:hash)', 'Mahasiswa::krsm/$1');
        $routes->get('aktivitas_kuliah/(:hash)', 'Mahasiswa::aktivitasKuliah/$1');
    });

    $routes->group('jadwal', ['filter' => 'mahasiswa'], function($routes){
        $routes->post('(:hash)', 'Jadwal::show/$1');
    });
});
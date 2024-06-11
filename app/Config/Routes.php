<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'auth']);

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
    $routes->get('GetJenisSertifikasi', 'GetData::GetJenisSertifikasi');
    $routes->get('jenis_pendaftaran', 'GetData::jenis_pendaftaran');
    $routes->get('jenis_sms', 'GetData::jenis_sms');
    $routes->get('bentuk_pendidikan', 'GetData::bentuk_pendidikan');
    $routes->get('jalur_masuk', 'GetData::jalur_masuk');
    $routes->get('transportasi', 'GetData::transportasi');
    $routes->get('nilai_transfer', 'GetData::nilai_transfer');
    $routes->get('jenis_substansi', 'GetData::jenis_substansi');
    $routes->get('jenjang_pendidikan', 'GetData::jenjang_pendidikan');
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
});

$routes->group('api', ['namespace'=> 'App\Controllers\Api'], static function($routes){
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->get('perguruanTinggi', 'Referensi\PerguruanTinggi::store', ['filter' => 'auth']);
    
});



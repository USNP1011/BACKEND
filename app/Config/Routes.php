<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('pt', static function($routes){
    $routes->get('read', 'PerguruanTinggi::index');
});
$routes->group('get_data', static function($routes){
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
});
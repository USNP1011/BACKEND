<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->set404Override('App\Controllers\Errors::show404');
$routes->get('/', 'Home::index');
$routes->get('testing', 'Testing::read');

service('auth')->routes($routes);
$routes->group('pt', static function($routes){
    $routes->get('read', 'PerguruanTinggi::index');
});

$routes->group('auth', static function($routes){
    $routes->post('login', 'Auth::login');
});

$routes->group('get_data', static function($routes){
    $routes->get('/', 'GetData::index');
    $routes->get('pt', 'GetData::pt');
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
    $routes->get('uji_mahasiswa', 'GetData::ujiMahasiswa');
    $routes->get('peserta_kelas', 'GetData::peserta_kelas');
    $routes->get('peserta_kelas_bykelas', 'GetData::peserta_kelas_bykelas');
    $routes->get('nilai_kelas', 'GetData::nilai_kelas');
    $routes->get('dosen_wali', 'GetData::dosenWali');
    $routes->get('mahasiswa_lulus_do', 'GetData::mahasiswaLulusDO');
    $routes->get('kampus_merdeka', 'GetData::konversiKampusMerdeka');
    $routes->get('transkrip', 'GetData::transkrip');
    $routes->get('periode_kuliah', 'GetData::periodePerkuliahan');
});

$routes->group('api', ['namespace'=> 'App\Controllers\Api'], static function($routes){
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->post('allpt', 'Referensi\AllPT::store', ['filter' => 'general']);
    $routes->get('perguruanTinggi', 'Referensi\PerguruanTinggi::store', ['filter' => 'general']);
    $routes->get('agama', 'Referensi\Agama::store', ['filter' => 'general']);
    $routes->get('penghasilan', 'Referensi\Penghasilan::store', ['filter' => 'general']);
    $routes->get('jenis_evaluasi', 'Referensi\JenisEvaluasi::store', ['filter' => 'general']);
    $routes->get('jenis_tinggal', 'Referensi\JenisTinggal::store', ['filter' => 'general']);
    $routes->get('jenis_keluar', 'Referensi\jenisKeluar::store', ['filter' => 'general']);
    $routes->get('jenis_sertifikasi', 'Referensi\JenisSertifikasi::store', ['filter' => 'general']);
    $routes->get('jenis_pendaftaran', 'Referensi\JenisPendaftaran::store', ['filter' => 'general']);
    $routes->get('jenis_pembiayaan', 'Referensi\JenisPembiayaan::store', ['filter' => 'general']);
    $routes->get('jenis_sms', 'Referensi\JenisSMS::store', ['filter' => 'general']);
    $routes->get('jenis_peran', 'Referensi\JenisPeran::store', ['filter' => 'general']);
    $routes->get('bentuk_pendidikan', 'Referensi\BentukPendidikan::store', ['filter' => 'general']);
    $routes->get('jalur_masuk', 'Referensi\JalurMasuk::store', ['filter' => 'general']);
    $routes->get('transportasi', 'Referensi\Transportasi::store', ['filter' => 'general']);
    $routes->get('jenis_substansi', 'Referensi\JenisSubstansi::store', ['filter' => 'general']);
    $routes->get('jenjang_pendidikan', 'Referensi\JenjangPendidikan::store', ['filter' => 'general']);
    $routes->get('kebutuhan_khusus', 'Referensi\KebutuhanKhusus::store', ['filter' => 'general']);
    $routes->get('kategori_kegiatan/(:hash)', 'Referensi\KategoriKegiatan::store/$1', ['filter' => 'general']);
    $routes->get('lembaga_pangkat', 'Referensi\LembagaPangkat::store', ['filter' => 'general']);
    $routes->get('level_wilayah', 'Referensi\LevelWilayah::store', ['filter' => 'general']);
    $routes->get('negara', 'Referensi\Negara::store', ['filter' => 'general']);
    $routes->get('pekerjaan', 'Referensi\Pekerjaan::store', ['filter' => 'general']);
    $routes->get('status_mahasiswa', 'Referensi\StatusMahasiswa::store', ['filter' => 'general']);
    $routes->get('wilayah', 'Referensi\Wilayah::store', ['filter' => 'general']);
    $routes->get('get_wilayah/(:any)/(:any)/(:any)', 'Referensi\Wilayah::by_id/$1/$2/$3', ['filter' => 'general']);
    $routes->get('jenis_aktivitas', 'Referensi\JenisAktivitas::store', ['filter' => 'general']);
    $routes->get('pengajar_kelas', 'Referensi\PengajarKelas::store', ['filter' => 'general']);
    
    $routes->get('prodi', 'Referensi\Prodi::store', ['filter' => 'general']);


    $routes->group('user', ['filter' => 'auth'], function($routes){
        $routes->get('', 'Referensi\SkalaSKS::store');
        $routes->get('(:hash)', 'Referensi\SkalaSKS::store/$1');
        $routes->post('', 'Referensi\SkalaSKS::create');
        $routes->put('', 'Referensi\SkalaSKS::update');
        $routes->delete('(:hash)', 'Referensi\SkalaSKS::delete/$1');
    });

    $routes->group('dosen', ['filter' => 'general'], function($routes){
        $routes->get('', 'Referensi\Dosen::store');
        $routes->get('(:hash)', 'Referensi\Dosen::store/$1');
        $routes->post('', 'Referensi\Dosen::create');
    });
    
    $routes->group('kaprodi', ['filter' => 'auth'], function($routes){
        $routes->get('', 'Kaprodi::store');
        $routes->get('(:hash)', 'Kaprodi::store/$1');
        $routes->post('', 'Kaprodi::create');
        $routes->put('', 'Kaprodi::update');
    });

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

    $routes->group('semester', function($routes){
        $routes->get('', 'Referensi\Semester::store', ['filter' => 'general']);
        $routes->get('(:hash)', 'Referensi\Semester::store/$1', ['filter' => 'general']);
        $routes->put('', 'Referensi\Semester::update', ['filter' => 'general']);

    });


    $routes->group('skala_sks', function($routes){
        $routes->get('', 'Settings::skalaSKS', ['filter' => 'general']);
        $routes->get('(:hash)', 'Settings::skalaSKS/$1', ['filter' => 'general']);
        $routes->post('', 'Settings::createSkalaSKS', ['filter' => 'auth']);
        $routes->put('', 'Settings::updateSkalaSKS', ['filter' => 'auth']);
        $routes->delete('(:hash)', 'Settings::deleteSkalaSKS/$1', ['filter' => 'auth']);
    });

    $routes->group('pembiayaan', function($routes){
        $routes->get('', 'Settings::pembiayaan', ['filter' => 'general']);
        $routes->get('(:hash)', 'Settings::pembiayaan/$1', ['filter' => 'general']);
        $routes->post('', 'Settings::createPembiayaan', ['filter' => 'auth']);
        $routes->put('', 'Settings::updatePembiayaan', ['filter' => 'auth']);
        $routes->delete('(:hash)', 'Settings::deletePembiayaan/$1', ['filter' => 'auth']);
    });
    
    $routes->group('mahasiswa', function($routes){
        $routes->get('', 'Mahasiswa::show', ['filter' => 'auth']);
        $routes->get('(:hash)', 'Mahasiswa::show/$1', ['filter' => 'general']);
        $routes->get('(:hash)/riwayat_pendidikan', 'Mahasiswa::riwayatPendidikan/$1', ['filter' => 'auth']);
        $routes->get('(:hash)/nilai_transfer', 'Mahasiswa::nilaiTransfer/$1', ['filter' => 'auth']);
        $routes->get('(:hash)/(:hash)/krsm', 'Mahasiswa::krsm/$1/$2', ['filter' => 'general']);
        $routes->get('(:hash)/(:hash)/khsm', 'Mahasiswa::khsm/$1/$2', ['filter' => 'general']);
        $routes->get('(:hash)/krsm', 'Mahasiswa::krsm/$1', ['filter' => 'general']);
        $routes->get('(:hash)/aktivitas_kuliah', 'Mahasiswa::aktivitasKuliah/$1', ['filter' => 'auth']);
        $routes->get('(:hash)/transkrip', 'Mahasiswa::transkrip/$1', ['filter' => 'general']);
        $routes->get('(:hash)/update_transkrip', 'Mahasiswa::updateTranskrip/$1', ['filter' => 'general']);
        $routes->post('', 'Mahasiswa::create', ['filter' => 'auth']);
        $routes->put('', 'Mahasiswa::update', ['filter' => 'auth']);
        $routes->delete('(:hash)', 'Mahasiswa::delete/$1', ['filter' => 'auth']);
    });
    $routes->post('mahasiswa_paginate', 'Mahasiswa::paginate', ['filter' => 'auth']);

    $routes->group('matakuliah', ['filter' => 'auth'], function($routes){
        $routes->get('', 'Matakuliah::show');
        $routes->get('(:hash)', 'Matakuliah::show/$1');
        $routes->get('byprodi/(:hash)', 'Matakuliah::by_prodi/$1');
        $routes->post('', 'Matakuliah::create');
        $routes->put('', 'Matakuliah::update');
        $routes->delete('(:hash)', 'Matakuliah::delete/$1');
    });
    $routes->post('matakuliah_paginate', 'Matakuliah::paginate');

    $routes->group('riwayat_pendidikan_mahasiswa', ['filter' => 'auth'], function($routes){
        $routes->get('', 'RiwayatPendidikanMahasiswa::show');
        $routes->get('(:hash)', 'RiwayatPendidikanMahasiswa::show/$1');
        $routes->get('perguruan_tinggi/(:hash)', 'RiwayatPendidikanMahasiswa::perguruanTinggi/$1');
        $routes->get('prodi/(:hash)', 'RiwayatPendidikanMahasiswa::prodi/$1');
        $routes->post('', 'RiwayatPendidikanMahasiswa::create');
        $routes->put('', 'RiwayatPendidikanMahasiswa::update');
        $routes->delete('(:hash)', 'RiwayatPendidikanMahasiswa::delete/$1');
    });

    $routes->group('nilai_transfer', ['filter' => 'auth'], function($routes){
        $routes->get('(:hash)', 'NilaiTransfer::show/$1');
        $routes->post('', 'NilaiTransfer::create');
        $routes->put('', 'NilaiTransfer::update');
        $routes->delete('(:hash)', 'NilaiTransfer::delete/$1');
    });
    $routes->get('nilai_transfer_by_mahasiswa/(:any)', 'NilaiTransfer::showByMhs/$1', ['filter' => 'auth']);

    $routes->group('aktivitas_kuliah', ['filter' => 'auth'], function($routes){
        $routes->get('', 'AktivitasKuliah::show');
        $routes->get('(:hash)', 'AktivitasKuliah::show/$1');
        $routes->post('paginate', 'AktivitasKuliah::paginate');
        $routes->post('', 'AktivitasKuliah::create');
        $routes->put('', 'AktivitasKuliah::update');
        $routes->delete('(:hash)', 'AktivitasKuliah::delete/$1');
    });
    $routes->get('aktivitas_kuliah_by_mahasiswa/(:any)', 'AktivitasKuliah::showByMhs/$1', ['filter' => 'auth']);

    $routes->group('dosen_wali', ['filter'=>'auth'], function($routes){
        $routes->get('mahasiswa/(:hash)', 'DosenWali::show/$1');
        $routes->post('mahasiswa', 'DosenWali::create');
        $routes->put('mahasiswa', 'DosenWali::update');
        $routes->delete('mahasiswa/(:hash)', 'DosenWali::delete/$1');
    });

    $routes->group('kelas_kuliah', function($routes){
        $routes->get('', 'KelasKuliah::show', ['filter' => 'auth']);
        $routes->get('(:hash)', 'KelasKuliah::show/$1', ['filter' => 'auth']);
        $routes->get('mahasiswa/(:hash)/kelas', 'KelasKuliah::pesertaKelas/$1', ['filter' => 'auth']);
        $routes->get('mahasiswa/(:hash)/(:hash)/prodi', 'KelasKuliah::mahasiswaProdi/$1/$2', ['filter' => 'auth']);
        $routes->get('mahasiswa/(:hash)/(:hash)/prodi/(:hash)', 'KelasKuliah::mahasiswaProdi/$1/$2/$3', ['filter' => 'auth']);
        $routes->post('mahasiswa/all', 'KelasKuliah::mahasiswaAll', ['filter' => 'auth']);
        $routes->get('dosen/(:hash)/kelas', 'KelasKuliah::dosenPengajarKelas/$1', ['filter' => 'auth']);
        $routes->get('dosen/all', 'KelasKuliah::dosenAll/$1', ['filter' => 'auth']);
        $routes->post('kelas_kuliah', 'KelasKuliah::create', ['filter' => 'auth']);
        $routes->post('mahasiswa', 'KelasKuliah::createMahasiswa', ['filter' => 'auth']);
        $routes->post('mahasiswa/kolektif', 'KelasKuliah::createMahasiswaCollective', ['filter' => 'auth']);
        $routes->post('dosen', 'KelasKuliah::createDosen', ['filter' => 'auth']);
        $routes->post('paginate', 'KelasKuliah::paginate', ['filter' => 'auth']);
        $routes->put('kelas_kuliah', 'KelasKuliah::update', ['filter' => 'auth']);
        $routes->put('mahasiswa', 'KelasKuliah::updateMahasiswa', ['filter' => 'general']);
        $routes->put('dosen', 'KelasKuliah::updateDosen', ['filter' => 'auth']);
        $routes->delete('(:hash)', 'KelasKuliah::delete/$1', ['filter' => 'auth']);
        $routes->delete('mahasiswa/(:hash)', 'KelasKuliah::deleteMahasiswa/$1', ['filter' => 'auth']);
        $routes->delete('dosen/(:hash)', 'KelasKuliah::deleteDosen/$1', ['filter' => 'auth']);
    });

    $routes->group('nilai_kelas', ['filter' => 'general'], function($routes){
        $routes->get('kelas/(:hash)', 'NilaiPesertaKelas::kelas/$1');
        $routes->get('skala_nilai/(:hash)', 'NilaiPesertaKelas::skala/$1');
        $routes->get('mahasiswa/(:hash)', 'NilaiPesertaKelas::show/$1');
        $routes->post('', 'NilaiPesertaKelas::create');
    });

    $routes->group('kurikulum', ['filter' => 'auth'], function($routes){
        $routes->get('', 'Kurikulum::show');
        $routes->get('(:hash)', 'Kurikulum::show/$1');
        $routes->get('matakuliah/(:hash)/kurikulum', 'Kurikulum::matakuliah_kurikulum/$1');
        $routes->get('matakuliah/(:hash)/prodi', 'Kurikulum::matakuliah_prodi/$1');
        $routes->post('', 'Kurikulum::create');
        $routes->post('matakuliah', 'Kurikulum::create_matakuliah');
        $routes->put('', 'Kurikulum::update');
        $routes->put('matakuliah', 'Kurikulum::update_matakuliah');
        $routes->delete('(:hash)', 'Kurikulum::delete/$1');
        $routes->delete('matakuliah/(:hash)', 'Kurikulum::delete_matakuliah/$1');
    });

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

    $routes->post('reset_password', 'AuthController::resetPassword', ['filter'=>'general']);
    $routes->post('create_user', 'AuthController::createUser', ['filter'=>'general']);
    $routes->post('change_password', 'AuthController::changePassword', ['filter'=>'general']);

    $routes->group('v2', function($routes){
        $routes->get('detail_kurikulum_by_prodi/(:hash)', 'V2\Kurikulum::detail_kurikulum/$1');
    });
});

$routes->group('rest', ['namespace'=> 'App\Controllers\Rest'], static function($routes){
    //Mahasiswa 
    $routes->group('mahasiswa', function($routes){
        $routes->get('profile', 'Mahasiswa\Mahasiswa::byUserId', ['filter' => 'mahasiswa']);
        $routes->get('riwayat_pendidikan', 'Mahasiswa\Mahasiswa::riwayatPendidikan', ['filter' => 'mahasiswa']);
        $routes->get('nilai_transfer', 'Mahasiswa\Mahasiswa::nilaiTransfer', ['filter' => 'mahasiswa']);
        $routes->get('krsm', 'Mahasiswa\Mahasiswa::krsm', ['filter' => 'mahasiswa']);
        $routes->get('aktivitas_kuliah', 'Mahasiswa\Mahasiswa::aktivitasKuliah', ['filter' => 'mahasiswa']);
        $routes->get('riwayat_perkuliahan', 'Mahasiswa\Mahasiswa::transkripSementara', ['filter' => 'mahasiswa']);
        $routes->get('riwayat_perkuliahan/(:hash)', 'Mahasiswa\Mahasiswa::transkripSementara/$1', ['filter' => 'general']);
        $routes->get('transkrip', 'Mahasiswa\Mahasiswa::transkrip', ['filter' => 'mahasiswa']);
        $routes->get('transkrip/(:hash)', 'Mahasiswa\Mahasiswa::transkrip/$1', ['filter' => 'general']);
        $routes->put('', 'Mahasiswa\Mahasiswa::update', ['filter' => 'mahasiswa']);
    });

    $routes->group('jadwal', ['filter' => 'mahasiswa'], function($routes){
        $routes->post('', 'Mahasiswa\Jadwal::show');
    });

    $routes->group('krsm', ['filter' => 'general'], function($routes){
        $routes->get('', 'Mahasiswa\Krsm::show');
        $routes->get('(:hash)', 'Mahasiswa\Krsm::show/$1');
        $routes->post('', 'Mahasiswa\Krsm::create');
        $routes->post('(:hash)', 'Mahasiswa\Krsm::create/$1');
        $routes->delete('(:hash)', 'Mahasiswa\Krsm::deleted/$1');
    });

    $routes->group('khsm', function($routes){
        $routes->get('(:hash)', 'Mahasiswa\Khsm::show/$1', ['filter' => 'mahasiswa']);
        $routes->get('(:hash)/(:hash)', 'Mahasiswa\Khsm::show/$1/$2', ['filter' => 'general']); //$1:id_semester, $2: id_riwayat_pendidikan
        $routes->post('', 'Mahasiswa\Khsm::create/$1', ['filter' => 'mahasiswa']);
        $routes->delete('(:hash)', 'Mahasiswa\Khsm::deleted/$1', ['filter' => 'mahasiswa']);
    });
    
    //Dosen
    $routes->group('perwalian', ['filter' => 'dosen'], function($routes){
        $routes->get('mahasiswa', 'Dosen\Perwalian::show');
        $routes->get('pengajuan', 'Dosen\Perwalian::pengajuan');
        $routes->get('pengajuan/(:hash)', 'Dosen\Perwalian::pengajuan/$1');
        $routes->get('jadwal/(:hash)', 'Dosen\Jadwal::show/$1');
        $routes->post('', 'Dosen\Perwalian::updatePengajuan');
    });

    $routes->group('dosen', ['filter' => 'dosen'], function($routes){
        $routes->get('profile', 'Dosen\Dosen::profile');
        $routes->get('jadwal_mengajar', 'Dosen\Dosen::jadwalMengajar');
        $routes->get('jadwal_mengajar/(:hash)', 'Dosen\Dosen::pesertaKelas/$1');
    });

    //Prodi
    $routes->group('approve', ['filter' => 'prodi'], function($routes){
        $routes->get('mahasiswa', 'Prodi\Perwalian::show');
        $routes->get('pengajuan', 'Prodi\Perwalian::pengajuan');
        $routes->get('pengajuan/(:hash)', 'Prodi\Perwalian::pengajuan/$1');
        $routes->post('', 'Prodi\Perwalian::updatePengajuan');
        $routes->put('reject', 'Prodi\Perwalian::tolakPengajuan');
    });

    $routes->group('prodi', ['filter' => 'dosen'], function($routes){
        $routes->get('profile', 'Prodi\Prodi::profile');
    });

    $routes->group('keuangan', ['filter' => 'keuangan'], function($routes){
        $routes->get('pengajuan', 'Keuangan\Perwalian::pengajuan');
        $routes->get('pengajuan/(:hash)', 'Keuangan\Perwalian::pengajuan/$1');
        $routes->post('pengajuan', 'Keuangan\Perwalian::updatePengajuan');
    });
});
$routes->group('sync', function($routes){
    $routes->get('', 'Sync::index');
    $routes->get('read', 'Sync::getSync');
    $routes->post('mahasiswa', 'Sync::syncMahasiswa');
    $routes->post('riwayat_pendidikan', 'Sync::syncHistoryPendidikan');
    $routes->post('kelas_kuliah', 'Sync::syncKelasKuliah');
    $routes->get('delete_kelas_kuliah', 'Sync::syncDeleteKelasKuliah');
    $routes->post('pengajar_kelas', 'Sync::syncPengajarKelas');
    $routes->get('delete_pengajar_kelas', 'Sync::syncDeletePengajarKelas');
    $routes->post('peserta_kelas', 'Sync::syncPesertaKelas');
    $routes->get('delete_peserta_kelas', 'Sync::syncDeletePesertaKelas');
    $routes->post('perkuliahan_mahasiswa', 'Sync::syncPerkuliahanMahasiswa');
    $routes->post('nilai_peserta_kelas', 'Sync::syncNilaiPesertaKelas');
    $routes->post('aktivitas_mahasiswa', 'Sync::syncAktivitasMahasiswa');
    $routes->post('anggota_aktivitas_mahasiswa', 'Sync::syncAnggotaAktivitasMahasiswa');
    $routes->post('bimbing_mahasiswa', 'Sync::syncBimbingMahasiswa');
    $routes->post('uji_mahasiswa', 'Sync::syncUjiMahasiswa');
    $routes->post('transkrip', 'Sync::syncTranskrip');
    $routes->post('nilai_transfer', 'Sync::syncNilaiTransfer');
});

$routes->group('repair', function($routes){
    $routes->get('', 'Repair::repair');
    $routes->get('temp', 'Repair::clearTemp');
    $routes->get('transkrip', 'Repair::transkrip');
    $routes->get('akm/(:any)', 'Repair::prosesPerkuliahanMandiri/$1');
    $routes->post('mahasiswa', 'Repair::createMahasiswa');
    $routes->post('register', 'Repair::createRegistrasi');
    $routes->get('hitung_akm/(:hash)', 'Repair::hitungAKM/$1');
});

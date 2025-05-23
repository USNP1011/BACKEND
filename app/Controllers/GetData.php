<?php




namespace App\Controllers;


use App\Controllers\BaseController;
use App\Libraries\Rest;
use App\Models\PerguruanTinggiModel;
use Ramsey\Uuid\Uuid;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class GetData extends BaseController
{
    protected $api;
    protected $pt;
    protected $token;
    public function __construct()
    {
        set_time_limit(3600);
        $this->api = new Rest();
        $this->pt = new PerguruanTinggiModel();
        try {
            $this->token = $this->api->getToken()->data->token;
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
    public function index()
    {
        // $this->profile_pt();
        // $this->agama();
        // $this->prodi();
        // $this->ta();
        // $this->periode();
        // $this->semester();
        // $this->jenis_evaluasi();
        // $this->jenis_tinggal();
        // $this->jenis_keluar();
        // $this->GetJenisSertifikasi();
        // $this->jenis_pendaftaran();
        // $this->jenis_sms();
        // $this->jenisPrestasi();
        // $this->tingkatPrestasi();
        // $this->pembiayaan();
        // $this->kategori_kegiatan();
        // $this->bentuk_pendidikan();
        // $this->jalur_masuk();
        // $this->transportasi();
        // $this->jenis_substansi();
        // $this->jenjang_pendidikan();
        // $this->kebutuhan_khusus();
        // $this->lembaga_pangkat();
        // $this->level_wilayah();
        // $this->nagara();
        // $this->pekerjaan();
        // $this->penghasilan();
        // $this->wilayah();
        // $this->skala_nilai();
        // $this->jenis_aktivitas();
        // $this->mahasiswa();
        // $this->riwayat_pendidikan();
        // $this->prestasiMahasiswa();
        // $this->kurikulum();
        // $this->matakuliah();
        // $this->kurikulum_detail();
        // $this->kelas_kuliah();
        // $this->dosen();
        // $this->penugasan_dosen();
        // $this->pengajar_kelas();
        // $this->peserta_kelas();
        // $this->status_mahasiswa();
        // $this->aktivitas_kuliah();
        // $this->nilai_transfer();
        // $this->aktivitas_mahasiswa();
        // $this->anggota_aktivitas_mahasiswa();
        // $this->bimbing_mahasiswa();
        // $this->ujiMahasiswa();
        // $this->konversiKampusMerdeka();
        $this->transkrip();
    }


    public function profile_pt()
    {
        $data = $this->api->getData('GetProfilPT', $this->token);
        $this->pt->insert($data->data[0]);
    }

    public function prodi()
    {
        $prodi = new \App\Models\ProdiModel();

        $data = $this->api->getData('GetProdi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetProdi', $this->token);
        }
        $pt = $this->api->getData('GetProfilPT', $this->token)->data[0];
        foreach ($data->data as $key => $value) {
            $value->id_perguruan_tinggi = $pt->id_perguruan_tinggi;
            $prodi->insert($value);
        }
    }

    public function periode()
    {
        $prodi = new \App\Models\PeriodeModel();

        $data = $this->api->getData('GetPeriode', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetPeriode', $this->token);
        }
        $prodi->insertBatch($data->data);
    }

    public function jenis_tinggal()
    {
        $jenis_tinggal = new \App\Models\JenisTinggalModel();

        $data = $this->api->getData('GetJenisTinggal', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisTinggal', $this->token);
        }
        $jenis_tinggal->insertBatch($data->data);
    }

    public function jenis_keluar()
    {
        $jenisKeluar = new \App\Models\JenisKeluarModel();

        $data = $this->api->getData('GetJenisKeluar', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisKeluar', $this->token);
        }
        $jenisKeluar->insertBatch($data->data);
    }

    public function agama()
    {
        $agama = new \App\Models\AgamaModel();

        $data = $this->api->getData('GetAgama', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetAgama', $this->token);
        }
        $agama->insertBatch($data->data);
    }

    public function GetJenisSertifikasi()
    {
        $JenisSertifikasi = new \App\Models\JenisSertifikasiModel();

        $data = $this->api->getData('GetJenisSertifikasi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisSertifikasi', $this->token);
        }
        $JenisSertifikasi->insertBatch($data->data);
    }

    public function jenis_pendaftaran()
    {
        $jenisPendaftaran = new \App\Models\JenisPendaftaranModel();

        $data = $this->api->getData('GetJenisPendaftaran', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisPendaftaran', $this->token);
        }
        $jenisPendaftaran->insertBatch($data->data);
    }

    public function jenis_sms()
    {
        $GetJenisSMS = new \App\Models\JenisSmsModel();

        $data = $this->api->getData('GetJenisSMS', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisSMS', $this->token);
        }
        $GetJenisSMS->insertBatch($data->data);
    }

    public function tingkatPrestasi()
    {
        $tingkat = new \App\Models\TingkatPrestasiModel();
        $data = $this->api->getData('GetTingkatPrestasi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetTingkatPrestasi', $this->token);
        }
        $tingkat->insertBatch($data->data);
    }

    public function jenisPrestasi()
    {
        $prestasi = new \App\Models\JenisPrestasiModel();
        $data = $this->api->getData('GetJenisPrestasi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisPrestasi', $this->token);
        }
        $prestasi->insertBatch($data->data);
    }

    public function bentuk_pendidikan()
    {
        $bentukPendidikan = new \App\Models\BentukPendidikanModel();

        $data = $this->api->getData('GetBentukPendidikan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetBentukPendidikan', $this->token);
        }
        $bentukPendidikan->insertBatch($data->data);
    }

    public function jalur_masuk()
    {
        $jalurMasuk = new \App\Models\JalurMasukModel();

        $data = $this->api->getData('GetJalurMasuk', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJalurMasuk', $this->token);
        }
        $jalurMasuk->insertBatch($data->data);
    }

    public function nilai_transfer()
    {
        $nilaiTransfer = new \App\Models\NilaiTransferModel();
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();

        $data = $this->api->getData('GetNilaiTransferPendidikanMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetNilaiTransferPendidikanMahasiswa', $this->token);
        }
        $itemUpdate = [];
        foreach ($data->data as $key => $value) {
            $nilaiTransfer->where('id_transfer', $value->id_transfer)->delete();
            $value->id = Uuid::uuid4()->toString();
            $value->id_riwayat_pendidikan = $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first()->id;
            $itemUpdate[] = $value;
        }
        $nilaiTransfer->insertBatch($itemUpdate);
    }

    public function transportasi()
    {
        $transport = new \App\Models\JenisTransportasiModel();

        $data = $this->api->getData('GetAlatTransportasi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetAlatTransportasi', $this->token);
        }
        $transport->insertBatch($data->data);
    }

    public function jenis_substansi()
    {
        $jenisSubstansi = new \App\Models\JenisSubstansiModel();

        $data = $this->api->getData('GetJenisSubstansi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisSubstansi', $this->token);
        }
        $jenisSubstansi->insertBatch($data->data);
    }

    public function kategori_kegiatan()
    {
        $kategori = new \App\Models\KategoriKegiatanModel();

        $data = $this->api->getData('GetKategoriKegiatan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetKategoriKegiatan', $this->token);
        }
        $kategori->insertBatch($data->data);
    }

    public function pembiayaan()
    {
        $pembiayaan = new \App\Models\PembiayaanModel();

        $data = $this->api->getData('GetPembiayaan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetPembiayaan', $this->token);
        }
        $pembiayaan->insertBatch($data->data);
    }

    public function jenjang_pendidikan()
    {
        $jenjangPendidikan = new \App\Models\JenjangPendidikanModel();

        $data = $this->api->getData('GetJenjangPendidikan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenjangPendidikan', $this->token);
        }
        $jenjangPendidikan->insertBatch($data->data);
    }

    public function kebutuhan_khusus()
    {
        $kebutuhanKhusus = new \App\Models\KebutuhanKhususModel();

        $data = $this->api->getData('GetKebutuhanKhusus', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetKebutuhanKhusus', $this->token);
        }
        $kebutuhanKhusus->insertBatch($data->data);
    }

    public function lembaga_pangkat()
    {
        $lembagaPangkat = new \App\Models\LembagaPengangkatanModel();

        $data = $this->api->getData('GetLembagaPengangkat', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetLembagaPengangkat', $this->token);
        }
        $lembagaPangkat->insertBatch($data->data);
    }

    public function level_wilayah()
    {
        $levelWilayah = new \App\Models\LevelWilayahModel();

        $data = $this->api->getData('GetLevelWilayah', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetLevelWilayah', $this->token);
        }
        $levelWilayah->insertBatch($data->data);
    }

    public function nagara()
    {
        $negara = new \App\Models\NegaraModel();

        $data = $this->api->getData('GetNegara', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetNegara', $this->token);
        }
        $negara->insertBatch($data->data);
    }

    public function pekerjaan()
    {
        $pekerjaan = new \App\Models\PekerjaanModel();

        $data = $this->api->getData('GetPekerjaan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetPekerjaan', $this->token);
        }
        $pekerjaan->insertBatch($data->data);
    }

    public function penghasilan()
    {
        $penghasilan = new \App\Models\PenghasilanModel();

        $data = $this->api->getData('GetPenghasilan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetPenghasilan', $this->token);
        }
        $penghasilan->insertBatch($data->data);
    }

    public function status_mahasiswa()
    {
        $statusMahasiswa = new \App\Models\StatusMahasiswaModel();

        $data = $this->api->getData('GetStatusMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetStatusMahasiswa', $this->token);
        }
        $statusMahasiswa->insertBatch($data->data);
    }

    public function wilayah()
    {
        $wilayah = new \App\Models\WilayahModel();

        $data = $this->api->getData('GetWilayah', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetWilayah', $this->token);
        }
        $wilayah->insertBatch($data->data);
    }

    public function jenis_aktivitas()
    {
        $jenisAktivitas = new \App\Models\JenisAktivitasMahasiswaModel();

        $data = $this->api->getData('GetJenisAktivitasMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisAktivitasMahasiswa', $this->token);
        }
        $jenisAktivitas->insertBatch($data->data);
    }

    public function aktivitas_kuliah()
    {
        $aktivitasMahasiswa = new \App\Models\PerkuliahanMahasiswaModel();
        $riwayatPendidikan = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $mahasiswa = new \App\Models\MahasiswaModel();
        $conn = \Config\Database::connect();

        $data = $this->api->getData('GetAktivitasKuliahMahasiswa', $this->token, "id_semester='20233' AND id_status_mahasiswa ='A'");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetAktivitasKuliahMahasiswa', $this->token);
        }
        try {
            $conn->transException(true)->transStart();
            foreach ($data->data as $key => $value) {
                $value->id = Uuid::uuid4()->toString();
                $value->id_riwayat_pendidikan = $riwayatPendidikan->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first()->id;
                $value->id_mahasiswa = $mahasiswa->where('id_mahasiswa', $value->id_mahasiswa)->first()->id;
                $aktivitasMahasiswa->insert($value);
            }
            $conn->transComplete();
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function aktivitas_mahasiswa()
    {
        $aktivitasMahasiswa = new \App\Models\AktivitasMahasiswaModel();

        $data = $this->api->getData('GetListAktivitasMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListAktivitasMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            if ($aktivitasMahasiswa->where('id_aktivitas', $value->id_aktivitas)->countAllResults() == 0) {
                $value->id = Uuid::uuid4()->toString();
                $value->status_sync = "sudah sync";
                $value->sync_at = date('Y-m-d H:i:s');
                $value->updated_at = date('Y-m-d H:i:s');
                $value->id_jenis_aktivitas_mahasiswa = $value->id_jenis_aktivitas;
                $tanggal = explode('-', $value->tanggal_sk_tugas);
                $value->tanggal_sk_tugas = $value->tanggal_sk_tugas != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
                $aktivitasMahasiswa->insert($value);
            }
        }
    }

    public function anggota_aktivitas_mahasiswa()
    {
        try {
            $anggotaAktivitasMahasiswa = new \App\Models\AnggotaAktivitasModel();
            $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
            $aktivitas = new \App\Models\AktivitasMahasiswaModel();
            $data = $this->api->getData('GetListAnggotaAktivitasMahasiswa', $this->token);
            if ($data->error_code == 100) {
                $this->token = $this->api->getToken()->data->token;
                $data = $this->api->getData('GetListAnggotaAktivitasMahasiswa', $this->token);
            }
            foreach ($data->data as $key => $value) {
                $value->id = Uuid::uuid4()->toString();
                $value->status_sync = "sudah sync";
                $value->sync_at = date('Y-m-d H:i:s');
                $value->updated_at = date('Y-m-d H:i:s');
                $itemAktivitas = $aktivitas->where('id_aktivitas', $value->id_aktivitas)->first();
                $value->aktivitas_mahasiswa_id = $itemAktivitas->id;
                $itemRiwayat = $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first();
                $value->id_riwayat_pendidikan = $itemRiwayat->id;
                if ($anggotaAktivitasMahasiswa->where('aktivitas_mahasiswa_id', $itemAktivitas->id)->where("id_riwayat_pendidikan", $itemRiwayat->id)->where('jenis_peran', $value->jenis_peran)->countAllResults() == 0) {
                    $anggotaAktivitasMahasiswa->insert($value);
                }
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public function bimbing_mahasiswa()
    {
        $bimbingMahasiswa = new \App\Models\BimbingMahasiswaModel();
        $aktivitas = new \App\Models\AktivitasMahasiswaModel();
        $data = $this->api->getData('GetListBimbingMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListBimbingMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            if ($bimbingMahasiswa->where('id_bimbing_mahasiswa', $value->id_bimbing_mahasiswa)->countAllResults() == 0) {
                $value->id = Uuid::uuid4()->toString();
                $value->status_sync = "sudah sync";
                $value->sync_at = date('Y-m-d H:i:s');
                $value->updated_at = date('Y-m-d H:i:s');
                $itemAktivitas = $aktivitas->where('id_aktivitas', $value->id_aktivitas)->first();
                $value->aktivitas_mahasiswa_id = $itemAktivitas->id;
                $bimbingMahasiswa->insert($value);
            }
        }
    }

    public function ujiMahasiswa()
    {
        $ujiMahasiswa = new \App\Models\UjiMahasiswaModel();
        $aktivitas = new \App\Models\AktivitasMahasiswaModel();
        $data = $this->api->getData('GetListUjiMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListUjiMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            if ($ujiMahasiswa->where('id_uji', $value->id_uji)->countAllResults() == 0) {
                $value->id = Uuid::uuid4()->toString();
                $value->status_sync = "sudah sync";
                $value->sync_at = date('Y-m-d H:i:s');
                $value->updated_at = date('Y-m-d H:i:s');
                $itemAktivitas = $aktivitas->where('id_aktivitas', $value->id_aktivitas)->first();
                $value->aktivitas_mahasiswa_id = $itemAktivitas->id;
                $ujiMahasiswa->insert($value);
            }
        }
    }

    public function skala_nilai()
    {
        $skalaNilai = new \App\Models\SkalaNilaiModel();

        $data = $this->api->getData('GetListSkalaNilaiProdi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListSkalaNilaiProdi', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $tanggal = explode('-', $value->tanggal_mulai_efektif);
            $value->tanggal_mulai_efektif = $value->tanggal_mulai_efektif != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $tanggal = explode('-', $value->tanggal_akhir_efektif);
            $value->tanggal_akhir_efektif = $value->tanggal_akhir_efektif != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $skalaNilai->insert($value);
        }
    }


    public function mahasiswa()
    {
        $mahasiswa = new \App\Models\MahasiswaModel();
        $conn = \Config\Database::connect();
        $report = ['gagal' => [], 'berhasil' => []];
        try {
            $conn->transException(true)->transStart();
            $data = $this->api->getData('GetBiodataMahasiswa', $this->token, "id_mahasiswa='d1efadbc-00e6-4be9-aef8-9589f3292094'", "", "");
            $report['total'] = $data->data;
            foreach ($data->data as $key => $value) {
                $value->oap = "2";
                $value->suku = "-";
                if (!$this->validateData((array) $value, 'mahasiswa')) {
                    $item = [
                        'nama' => $value->nama_mahasiswa,
                        'status' => [
                            "status" => false,
                            "message" => $this->validator->getErrors(),
                        ]
                    ];
                    $report['gagal'][] = $item;
                } else {
                    $myuuid = Uuid::uuid4();
                    $value->id = $myuuid->toString();
                    $value->status_sync = "sudah sync";
                    $value->status_at = date("Y-m-d");
                    $tanggal = explode('-', $value->tanggal_lahir);
                    $value->tanggal_lahir = $value->tanggal_lahir != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
                    $tanggal = explode('-', $value->tanggal_lahir_ayah);
                    $value->tanggal_lahir_ayah = $value->tanggal_lahir_ayah != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
                    $tanggal = explode('-', $value->tanggal_lahir_ibu);
                    $value->tanggal_lahir_ibu = $value->tanggal_lahir_ibu != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
                    $tanggal = explode('-', $value->tanggal_lahir_wali);
                    $value->tanggal_lahir_wali = $value->tanggal_lahir_wali != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
                    $modelMahasiswa = new \App\Entities\Mahasiswa();
                    $modelMahasiswa->fill((array)$value);
                    if ($mahasiswa->insert($modelMahasiswa)) {
                        $dataRiwayat = $this->api->getData('GetListRiwayatPendidikanMahasiswa', $this->token, "id_mahasiswa='" . $value->id_mahasiswa . "'", "", 1);
                        if (!is_null($dataRiwayat->data)) {
                            foreach ($dataRiwayat->data as $keyRiwayat => $valueRiwayat) {
                                $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
                                $model = new \App\Entities\RiwayatPendidikanMahasiswa();
                                $valueRiwayat->id = Uuid::uuid4()->toString();
                                $valueRiwayat->angkatan = $valueRiwayat->nim;
                                $valueRiwayat->id_mahasiswa = $mahasiswa->where('id_mahasiswa', $valueRiwayat->id_mahasiswa)->first()->id;
                                $model->fill((array)$valueRiwayat);
                                $riwayat->insert($model);
                            }
                        }
                    }
                    $item = [
                        'nama' => $value->nama_mahasiswa,
                        'status' => "success"
                    ];
                    $report['berhasil'][] = $item;
                }
            }
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $report
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function prestasiMahasiswa()
    {
        $prestasiMahasiswa = new \App\Models\PrestasiMahasiswaModel();
        $mahasiswa = new \App\Models\MahasiswaModel();

        $data = $this->api->getData('GetListPrestasiMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListPrestasiMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $model = new \App\Entities\PrestasiMahasiswaEntity();
            $value->id = Uuid::uuid4()->toString();
            $value->mahasiswa_id = $mahasiswa->where('id_mahasiswa', $value->id_mahasiswa)->first()->id;
            $model->fill((array)$value);
            $prestasiMahasiswa->insert($model);
        }
    }

    public function riwayat_pendidikan()
    {
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $mahasiswa = new \App\Models\MahasiswaModel();
        $data = $this->api->getData('GetListRiwayatPendidikanMahasiswa', $this->token, "nim='202411152'");
        foreach ($data->data as $key => $value) {
            $item = $riwayat->where('nim', '202411152')->first();
            $model = new \App\Entities\RiwayatPendidikanMahasiswa();
            $value->id = Uuid::uuid4()->toString();
            $value->angkatan = $value->nim;
            $value->status_sync = "sudah sync";
            $value->sync_at = date('Y-m-d H:i:s');
            $value->updated_at = date('Y-m-d H:i:s');
            $value->id_mahasiswa = $mahasiswa->where('id_mahasiswa', $value->id_mahasiswa)->first()->id;
            $model->fill((array)$value);
            $riwayat->update($item->id,$model);
            // if ($riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->countAllResults() == 0) {
            // }
        }
    }

    public function ta()
    {
        $ta = new \App\Models\TahunAjaranModel();

        $data = $this->api->getData('GetTahunAjaran', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetTahunAjaran', $this->token);
        }
        $ta->insertBatch($data->data);
    }

    public function semester()
    {
        $semester = new \App\Models\SemesterModel();
        $data = $this->api->getData('GetSemester', $this->token, "id_tahun_ajaran>=2015");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetSemester', $this->token);
        }
        $semester->insertBatch($data->data);
    }

    public function periodePerkuliahan()
    {
        $periodeKuliah = new \App\Models\PeriodePerkuliahanModel();
        $data = $this->api->getData('GetListPeriodePerkuliahan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListPeriodePerkuliahan', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $item = [
                'id' => Uuid::uuid4()->toString(),
                'id_prodi' => $value->id_prodi,
                'id_semester' => $value->id_semester,
                'jumlah_target_mahasiswa_baru' => $value->jumlah_target_mahasiswa_baru,
                'jumlah_pendaftar_ikut_seleksi' => $value->calon_ikut_seleksi,
                'jumlah_pendaftar_lulus_seleksi' => $value->calon_lulus_seleksi,
                'jumlah_daftar_ulang' => $value->daftar_sbg_mhs,
                'jumlah_mengundurkan_diri' => $value->pst_undur_diri,
                'jumlah_minggu_pertemuan' => $value->jml_mgu_kul,
                'tanggal_awal_perkuliahan' => $value->tanggal_awal_perkuliahan,
                'tanggal_akhir_perkuliahan' => $value->tanggal_akhir_perkuliahan,
                'status_sync' => $value->status_sync,
            ];
            $periodeKuliah->insert($item);
        }
    }

    public function kurikulum()
    {
        $kurikulum = new \App\Models\KurikulumModel();

        $data = $this->api->getData('GetListKurikulum', $this->token, "id_semester>='20181'");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListKurikulum', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $kurikulum->insert($value);
        }
    }

    public function matakuliah()
    {
        $matakuliah = new \App\Models\MatakuliahModel();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $data = $this->api->getData('GetDetailMataKuliah', $this->token, "");
            if ($data->error_code == 100) {
                $this->token = $this->api->getToken()->data->token;
                $data = $this->api->getData('GetDetailMataKuliah', $this->token);
            }
            $result = ['update' => [], 'insert' => []];
            foreach ($data->data as $key => $value) {
                $itemKuliah = $matakuliah->where('id_matkul', $value->id_matkul)->first();
                if (is_null($itemKuliah)) {
                    $value->id = Uuid::uuid4()->toString();
                    $value->status_sync = "sudah sync";
                    $model = new \App\Entities\MatakuliahKurikulumEntity();
                    $model->fill((array) $value);
                    $matakuliah->insert($model);
                    $result['insert'][] = $value;
                } else {
                    $model = new \App\Entities\MatakuliahKurikulumEntity();
                    $model->fill((array) $value);
                    $matakuliah->update($itemKuliah->id, $model);
                    $result['update'][] = $value;
                }
            }
            $conn->transComplete();
            return $this->respond([
                'status' => true,
                'data' => $result
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function kurikulum_detail()
    {
        $detail = new \App\Models\MatakuliahKurikulumModel();
        $kurikulum = new \App\Models\KurikulumModel();
        $matakuliah = new \App\Models\MatakuliahModel();
        $conn = \Config\Database::connect();
        try {
            $conn->transException(true)->transStart();
            $data = $this->api->getData('GetMatkulKurikulum', $this->token, "nama_kurikulum not in('TI-2011')");
            if ($data->error_code == 100) {
                $this->token = $this->api->getToken()->data->token;
                $data = $this->api->getData('GetMatkulKurikulum', $this->token, "nama_kurikulum not in('TI-2011')");
            }
            foreach ($data->data as $key => $value) {
                $itemKurikulum = $kurikulum->where('id_kurikulum', $value->id_kurikulum)->first();
                $value->kurikulum_id = $itemKurikulum->id;
                $itemMatakuliah = $matakuliah->where('id_matkul', $value->id_matkul)->first();
                $value->matakuliah_id = $itemMatakuliah->id;
                $itemDetail = $detail->where('kurikulum_id', $value->kurikulum_id)->where('matakuliah_id', $value->matakuliah_id)->first();
                if (is_null($itemDetail)) {
                    $value->id = Uuid::uuid4()->toString();
                    $value->status_sync = "sudah sync";
                    $detail->insert($value);
                } else {
                    $item = [
                        'kurikulum_id' => $value->kurikulum_id,
                        'matakuliah_id' => $value->matakuliah_id,
                        'apakah_wajib' => $value->apakah_wajib,
                        'semester' => $value->semester
                    ];
                    $value->status_sync = "sudah sync";
                    $detail->update($itemDetail->id, $value);
                }
            }
            $conn->transComplete();
            return $this->respond([
                'status' => true
            ]);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function kelas_kuliah()
    {
        $kelas = new \App\Models\KelasKuliahModel();
        $matakuliah = new \App\Models\MatakuliahModel();
        $conn = \Config\Database::connect();
        $data_kelas = $kelas->where('id_semester', '20241')->where('id_prodi', '8f929cec-0d9e-4d9a-8dfb-795fd50363d6')->findAll();
        try {
            $conn->transException(true)->transStart();
            $item = [];
            $data = $this->api->getData('GetDetailKelasKuliah', $this->token, "id_semester='20241' AND id_prodi='8f929cec-0d9e-4d9a-8dfb-795fd50363d6'");
            if ($data->error_code == 100) {
                $this->token = $this->api->getToken()->data->token;
                $data = $this->api->getData('GetDetailKelasKuliah', $this->token, "id_semester='20241' AND id_prodi='8f929cec-0d9e-4d9a-8dfb-795fd50363d6'");
            }
            foreach ($data->data as $key => $value) {
                $cek = false;
                foreach ($data_kelas as $key => $itemKelas) {
                    if ($value->id_kelas_kuliah == $itemKelas->id_kelas_kuliah)
                        $cek = true;
                }
                if (!$cek) {
                    $value->id = Uuid::uuid4()->toString();
                    $tanggal = explode('-', $value->tanggal_mulai_efektif);
                    $value->tanggal_mulai_efektif = $value->tanggal_mulai_efektif != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
                    $tanggal = explode('-', $value->tanggal_akhir_efektif);
                    $value->tanggal_akhir_efektif = $value->tanggal_akhir_efektif != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
                    $value->matakuliah_id = $matakuliah->where('id_matkul', $value->id_matkul)->first()->id;
                    $value->status_sync = "sudah sync";
                    $value->sync_at = date('Y-m-d H:i:s');
                    $kelas->insert($value);
                    $item[] = $value;
                }
            }
            $conn->transComplete();
            return $item;
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function dosen()
    {
        $dosen = new \App\Models\DosenModel();

        $data = $this->api->getData('DetailBiodataDosen', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('DetailBiodataDosen', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $tanggal = explode('-', $value->tanggal_lahir);
            $value->tanggal_lahir = $value->tanggal_lahir != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $dosen->save($value);
        }
    }

    public function penugasan_dosen()
    {
        $penugasan = new \App\Models\PenugasanDosenModel();
        $data = $this->api->getData('GetListPenugasanDosen', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListPenugasanDosen', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $tanggal = explode('-', $value->tanggal_surat_tugas);
            $value->tanggal_surat_tugas = $value->tanggal_surat_tugas != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $tanggal = explode('-', $value->mulai_surat_tugas);
            $value->mulai_surat_tugas = $value->mulai_surat_tugas != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $tanggal = explode('-', $value->tgl_create);
            $value->tgl_create = $value->tgl_create != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $penugasan->save($value);
        }
    }

    public function jenis_evaluasi()
    {
        $jenis_evaluasi = new \App\Models\JenisEvaluasiModel();

        $data = $this->api->getData('GetJenisEvaluasi', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisEvaluasi', $this->token);
        }
        $jenis_evaluasi->insertBatch($data->data);
    }

    public function pengajar_kelas()
    {
        $pengajar = new \App\Models\DosenPengajarKelasModel();
        $kelasKuliah = new \App\Models\KelasKuliahModel();

        $data = $this->api->getData('GetDosenPengajarKelasKuliah', $this->token, "id_semester='20241'");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDosenPengajarKelasKuliah', $this->token);
        }
        $item = [];
        try {
            foreach ($data->data as $key => $value) {
                // if($pengajar->where('id_aktivitas_mangajar IS NULL')->where('id_kelas_kuliah', $value->id_kelas_kuliah))
                $value->id = Uuid::uuid4()->toString();
                $value->status_sync = 'sudah sync';
                $value->sync_at = date('Y-m-d H:i:s');
                $value->kelas_kuliah_id = $kelasKuliah->where('id_kelas_kuliah', $value->id_kelas_kuliah)->first()->id;
                $pengajar->insert($value);
                $item[] = $value;
            }
            return $this->respond($item);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function peserta_kelas()
    {
        $pesertaKelas = new \App\Models\PesertaKelasModel();
        $mahasiswa = new \App\Models\MahasiswaModel();
        $kelasKuliah = new \App\Models\KelasKuliahModel();
        $matakuliah = new \App\Models\MatakuliahModel();
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $model = new \App\Entities\PesertaKelasEntity();
        $conn = \Config\Database::connect();

        $data = $this->api->getData('GetPesertaKelasKuliah', $this->token, "id_kelas_kuliah='8fec6dda-6539-4292-b2bb-695920c7f6d1'");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetPesertaKelasKuliah', $this->token);
        }
        $dataSet = [];
        foreach ($data->data as $key => $value) {
            if ($kelasKuliah->where('id_kelas_kuliah', $value->id_kelas_kuliah)->countAllResults() > 0) {
                try {
                    $value->id = Uuid::uuid4()->toString();
                    $value->id_riwayat_pendidikan = $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first()->id;
                    $value->kelas_kuliah_id =  $kelasKuliah->where('id_kelas_kuliah', $value->id_kelas_kuliah)->first()->id;
                    $value->mahasiswa_id = $mahasiswa->where('id_mahasiswa', $value->id_mahasiswa)->first()->id;
                    $value->matakuliah_id = $matakuliah->where('id_matkul', $value->id_matkul)->first()->id;
                    $value->status_sync = 'sudah sync';
                    $value->sync_at = date('Y-m-d H:i:s');
                    $dataSet[] = $value;
                    // $model->fill((array) $value);
                    // $pesertaKelas->insert($model);
                } catch (\Throwable $th) {
                    continue;
                }
            }
        }
        $pesertaKelas->insertBatch($dataSet);


        // return response()->setJSON($data);
    }

    public function peserta_kelas_bykelas()
    {
        $pesertaKelas = new \App\Models\PesertaKelasModel();
        $mahasiswa = new \App\Models\MahasiswaModel();
        $kelasKuliah = new \App\Models\KelasKuliahModel();
        $matakuliah = new \App\Models\MatakuliahModel();
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $model = new \App\Entities\PesertaKelasEntity();
        $conn = \Config\Database::connect();

        $dataKelas = $this->api->getData('GetDetailKelasKuliah', $this->token, "id_semester='20233'");
        $dataSimpan = [];
        try {
            $conn->transException(true)->transStart();
            $data = $this->api->getData('GetPesertaKelasKuliah', $this->token, "");
            foreach ($dataKelas->data as $keyKelas => $kelas) {
                $dataSet = [];
                $where = "id_kelas_kuliah='" . $kelas->id_kelas_kuliah . "'";
                $data = $this->api->getData('GetPesertaKelasKuliah', $this->token, $where);
                foreach ($data->data as $key => $value) {
                    if ($kelasKuliah->where('id_kelas_kuliah', $value->id_kelas_kuliah)->countAllResults() > 0) {
                        $value->id = Uuid::uuid4()->toString();
                        $value->id_riwayat_pendidikan = $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first()->id;
                        $value->kelas_kuliah_id =  $kelasKuliah->where('id_kelas_kuliah', $value->id_kelas_kuliah)->first()->id;
                        $value->mahasiswa_id = $mahasiswa->where('id_mahasiswa', $value->id_mahasiswa)->first()->id;
                        $value->matakuliah_id = $matakuliah->where('id_matkul', $value->id_matkul)->first()->id;
                        $dataSet[] = $value;
                        $dataSimpan[] = $value;
                    }
                }
                $pesertaKelas->insertBatch($dataSet);
            }
            $conn->transComplete();
            return $this->respond($dataSimpan);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }

        // return response()->setJSON($data);
    }

    public function nilai_kelas()
    {
        $pesertaKelas = new \App\Models\PesertaKelasModel();
        $conn = \Config\Database::connect();
        try {
            $data = $this->api->getData('GetDetailNilaiPerkuliahanKelas', $this->token, "id_semester='20233'");
            $conn->transException(true)->transStart();
            $dataUpdate = [];
            foreach ($data->data as $key => $value) {
                $itemKelas = $pesertaKelas->select('peserta_kelas.id')
                    ->join('kelas_kuliah', 'kelas_kuliah.id=peserta_kelas.kelas_kuliah_id', 'left')
                    ->join('riwayat_pendidikan_mahasiswa', 'riwayat_pendidikan_mahasiswa.id=peserta_kelas.id_riwayat_pendidikan', 'left')
                    ->where('kelas_kuliah.id_kelas_kuliah', $value->id_kelas_kuliah)->where('riwayat_pendidikan_mahasiswa.id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first();
                if (!is_null($itemKelas)) {
                    $itemUpdate = [
                        'nilai_angka' => $value->nilai_angka,
                        'nilai_huruf' => $value->nilai_huruf,
                        'nilai_indeks' => $value->nilai_indeks
                    ];
                    $pesertaKelas->update($itemKelas->id, $itemUpdate);
                }
            }
            $conn->transComplete();
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    function dosenWali()
    {
        $conn = \Config\Database::connect('default2');
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $dosen = new \App\Models\DosenModel();
        $waliDosen = new \App\Models\DosenWaliModel();
        $waliSimak = $conn->query("SELECT * FROM dosen_wali")->getResult();
        $dataRiwayat = $riwayat->findAll();
        $dataDosen = $dosen->findAll();
        $dataSave = [];
        foreach ($dataRiwayat as $key1 => $itemRiwayat) {
            $mhs = $this->getMhs($waliSimak, $itemRiwayat->nim);
            if (!is_null($mhs)) {
                $dsn = $this->getDsn($dataDosen, $mhs->nidn);
                if (!is_null($dsn)) {
                    $item = [
                        "id" => Uuid::uuid4()->toString(),
                        "id_dosen" => $dsn->id_dosen,
                        "id_riwayat_pendidikan" => $itemRiwayat->id
                    ];
                    $dataSave[] = $item;
                }
            }
        }
        $waliDosen->insertBatch($dataSave);
        return $this->respond($dataSave);
    }

    public function mahasiswaLulusDO()
    {
        $mhs = new \App\Models\MahasiswaLulusDOModel();
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $dataRiwayat = $riwayat->findAll();
        $data = $this->api->getData('GetDetailMahasiswaLulusDO', $this->token, "");
        $tempArray = [];
        foreach ($data->data as $obj) {
            $tempArray[$obj->{'id_registrasi_mahasiswa'}] = $obj;
        }
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDetailMahasiswaLulusDO', $this->token);
        }
        foreach (array_values($tempArray) as $key => $valueLulus) {
            foreach ($dataRiwayat as $key => $value) {
                if ($valueLulus->id_registrasi_mahasiswa == $value->id_registrasi_mahasiswa) {
                    $valueLulus->id_riwayat_pendidikan =  $value->id;
                    $tanggal = DateTime::createFromFormat('d-m-Y', $valueLulus->tanggal_keluar);
                    $valueLulus->tanggal_keluar = $tanggal->format('Y-m-d');
                    $valueLulus->tanggal_sk_yudisium = $valueLulus->tanggal_keluar;
                    $mhs->save($valueLulus);
                }
            }
        }
        // return response()->setJSON($data);
    }

    function getMhs($array, $npm = null)
    {
        foreach ($array as $key => $value) {
            if ($value->npm == $npm) {
                return $value;
            }
        }
        return null;
    }

    function getDsn($array, $nidn = null)
    {
        foreach ($array as $key => $value) {
            if ($value->nidn == $nidn) {
                return $value;
            }
        }
        return null;
    }

    public function konversiKampusMerdeka()
    {
        $object = new \App\Models\KonversiKampusMerdekaModel();
        $model = new \App\Entities\KonversiKampusMerdekaEntity();
        $conn = \Config\Database::connect();
        try {
            $data = $this->api->getData('GetListKonversiKampusMerdeka', $this->token, "");
            $conn->transException(true)->transStart();
            $dataUpdate = [];
            $anggotaAktivitas = new \App\Models\AnggotaAktivitasModel();
            $matakuliah = new \App\Models\MatakuliahModel();
            foreach ($data->data as $key => $value) {
                $itemAktivitas = $anggotaAktivitas->select("anggota_aktivitas.id as anggota_aktivitas_id, aktivitas_mahasiswa.id as aktivitas_mahasiswa_id")->join('aktivitas_mahasiswa', 'aktivitas_mahasiswa.id=anggota_aktivitas.aktivitas_mahasiswa_id', 'left')
                    ->where('anggota_aktivitas.id_anggota', $value->id_anggota)->first();
                $itemMatakuliah = $matakuliah->where('id_matkul', $value->id_matkul)->first();
                $itemUpdate = [
                    'id' => Uuid::uuid4()->toString(),
                    'id_konversi_aktivitas' => $value->id_konversi_aktivitas,
                    'id_semester' => $value->id_semester,
                    'matakuliah_id' => $itemMatakuliah->id,
                    'anggota_aktivitas_id' => $itemAktivitas->anggota_aktivitas_id,
                    'aktivitas_mahasiswa_id' => $itemAktivitas->aktivitas_mahasiswa_id,
                    'nilai_angka' => $value->nilai_angka,
                    'nilai_huruf' => $value->nilai_huruf,
                    'nilai_indeks' => $value->nilai_indeks,
                ];
                $model = new \App\Entities\KonversiKampusMerdekaEntity();
                $model->fill($itemUpdate);
                $dataUpdate[] = $model;
            }
            $object->insertBatch($dataUpdate);
            $conn->transComplete();
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function transkrip()
    {
        $object = new \App\Models\TranskripModel();
        $matakuliah = new \App\Models\MatakuliahModel();
        $transfer = new \App\Models\NilaiTransferModel();
        $kelas = new \App\Models\KelasKuliahModel();
        $konversi = new \App\Models\KonversiKampusMerdekaModel();
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        $conn = \Config\Database::connect();
        try {
            $data = $this->api->getData('GetTranskripMahasiswa', $this->token, "", "id_registrasi_mahasiswa", 10000, 10000);
            $conn->transException(true)->transStart();
            $dataUpdate = [];
            foreach ($data->data as $key => $value) {
                $itemMatakuliah = $matakuliah->where('id_matkul', $value->id_matkul)->first();
                $itemRiwayat = $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first();
                $object
                    ->where('id_riwayat_pendidikan', $itemRiwayat->id)
                    ->where('matakuliah_id', $itemMatakuliah->id)
                    ->delete();
                $itemUpdate = [
                    'id' => Uuid::uuid4()->toString(),
                    'id_riwayat_pendidikan' => $itemRiwayat->id,
                    'matakuliah_id' => $itemMatakuliah->id,
                    'nilai_angka' => $value->nilai_angka,
                    'nilai_indeks' => $value->nilai_indeks,
                    'nilai_huruf' => $value->nilai_huruf,
                    'status_sync' => 'sudah sync'
                ];
                if (!is_null($value->id_kelas_kuliah)) {
                    $item = $kelas->where('id_kelas_kuliah', $value->id_kelas_kuliah)->first();
                    if (!is_null($item)) {
                        $itemUpdate['kelas_kuliah_id'] = $item->id;
                        $itemUpdate['konversi_kampus_merdeka_id'] = null;
                        $itemUpdate['nilai_transfer_id'] = null;
                    }
                } else if (!is_null($value->id_konversi_aktivitas)) {
                    $item = $konversi->where('id_konversi_aktivitas', $value->id_konversi_aktivitas)->first();
                    if (!is_null($item)) {
                        $itemUpdate['konversi_kampus_merdeka_id'] = $item->id;
                        $itemUpdate['kelas_kuliah_id'] = null;
                        $itemUpdate['nilai_transfer_id'] = null;
                    }
                } else if (!is_null($value->id_nilai_transfer)) {
                    $item = $transfer->where('id_transfer', $value->id_nilai_transfer)->first();
                    if (!is_null($item)) {
                        $itemUpdate['nilai_transfer_id'] = $item->id;
                        $itemUpdate['konversi_kampus_merdeka_id'] = null;
                        $itemUpdate['kelas_kuliah_id'] = null;
                    }
                }
                $model = new \App\Entities\TranskripEntity();
                $model->fill($itemUpdate);
                $object->insert($model);
            }
            $conn->transComplete();
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }
}

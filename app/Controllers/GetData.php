<?php




namespace App\Controllers;


use App\Controllers\BaseController;
use App\Libraries\Rest;
use App\Models\PerguruanTinggiModel;
use Ramsey\Uuid\Uuid;
use CodeIgniter\HTTP\ResponseInterface;


class GetData extends BaseController
{
    protected $api;
    protected $pt;
    protected $token;
    public function __construct()
    {
        $this->api = new Rest();
        $this->pt = new PerguruanTinggiModel();
        $this->token = $this->api->getToken()->data->token;
    }
    public function index()
    {
        $this->profile_pt();
        $this->agama();
        $this->prodi();
        $this->ta();
        $this->periode();
        $this->semester();
        $this->jenis_evaluasi();
        $this->jenis_tinggal();
        $this->jenis_keluar();
        $this->GetJenisSertifikasi();
        $this->jenis_pendaftaran();
        $this->jenis_sms();
        $this->bentuk_pendidikan();
        $this->jalur_masuk();
        $this->transportasi();
        $this->nilai_transfer();
        $this->jenis_substansi();
        $this->jenjang_pendidikan();
        $this->kebutuhan_khusus();
        $this->lembaga_pangkat();
        $this->level_wilayah();
        $this->nagara();
        $this->pekerjaan();
        $this->penghasilan();
        $this->wilayah();
        $this->skala_nilai();
        $this->jenis_aktivitas();
        $this->mahasiswa();
        $this->riwayat_pendidikan();
        $this->kurikulum();
        $this->matakuliah();
        $this->kurikulum_detail();
        $this->kelas_kuliah();
        $this->dosen();
        $this->penugasan_dosen();
        $this->pengajar_kelas();
        $this->peserta_kelas();
        $this->status_mahasiswa();
        $this->aktivitas_kuliah();
        $this->aktivitas_mahasiswa();
        $this->anggota_aktivitas_mahasiswa();
        $this->bimbing_mahasiswa();
    }


    public function     profile_pt()
    {
        $data = $this->api->getData('GetProfilPT', $this->token);
        $this->pt->insert($data->data[0]);
        return response()->setJSON($data);
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
        return response()->setJSON($data);
    }

    public function periode()
    {
        $prodi = new \App\Models\PeriodeModel();

        $data = $this->api->getData('GetPeriode', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetPeriode', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $prodi->insert($value);
        }
        return response()->setJSON($data);
    }

    public function jenis_tinggal()
    {
        $jenis_tinggal = new \App\Models\JenisTinggalModel();

        $data = $this->api->getData('GetJenisTinggal', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisTinggal', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $jenis_tinggal->insert($value);
        }
        return response()->setJSON($data);
    }

    public function jenis_keluar()
    {
        $jenisKeluar = new \App\Models\JenisKeluarModel();

        $data = $this->api->getData('GetJenisKeluar', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisKeluar', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $jenisKeluar->insert($value);
        }
        return response()->setJSON($data);
    }

    public function agama()
    {
        $agama = new \App\Models\AgamaModel();

        $data = $this->api->getData('GetAgama', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetAgama', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $agama->insert($value);
        }
        return response()->setJSON($data);
    }

    public function GetJenisSertifikasi()
    {
        $JenisSertifikasi = new \App\Models\JenisSertifikasiModel();

        $data = $this->api->getData('GetJenisSertifikasi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisSertifikasi', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $JenisSertifikasi->insert($value);
        }
        return response()->setJSON($data);
    }

    public function jenis_pendaftaran()
    {
        $jenisPendaftaran = new \App\Models\JenisPendaftaranModel();

        $data = $this->api->getData('GetJenisPendaftaran', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisPendaftaran', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $jenisPendaftaran->insert($value);
        }
        return response()->setJSON($data);
    }

    public function jenis_sms()
    {
        $GetJenisSMS = new \App\Models\JenisSmsModel();

        $data = $this->api->getData('GetJenisSMS', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisSMS', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $GetJenisSMS->insert($value);
        }
        return response()->setJSON($data);
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
        return response()->setJSON($data);
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
        return response()->setJSON($data);
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
        foreach ($data->data as $key => $value) {
            $dataRiwayat = $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first();
            $value->id = Uuid::uuid4()->toString();
            $value->id_riwayat_pendidikan = $dataRiwayat->id;
            $nilaiTransfer->insert($value);
        }
        return response()->setJSON($data);
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
        return response()->setJSON($data);
    }

    public function jenis_substansi()
    {
        $jenisSubstansi = new \App\Models\JenisSubstansiModel();

        $data = $this->api->getData('GetJenisSubstansi', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisSubstansi', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $jenisSubstansi->insert($value);
        }
        return response()->setJSON($data);
    }

    public function jenjang_pendidikan()
    {
        $jenjangPendidikan = new \App\Models\JenjangPendidikanModel();

        $data = $this->api->getData('GetJenjangPendidikan', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenjangPendidikan', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $jenjangPendidikan->insert($value);
        }
        return response()->setJSON($data);
    }

    public function kebutuhan_khusus()
    {
        $kebutuhanKhusus = new \App\Models\KebutuhanKhususModel();

        $data = $this->api->getData('GetKebutuhanKhusus', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetKebutuhanKhusus', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $kebutuhanKhusus->insert($value);
        }
        return response()->setJSON($data);
    }

    public function lembaga_pangkat()
    {
        $lembagaPangkat = new \App\Models\LembagaPengangkatanModel();

        $data = $this->api->getData('GetLembagaPengangkat', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetLembagaPengangkat', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $lembagaPangkat->insert($value);
        }
        return response()->setJSON($data);
    }

    public function level_wilayah()
    {
        $levelWilayah = new \App\Models\LevelWilayahModel();

        $data = $this->api->getData('GetLevelWilayah', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetLevelWilayah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $levelWilayah->insert($value);
        }
        return response()->setJSON($data);
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

        return response()->setJSON($data);
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

        return response()->setJSON($data);
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

        return response()->setJSON($data);
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

        return response()->setJSON($data);
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

        return response()->setJSON($data);
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

        return response()->setJSON($data);
    }

    public function aktivitas_kuliah()
    {
        $aktivitasMahasiswa = new \App\Models\PerkuliahanMahasiswaModel();
        $riwayatPendidikan = new \App\Models\RiwayatPendidikanMahasiswaModel();

        $data = $this->api->getData('GetAktivitasKuliahMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetAktivitasKuliahMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $riwayat = $riwayatPendidikan->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first();
            $value->id_riwayat_pendidikan = $riwayat->id;
            $aktivitasMahasiswa->insert($value);
        }
        return response()->setJSON($data);
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
            $value->id = Uuid::uuid4()->toString();
            $tanggal = explode('-', $value->tanggal_sk_tugas);
            $value->tanggal_sk_tugas = $value->tanggal_sk_tugas != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $aktivitasMahasiswa->insert($value);
        }
        return response()->setJSON($data);
    }

    public function anggota_aktivitas_mahasiswa()
    {
        $anggotaAktivitasMahasiswa = new \App\Models\AnggotaAktivitasModel();

        $data = $this->api->getData('GetListAnggotaAktivitasMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListAnggotaAktivitasMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $anggotaAktivitasMahasiswa->insert($value);
        }
        return response()->setJSON($data);
    }

    public function bimbing_mahasiswa()
    {
        $bimbingMahasiswa = new \App\Models\BimbingMahasiswaModel();

        $data = $this->api->getData('GetListBimbingMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListBimbingMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $bimbingMahasiswa->insert($value);
        }
        return response()->setJSON($data);
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

        return response()->setJSON($data);
    }


    public function mahasiswa()
    {
        $mahasiswa = new \App\Models\MahasiswaModel();

        $data = $this->api->getData('GetBiodataMahasiswa', $this->token);
        $a = date('Y-m-d', strtotime(str_replace('-', '/', '10-10-2021')));
        foreach ($data->data as $key => $value) {
            $myuuid = Uuid::uuid4();
            $value->id = $myuuid->toString();
            $tanggal = explode('-', $value->tanggal_lahir);
            $value->tanggal_lahir = $value->tanggal_lahir != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $tanggal = explode('-', $value->tanggal_lahir_ayah);
            $value->tanggal_lahir_ayah = $value->tanggal_lahir_ayah != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $tanggal = explode('-', $value->tanggal_lahir_ibu);
            $value->tanggal_lahir_ibu = $value->tanggal_lahir_ibu != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $tanggal = explode('-', $value->tanggal_lahir_wali);
            $value->tanggal_lahir_wali = $value->tanggal_lahir_wali != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $mahasiswa->insert($value);
        }
        return response()->setJSON($data);
    }

    public function riwayat_pendidikan()
    {
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        
        $mahasiswa = new \App\Models\MahasiswaModel();
        // $dataMahasiswa = $mahasiswa->findAll();
        // $a = array_search('001a955e-2dc9-4264-8b60-00f4edad0246', $dataMahasiswa);

        $data = $this->api->getData('GetListRiwayatPendidikanMahasiswa', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListRiwayatPendidikanMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $model = new \App\Entities\RiwayatPendidikanMahasiswa();
            $value->id = Uuid::uuid4()->toString();
            $value->angkatan = $value->nim;
            $value->id_mahasiswa = $mahasiswa->where('id_mahasiswa', $value->id_mahasiswa)->first()->id;
            $model->fill((array)$value);
            $riwayat->insert($model);
        }
        return response()->setJSON($data);
    }

    public function ta()
    {
        $ta = new \App\Models\TahunAjaranModel();

        $data = $this->api->getData('GetTahunAjaran', $this->token);
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetTahunAjaran', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $ta->insert($value);
        }
        return response()->setJSON($data);
    }

    public function semester()
    {
        $semester = new \App\Models\SemesterModel();

        $data = $this->api->getData('GetSemester', $this->token, "id_tahun_ajaran>=2015");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetSemester', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $semester->insert($value);
        }
        return response()->setJSON($data);
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
        return response()->setJSON($data);
    }

    public function matakuliah()
    {
        $matakuliah = new \App\Models\MatakuliahModel();

        $data = $this->api->getData('GetDetailMataKuliah', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDetailMataKuliah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $value->status_sync = "sudah sync";
            $matakuliah->insert($value);
        }
        return response()->setJSON($data);
    }

    public function kurikulum_detail()
    {
        $detail = new \App\Models\MatakuliahKurikulumModel();
        $kurikulum = new \App\Models\KurikulumModel();
        $matakuliah = new \App\Models\MatakuliahModel();

        $data = $this->api->getData('GetMatkulKurikulum', $this->token, "nama_kurikulum not in('TI-2011')");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetMatkulKurikulum', $this->token, "nama_kurikulum not in('TI-2011')");
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $itemKurikulum = $kurikulum->where('id_kurikulum', $value->id_kurikulum)->first();
            $itemMatakuliah = $matakuliah->where('id_matkul', $value->id_matkul)->first();
            $value->kurikulum_id = $itemKurikulum->id;
            $value->matakuliah_id = $itemMatakuliah->id;
            $value->status_sync = "sudah sync";
            $detail->insert($value);
        }
        return response()->setJSON($data);
    }

    public function kelas_kuliah()
    {
        $kelas = new \App\Models\KelasKuliahModel();
        $matakuliah = new \App\Models\MatakuliahModel();

        $data = $this->api->getData('GetDetailKelasKuliah', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDetailKelasKuliah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $tanggal = explode('-', $value->tanggal_mulai_efektif);
            $value->tanggal_mulai_efektif = $value->tanggal_mulai_efektif != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $tanggal = explode('-', $value->tanggal_akhir_efektif);
            $value->tanggal_akhir_efektif = $value->tanggal_akhir_efektif != null ? date('Y-m-d', strtotime($tanggal[1] . '/' . $tanggal[0] . '/' . $tanggal[2])) : null;
            $itemMatakuliah = $matakuliah->where('id_matkul', $value->id_matkul)->first();
            $value->matakuliah_id = $itemMatakuliah->id;
            $value->status_sync = "sudah sync";
            $kelas->insert($value);
        }
        return response()->setJSON($data);
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
            $dosen->insert($value);
        }
        return response()->setJSON($data);
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
            $penugasan->insert($value);
        }
        return response()->setJSON($data);
    }

    public function jenis_evaluasi()
    {
        $jenis_evaluasi = new \App\Models\JenisEvaluasiModel();

        $data = $this->api->getData('GetJenisEvaluasi', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisEvaluasi', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $jenis_evaluasi->insert($value);
        }
        return response()->setJSON($data);
    }

    public function pengajar_kelas()
    {
        $pengajar = new \App\Models\DosenPengajarKelasModel();
        $kelasKuliah = new \App\Models\KelasKuliahModel();

        $data = $this->api->getData('GetDosenPengajarKelasKuliah', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDosenPengajarKelasKuliah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $value->status_sync = 'sudah sync';
            $itemKelasKuliah = $kelasKuliah->where('id_kelas_kuliah', $value->id_kelas_kuliah)->first();
            $value->kelas_kuliah_id = $itemKelasKuliah->id;
            $pengajar->insert($value);
        }
        return response()->setJSON($data);
    }

    public function peserta_kelas()
    {
        $pesertaKelas = new \App\Models\PesertaKelasModel();
        $mahasiswa = new \App\Models\MahasiswaModel();
        $kelasKuliah = new \App\Models\KelasKuliahModel();
        $matakuliah = new \App\Models\MatakuliahModel();
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();

        $data = $this->api->getData('GetPesertaKelasKuliah', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetPesertaKelasKuliah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $itemRiwayat =  $riwayat->where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)->first();
            $value->id_riwayat_pendidikan = $itemRiwayat->id;
            $itemKelasKuliah =  $kelasKuliah->where('id_kelas_kuliah', $value->id_kelas_kuliah)->first();
            $value->kelas_kuliah_id = $itemKelasKuliah->id;
            $itemMahasiswa =  $mahasiswa->where('id_mahasiswa', $value->id_mahasiswa)->first();
            $value->mahasiswa_id = $itemMahasiswa->id;
            $itemMatakuliah =  $matakuliah->where('id_matkul', $value->id_matkul)->first();
            $value->matakuliah_id = $itemMatakuliah->id;
            $pesertaKelas->insert($value);
        }
        return response()->setJSON($data);
    }

    public function nilai_kelas()
    {
        $pesertaKelas = new \App\Models\PesertaKelasModel();

        $data = $this->api->getData('GetDetailNilaiPerkuliahanKelas', $this->token, "");
        if ($data->error_code == 100) {
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDetailNilaiPerkuliahanKelas', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $pesertaKelas->insert($value);
        }
        return response()->setJSON($data);
    }
}

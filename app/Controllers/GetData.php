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
    public function __construct() {
        $this->api = new Rest();
        $this->pt = new PerguruanTinggiModel();
        $this->token = $this->api->getToken()->data->token;
    }
    public function index()
    {
        $data = $this->api->getData('GetProfilPT','');
        $this->pt->insert($data->data[0]);
        return response()->setJSON($data);
    }

    public function prodi()
    {
        $prodi = new \App\Models\ProdiModel();
        
        $data = $this->api->getData('GetProdi', $this->token);
        if($data->error_code==100){
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
        if($data->error_code==100){
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
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetJenisTinggal', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $jenis_tinggal->insert($value);
        }
        return response()->setJSON($data);
    }


    public function mahasiswa()
    {
        $mahasiswa = new \App\Models\MahasiswaModel();
        
        $data = $this->api->getData('GetBiodataMahasiswa', '');
        $a = date('Y-m-d', strtotime(str_replace('-','/', '10-10-2021')));
        foreach ($data->data as $key => $value) {
            $myuuid = Uuid::uuid4();
            $value->id = $myuuid->toString();
            $value->tanggal_lahir = $value->tanggal_lahir != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tanggal_lahir))): null;
            $value->tanggal_lahir_ayah =  $value->tanggal_lahir_ayah != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tanggal_lahir_ayah))):null;
            $value->tanggal_lahir_ibu = $value->tanggal_lahir_ibu != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tanggal_lahir_ibu))): null;
            $value->tanggal_lahir_wali = $value->tanggal_lahir_wali !=null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tanggal_lahir_wali))):null;
            $mahasiswa->insert($value);
        }
        return response()->setJSON($data);
    }

    public function riwayat_pendidikan()
    {
        $riwayat = new \App\Models\RiwayatPendidikanMahasiswaModel();
        
        $data = $this->api->getData('GetListRiwayatPendidikanMahasiswa', $this->token);
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListRiwayatPendidikanMahasiswa', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $value->angkatan = $value->nim;
            $riwayat->insert($value);
        }
        return response()->setJSON($data);
    }

    public function ta()
    {
        $ta = new \App\Models\TahunAjaranModel();
        
        $data = $this->api->getData('GetTahunAjaran', $this->token);
        if($data->error_code==100){
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
        if($data->error_code==100){
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
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListKurikulum', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $kurikulum->insert($value);
        }
        return response()->setJSON($data);
    }

    public function matakuliah()
    {
        $matakuliah = new \App\Models\MatakuliahModel();
        
        $data = $this->api->getData('GetDetailMataKuliah', $this->token, "");
        if($data->error_code==100){
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
        
        $data = $this->api->getData('GetMatkulKurikulum', $this->token, "nama_kurikulum not in('TI-2011')");
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDetailMataKuliah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $value->tgl_create = $value->tgl_create != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tgl_create))): null;
            $value->status_sync = "sudah sync";
            $detail->insert($value);
        }
        return response()->setJSON($data);
    }

    public function kelas_kuliah()
    {
        $kelas = new \App\Models\KelasKuliahModel();
        
        $data = $this->api->getData('GetDetailKelasKuliah', $this->token, "");
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDetailKelasKuliah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $value->tanggal_mulai_efektif = $value->tanggal_mulai_efektif != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tanggal_mulai_efektif))): null;
            $value->tanggal_akhir_efektif = $value->tanggal_akhir_efektif != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tanggal_akhir_efektif))): null;
            $value->status_sync = "sudah sync";
            $kelas->insert($value);
        }
        return response()->setJSON($data);
    }

    public function dosen()
    {
        $dosen = new \App\Models\DosenModel();
        
        $data = $this->api->getData('DetailBiodataDosen', $this->token, "");
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('DetailBiodataDosen', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $tanggal = explode('-', $value->tanggal_lahir);
            $value->tanggal_lahir = $value->tanggal_lahir != null ? date('Y-m-d', strtotime($tanggal[1].'/'.$tanggal[0].'/'.$tanggal[2])): null;
            $dosen->insert($value);
        }
        return response()->setJSON($data);
    }

    public function penugasan_dosen()
    {
        $penugasan = new \App\Models\PenugasanDosenModel();
        
        $data = $this->api->getData('GetListPenugasanDosen', $this->token, "");
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetListPenugasanDosen', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->tanggal_surat_tugas = $value->tanggal_surat_tugas != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->tanggal_surat_tugas))): null;
            $value->mulai_surat_tugas = $value->mulai_surat_tugas != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->mulai_surat_tugas))): null;
            $value->tgl_create = $value->tgl_create != null ? date('Y-m-d', strtotime(str_replace('-','/', $value->mulai_surat_tugas))): null;
            $penugasan->insert($value);
        }
        return response()->setJSON($data);
    }

    public function jenis_evaluasi()
    {
        $jenis_evaluasi = new \App\Models\JenisEvaluasiModel();
        
        $data = $this->api->getData('GetJenisEvaluasi', $this->token, "");
        if($data->error_code==100){
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
        
        $data = $this->api->getData('GetDosenPengajarKelasKuliah', $this->token, "");
        if($data->error_code==100){
            $this->token = $this->api->getToken()->data->token;
            $data = $this->api->getData('GetDosenPengajarKelasKuliah', $this->token);
        }
        foreach ($data->data as $key => $value) {
            $value->id = Uuid::uuid4()->toString();
            $pengajar->insert($value);
        }
        return response()->setJSON($data);
    }
}

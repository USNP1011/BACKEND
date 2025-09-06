<?php

namespace App\Controllers\Rest\Mahasiswa;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahKurikulumModel;
use App\Models\NilaiTransferModel;
use App\Models\PerkuliahanMahasiswaModel;
use App\Models\PesertaKelasModel;
use App\Models\RiwayatPendidikanMahasiswaModel;
use App\Models\TranskripModel;
use CodeIgniter\RESTful\ResourceController;

class Mahasiswa extends ResourceController
{
    public function __construct()
    {
        helper('semester');
    }

    public function byUserId($id = null)
    {
        return $this->respond([
            'status' => true,
            'data' => getProfile()
        ]);
    }

    public function riwayatPendidikan()
    {
        $profile = getProfile();
        $object = new RiwayatPendidikanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id', $profile->id_riwayat_pendidikan)->findAll()
        ]);
    }

    public function nilaiTransfer()
    {
        $profile = getProfile();
        $object = new NilaiTransferModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->findAll()
        ]);
    }

    public function krsm()
    {
        $profile = getProfile();
        $semester = getSemesterAktif();
        $object = new PesertaKelasModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select("kelas_kuliah.*")
                ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)
                ->where('kelas_kuliah.id_semester', $semester->id)
                ->findAll()
        ]);
    }

    public function aktivitasKuliah()
    {
        $profile = getProfile();
        $object = new PerkuliahanMahasiswaModel();
        return $this->respond([
            'status' => true,
            'data' => $object->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->orderBy('id_semester', 'asc')->findAll()
        ]);
    }

    public function transkripSementara($id = null)
    {
        try {
            $profile = $id === null ? getProfile() : getProfileByMahasiswa($id);

            /* ---------- 2. Semua mata kuliah kurikulum ---------- */
            $mkKur = new \App\Models\MatakuliahKurikulumModel();
            $kurikulum = $mkKur
                ->select('matakuliah_kurikulum.id,
                      matakuliah_kurikulum.kurikulum_id,
                      matakuliah_kurikulum.matakuliah_id,
                      matakuliah_kurikulum.semester,
                      matakuliah.nama_mata_kuliah,
                      matakuliah.kode_mata_kuliah,
                      matakuliah.sks_mata_kuliah')
                ->join('matakuliah', 'matakuliah.id = matakuliah_kurikulum.matakuliah_id', 'left')
                ->where('matakuliah_kurikulum.kurikulum_id', $profile->kurikulum_id)
                ->orderBy('semester', 'asc')
                ->findAll();

            /* ---------- 3. Semua nilai transkrip (ambil yg terbaik per matakuliah) ---------- */
            $transkrip = new \App\Models\TranskripModel();
            $rawTrx    = $transkrip
                ->select('matakuliah_id,
                      nilai_angka,
                      nilai_huruf,
                      nilai_indeks')
                ->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)
                ->orderBy('nilai_indeks', 'desc')   // nilai tertinggi di atas
                ->findAll();

            $trxMap = [];                          // key = matakuliah_id
            foreach ($rawTrx as $t) {
                if (!isset($trxMap[$t->matakuliah_id])) {   // simpan hanya yg pertama (tertinggi)
                    $trxMap[$t->matakuliah_id] = $t;
                }
            }

            /* ---------- 4. Semua nilai dari peserta_kelas (jaga-jaga belum di transkrip) ---------- */
            $peserta = new \App\Models\PesertaKelasModel();
            $rawPst  = $peserta
                ->select('peserta_kelas.nilai_angka,
                      peserta_kelas.nilai_huruf,
                      peserta_kelas.nilai_indeks,
                      kelas_kuliah.matakuliah_id')
                ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id', 'left')
                ->where('peserta_kelas.id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)
                ->findAll();

            $pstMap = [];
            foreach ($rawPst as $p) {
                if (!isset($pstMap[$p->matakuliah_id])) {   // ambil salah satu entry saja
                    $pstMap[$p->matakuliah_id] = $p;
                }
            }

            /* ---------- 5. Gabungkan hasil ---------- */
            foreach ($kurikulum as &$k) {
                $mId = $k->matakuliah_id;

                if (isset($trxMap[$mId])) {          // ada di transkrip → pakai nilai terbaik
                    $best = $trxMap[$mId];
                } elseif (isset($pstMap[$mId])) {    // belum di transkrip, pakai nilai kelas
                    $best = $pstMap[$mId];
                } else {                             // belum sama sekali
                    $best = null;
                }

                if ($best) {
                    $k->nilai_angka = $best->nilai_angka;
                    $k->nilai_huruf = $best->nilai_huruf;
                    $k->nilai_indeks = $best->nilai_indeks;
                } else {
                    $k->nilai_angka = $k->nilai_huruf = $k->nilai_indeks = null;
                }
            }

            return $this->respond([
                'status' => true,
                'data'   => $kurikulum
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage(),
            ]);

        }
        /* ---------- 1. Profile mahasiswa ---------- */
    }

    public function transkrip($id = null)
    {
        if (is_null($id)) $profile = getProfile();
        else $profile = getProfileByMahasiswa($id);
        $object = new TranskripModel();
        return $this->respond([
            'status' => true,
            'data' => $object->select('matakuliah.kode_mata_kuliah, matakuliah.nama_mata_kuliah, matakuliah.sks_mata_kuliah, transkrip.nilai_angka, transkrip.nilai_huruf, transkrip.nilai_indeks, (matakuliah.sks_mata_kuliah*transkrip.nilai_indeks) as nxsks')->join('matakuliah', 'matakuliah.id=transkrip.matakuliah_id', 'left')->where('id_riwayat_pendidikan', $profile->id_riwayat_pendidikan)->findAll()
        ]);
    }



    public function update($id = null)
    {
        try {
            $item = $this->request->getJSON();
            $object = new MahasiswaModel();
            $model = new EntitiesMahasiswa();
            $model->fill((array) $item);
            $object->save($item);
            return $this->respondUpdated([
                'status' => true,
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return $this->fail([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}

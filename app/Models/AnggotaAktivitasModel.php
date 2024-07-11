<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaAktivitasModel extends Model
{
    protected $table = 'anggota_aktivitas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\AnggotaAktivitasMahasiswaEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_anggota',
        'aktivitas_mahasiswa_id',
        'id_riwayat_pendidikan',
        'jenis_peran',
        'nama_jenis_peran',
        'status_sync',
        'sync_at'
    ];
    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    function getByAktivitas($id = null)
    {
        return $this->db->query("SELECT
            `anggota_aktivitas`.*,
            `riwayat_pendidikan_mahasiswa`.`nim`,
            `mahasiswa`.`nama_mahasiswa`,
            `aktivitas_mahasiswa`.`id_prodi`,
            `prodi`.`kode_program_studi`,
            `prodi`.`nama_program_studi`,
            peran_peserta.nama_jenis_peran
            FROM
            `anggota_aktivitas`
            LEFT JOIN `aktivitas_mahasiswa` ON `aktivitas_mahasiswa`.`id` =
            `anggota_aktivitas`.`aktivitas_mahasiswa_id`
            LEFT JOIN `peran_peserta` ON `anggota_aktivitas`.`jenis_peran` =
            `peran_peserta`.`jenis_peran`
            LEFT JOIN `riwayat_pendidikan_mahasiswa`
            ON `anggota_aktivitas`.`id_riwayat_pendidikan` =
            `riwayat_pendidikan_mahasiswa`.`id`
            LEFT JOIN `semester` ON `semester`.`id_semester` =
            `aktivitas_mahasiswa`.`id_semester`
            LEFT JOIN `mahasiswa` ON `riwayat_pendidikan_mahasiswa`.`id_mahasiswa` =
            `mahasiswa`.`id`
            LEFT JOIN `prodi` ON `prodi`.`id_prodi` = `aktivitas_mahasiswa`.`id_prodi`
            WHERE aktivitas_mahasiswa_id = '".$id."' AND anggota_aktivitas.deleted_at IS NULL")->getResult();
    }
}

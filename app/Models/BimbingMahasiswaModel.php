<?php

namespace App\Models;

use CodeIgniter\Model;

class BimbingMahasiswaModel extends Model
{
    protected $table = 'bimbing_mahasiswa';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\BimbingMahasiswaEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_bimbing_mahasiswa',
        'aktivitas_mahasiswa_id',
        'id_kategori_kegiatan',
        'id_dosen',
        'pembimbing_ke',
        'judul',
        'status_sync',
        'sync_at'
    ];
    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    function getById($id = null)
    {
        $this->db->query("SELECT
                `bimbing_mahasiswa`.*,
                `kategori_kegiatan`.`nama_kategori_kegiatan`,
                `dosen`.`nama_dosen`,
                `dosen`.`nidn`,
                `aktivitas_mahasiswa`.`judul`
                FROM
                `bimbing_mahasiswa`
                LEFT JOIN `aktivitas_mahasiswa`
                ON `bimbing_mahasiswa`.`aktivitas_mahasiswa_id` = `aktivitas_mahasiswa`.`id`
                LEFT JOIN `kategori_kegiatan` ON `kategori_kegiatan`.`id_kategori_kegiatan` =
                `bimbing_mahasiswa`.`id_kategori_kegiatan`
                LEFT JOIN `dosen` ON `dosen`.`id_dosen` = `bimbing_mahasiswa`.`id_dosen`
                WHERE bimbing_mahasiswa.id='" . $id . "'")->getRowObject();
    }
}

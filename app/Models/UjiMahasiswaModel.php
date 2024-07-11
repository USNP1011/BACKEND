<?php namespace App\Models;

use CodeIgniter\Model;

class UjiMahasiswaModel extends Model
{
    protected $table = 'uji_mahasiswa';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\UjiMahasiswaEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_uji',
		'aktivitas_mahasiswa_id',
		'id_kategori_kegiatan',
		'id_dosen',
		'penguji_ke',
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
        return $this->db->query("SELECT
                `bimbing_mahasiswa`.*,
                `kategori_kegiatan`.`nama_kategori_kegiatan`,
                `dosen`.`nama_dosen`,
                `dosen`.`nidn`,
                `aktivitas_mahasiswa`.`judul`
                FROM
                `uji_mahasiswa`
                LEFT JOIN `aktivitas_mahasiswa`
                ON `bimbing_mahasiswa`.`aktivitas_mahasiswa_id` = `aktivitas_mahasiswa`.`id`
                LEFT JOIN `kategori_kegiatan` ON `kategori_kegiatan`.`id_kategori_kegiatan` =
                `bimbing_mahasiswa`.`id_kategori_kegiatan`
                LEFT JOIN `dosen` ON `dosen`.`id_dosen` = `bimbing_mahasiswa`.`id_dosen`
                WHERE bimbing_mahasiswa.id='" . $id . "' AND uji_mahasiswa.deleted_at IS NULL")->getRowObject();
    }

    function getByAktivitas($id = null)
    {
        return $this->db->query("SELECT
                `uji_mahasiswa`.*,
                `kategori_kegiatan`.`nama_kategori_kegiatan`,
                `dosen`.`nama_dosen`,
                `dosen`.`nidn`,
                `aktivitas_mahasiswa`.`judul`
                FROM
                `uji_mahasiswa`
                LEFT JOIN `aktivitas_mahasiswa`
                ON `uji_mahasiswa`.`aktivitas_mahasiswa_id` = `aktivitas_mahasiswa`.`id`
                LEFT JOIN `kategori_kegiatan` ON `kategori_kegiatan`.`id_kategori_kegiatan` =
                `uji_mahasiswa`.`id_kategori_kegiatan`
                LEFT JOIN `dosen` ON `dosen`.`id_dosen` = `uji_mahasiswa`.`id_dosen`
                WHERE aktivitas_mahasiswa_id='" . $id . "' AND uji_mahasiswa.deleted_at IS NULL")->getResult();
    }
}

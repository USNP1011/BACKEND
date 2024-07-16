<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\Mahasiswa';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_mahasiswa',
        'nama_mahasiswa',
		'jenis_kelamin',
		'jalan',
		'rt',
		'rw',
		'dusun',
		'kelurahan',
		'kode_pos',
		'nisn',
		'nik',
		'tempat_lahir',
		'tanggal_lahir',
		'nama_ayah',
		'tanggal_lahir_ayah',
		'nik_ayah',
		'id_pendidikan_ayah',
		'id_pekerjaan_ayah',
		'id_penghasilan_ayah',
		'id_kebutuhan_khusus_ayah',
		'nama_ibu_kandung',
		'tanggal_lahir_ibu',
		'nik_ibu',
		'id_pendidikan_ibu',
		'id_pekerjaan_ibu',
		'id_penghasilan_ibu',
		'id_kebutuhan_khusus_ibu',
		'nama_wali',
		'tanggal_lahir_wali',
		'id_pendidikan_wali',
		'id_pekerjaan_wali',
		'id_penghasilan_wali',
		'id_kebutuhan_khusus_mahasiswa',
		'telepon',
		'handphone',
		'email',
		'penerima_kps',
		'nomor_kps',
		'no_kps',
		'npwp',
		'id_wilayah',
		'id_jenis_tinggal',
		'nama_jenis_tinggal',
		'id_agama',
		'nama_agama',
		'id_alat_transportasi',
		'nama_alat_transportasi',
		'nama_wilayah',
		'kewarganegaraan',
		'nama_pendidikan_ayah',
		'nama_pendidikan_ibu',
		'nama_pendidikan_wali',
		'nama_pekerjaan_ayah',
		'nama_pekerjaan_ibu',
		'nama_pekerjaan_wali',
		'nama_penghasilan_ayah',
		'nama_penghasilan_ibu',
		'nama_penghasilan_wali',
		'nama_kebutuhan_khusus_ayah',
		'nama_kebutuhan_khusus_ibu',
		'nama_kebutuhan_khusus_wali',
		'nama_kebutuhan_khusus_mahasiswa',
		'oap',
		'suku',
		'id_user',
		'sync_at',
		'status_sync'
    ];

    protected bool $allowEmptyInserts = false;
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}

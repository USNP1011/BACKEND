<?php namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\Mahasiswa';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nama_dosen',
		'tempat_lahir',
		'tanggal_lahir',
		'jenis_kelamin',
		'id_agama',
		'id_status_aktif',
		'nidn',
		'nama_ibu',
		'nik',
		'nip',
		'npwp',
		'id_jenis_sdm',
		'no_sk_cpns',
		'tanggal_sk_cpns',
		'no_sk_pengangkatan',
		'mulai_sk_pengangkatan',
		'id_lembaga_kepangkatan',
		'id_pangkat_golongan',
		'id_sumber_gaji',
		'jalan',
		'dusun',
		'rt',
		'rw',
		'ds_kel',
		'kode_pos',
		'id_wilayah',
		'nama_wilayah',
		'telepon',
		'handphone',
		'email',
		'status_pernikahan',
		'nama_suami_istri',
		'nip_suami_istri',
		'tanggal_mulai_pns',
		'id_pekerjaan_suami_istri',
		'mampu_handle_kebutuhan_khusus',
		'mampu_handle_braille',
		'mampu_handle_bahasa_isyarat',
		'nama_agama',
		'nama_status_aktif',
		'nama_ibu_kandung',
		'nama_jenis_sdm',
		'id_lembaga_pengangkatan',
		'nama_lembaga_pengangkatan',
		'nama_pangkat_golongan',
		'nama_sumber_gaji',
		'nama_pekerjaan_suami_istri',
		'id_user'
    ];
}

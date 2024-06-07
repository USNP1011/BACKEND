<?php namespace App\Models;

use CodeIgniter\Model;

class MatakuliahModel extends Model
{
    protected $table = 'matakuliah';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
		'id_matkul',
        'kode_mata_kuliah',
		'nama_mata_kuliah',
		'id_prodi',
		'id_jenis_mata_kuliah',
		'id_kelompok_mata_kuliah',
		'sks_mata_kuliah',
		'sks_tatap_muka',
		'sks_praktek',
		'sks_praktek_lapangan',
		'id_jenjang_didik',
		'sks_simulasi',
		'metode_kuliah',
		'ada_sap',
		'ada_silabus',
		'ada_bahan_ajar',
		'ada_acara_praktek',
		'ada_diktat',
		'tanggal_mulai_efektif',
		'tanggal_selesai_efektif',
		'id_jenj_didik',
		'tgl_create',
		'jns_mk',
		'kel_mk',
		'nama_program_studi',
		'status_sync',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at'
    ];
}

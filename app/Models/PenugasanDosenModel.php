<?php namespace App\Models;

use CodeIgniter\Model;

class PenugasanDosenModel extends Model
{
    protected $table = 'penugasan_dosen';
    protected $primaryKey = 'id_registrasi_dosen'; 
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;   
    protected $allowedFields = [
        'jk',
		'id_dosen',
		'nama_dosen',
		'nidn',
		'id_perguruan_tinggi',
		'nama_perguruan_tinggi',
		'id_prodi',
		'nama_program_studi',
		'nomor_surat_tugas',
		'tanggal_surat_tugas',
		'mulai_surat_tugas',
		'tgl_create',
		'tgl_ptk_keluar',
		'id_stat_pegawai',
		'id_jns_keluar',
		'id_ikatan_kerja',
		'a_sp_homebase'
    ];
}

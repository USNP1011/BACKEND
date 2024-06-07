<?php namespace App\Models;

use CodeIgniter\Model;

class DosenPengajarKelasModel extends Model
{
    protected $table = 'dosen_pengajar_kelas';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_aktivitas_mengajar',
        'id_registrasi_dosen',
        'id_dosen', 
        'nidn',
        'nama_dosen',
		'id_kelas_kuliah',
        'nama_kelas_kuliah',
		'id_substansi',
		'sks_substansi_total',
		'rencana_minggu_pertemuan',
		'realisasi_minggu_pertemuan',
		'id_jenis_evaluasi',
        'nama_jenis_evaluasi',
        'id_prodi',
        'id_semester'
    ];
}

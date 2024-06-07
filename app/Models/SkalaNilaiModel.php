<?php namespace App\Models;

use CodeIgniter\Model;

class SkalaNilaiModel extends Model
{
    protected $table = 'skala_nilai';
    protected $primaryKey = 'id'; 
	protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;   
    protected $allowedFields = [
		'id_bobot_nilai',
        'id_prodi',
		'nama_program_studi',
		'nilai_huruf',
		'nilai_indeks',
		'bobot_minimum',
		'bobot_maksimum',
		'tanggal_mulai_efektif',
		'tanggal_akhir_efektif',
		'created_at',
		'updated_at',
		'deleted_at',
		'sync_at',
		'tgl_create',
		'status_sync'
    ];
}

<?php namespace App\Models;

use CodeIgniter\Model;

class AktivitasMahasiswaModel extends Model
{
    protected $table = 'aktivitas_mahasiswa';
    protected $primaryKey = 'id';    
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_aktivitas',
		'jenis_anggota',
		'id_jenis_aktivitas',
		'id_prodi',
		'id_semester',
		'judul',
		'keterangan',
		'lokasi',
		'sk_tugas',
		'status_sync'
    ];
	protected bool $allowEmptyInserts = false;
}

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
		'nama_jenis_anggota',
		'id_jenis_aktivitas_mahasiswa',
		'id_prodi',
		'id_semester',
		'judul',
		'keterangan',
		'lokasi',
		'sk_tugas',
		'tanggal_sk_tugas',
		'tanggal_mulai_efektif',
		'tanggal_selesai_efektif',
		'status_sync',
		'sync_at'
    ];
	protected bool $allowEmptyInserts = false;

	protected $beforeDelete = ['beforeDeleteCallback'];
	protected $beforeUpdate = ['beforeUpdateCallback'];

	protected function beforeDeleteCallback(array $data)
    {
		$this->update($data['id'][0], ['status_sync'=>null]);
    }

	protected function beforeUpdateCallback(array $data)
    {
		$this->update($data['id'][0], ['status_sync'=>null]);
    }
}

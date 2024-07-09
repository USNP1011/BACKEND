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

<?php namespace App\Models;

use CodeIgniter\Model;

class KelasKuliahModel extends Model
{
    protected $table = 'kelas_kuliah';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\KelasKuliahEntity';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_kelas_kuliah',
		'id_semester',
		'id_prodi',
		'matakuliah_id',
		'nama_semester',
		'nama_program_studi',
		'nama_kelas_kuliah',
		'kode_mata_kuliah',
		'nama_mata_kuliah',
		'bahasan',
		'tanggal_mulai_efektif',
		'tanggal_akhir_efektif',
		'lingkup',
		'mode',
		'kapasitas',
		'hari',
		'jam_mulai',
		'jam_selesai',
		'status_sync',
		'sync_at'
    ];

	protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

	protected $beforeDelete = ['beforeDeleteCallback'];

	protected function beforeDeleteCallback(array $data)
    {
		$this->update($data['id'][0], ['sync_at'=>null, 'status_sync'=>null]);
    }
}

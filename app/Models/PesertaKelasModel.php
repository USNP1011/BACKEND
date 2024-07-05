<?php namespace App\Models;

use CodeIgniter\Model;

class PesertaKelasModel extends Model
{
    protected $table = 'peserta_kelas';
    protected $primaryKey = 'id';    
	protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\PesertaKelasEntity';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
		'kelas_kuliah_id',
		'nama_kelas_kuliah',
		'id_riwayat_pendidikan',
		'mahasiswa_id',
		'nim',
		'nama_mahasiswa',
		'matakuliah_id',
		'kode_mata_kuliah',
		'nama_mata_kuliah',
		'id_prodi',
		'nama_program_studi',
		'nilai_angka',
		'nilai_huruf',
		'nilai_indeks',
		'sync_at',
		'status_sync',
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

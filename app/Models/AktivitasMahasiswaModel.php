<?php

namespace App\Models;

use CodeIgniter\Model;

class AktivitasMahasiswaModel extends Model
{
	protected $table = 'aktivitas_mahasiswa';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType       = 'object';
	protected $useSoftDeletes   = true;
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
	protected $useTimestamps = true;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';

	function getById($id = null)
	{
		return $this->db->query("SELECT
			`aktivitas_mahasiswa`.*,
			`jenis_aktivitas`.`nama_jenis_aktivitas_mahasiswa`,
			`prodi`.`nama_program_studi`,
			`semester`.`nama_semester`
			FROM
			`aktivitas_mahasiswa`
			LEFT JOIN `jenis_aktivitas`
			ON `aktivitas_mahasiswa`.`id_jenis_aktivitas_mahasiswa` =
			`jenis_aktivitas`.`id_jenis_aktivitas_mahasiswa`
			LEFT JOIN `prodi` ON `prodi`.`id_prodi` = `aktivitas_mahasiswa`.`id_prodi`
			LEFT JOIN `semester` ON `semester`.`id_semester` =
			`aktivitas_mahasiswa`.`id_semester` 
			WHERE aktivitas_mahasiswa.id='" . $id . "' AND aktivitas_mahasiswa.deleted_at IS NULL")->getRowObject();
	}
}

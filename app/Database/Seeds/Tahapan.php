<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Tahapan extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'=>1,
                'tahapan'=>'Dosen Wali',
                'urutan'=>"1"
            ],
            [
                'id'=>2,
                'tahapan'=>'Kaprodi',
                'urutan'=>'2'
            ],
            [
                'id'=>3,
                'tahapan'=>'Keuangan',
                'urutan'=>3
            ]
        ];

        $this->db->table('tahapan')->insertBatch($data);
    }
}

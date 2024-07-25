<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Kelas extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'=>1,
                'nama_kelas_kuliah'=>'A'
            ],
            [
                'id'=>2,
                'nama_kelas_kuliah'=>'B'
            ],
            [
                'id'=>3,
                'nama_kelas_kuliah'=>'C'
            ],
            [
                'id'=>4,
                'nama_kelas_kuliah'=>'D'
            ],
            [
                'id'=>5,
                'nama_kelas_kuliah'=>'E'
            ],
        ];

        foreach ($data as $key => $value) {
            $this->db->table('kelas')->insert($value);
        }
    }
}

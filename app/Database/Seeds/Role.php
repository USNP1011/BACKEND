<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Role extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'=>1,
                'role'=>'Admin'
            ],
            [
                'id'=>2,
                'role'=>'Dosen'
            ],
            [
                'id'=>3,
                'role'=>'Prodi'
            ],
            [
                'id'=>4,
                'role'=>'Mahasiswa'
            ],
            [
                'id'=>5,
                'role'=>'Keuangan'
            ],
        ];

        foreach ($data as $key => $value) {
            $this->db->table('role')->insert($value);
        }
    }
}

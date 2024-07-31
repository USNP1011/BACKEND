<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Ruang extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'=>1,
                'nama_ruangan'=>'IIIA'
            ],
            [
                'id'=>2,
                'nama_ruangan'=>'IIIB'
            ],
            [
                'id'=>3,
                'nama_ruangan'=>'IIIC'
            ],
            [
                'id'=>4,
                'nama_ruangan'=>'IIID'
            ],
            [
                'id'=>5,
                'nama_ruangan'=>'IVA'
            ],
        ];

        $this->db->table('ruangan')->insertBatch($data);
    }
}

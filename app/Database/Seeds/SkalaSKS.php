<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SkalaSKS extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'=>1,
                'ips_min'=>0,
                'ips_max'=>2.99,
                'sks_max'=>22
            ],
            [
                'id'=>2,
                'ips_min'=>3,
                'ips_max'=>4,
                'sks_max'=>24
            ]
        ];

        $this->db->table('skala_sks')->insertBatch($data);
    }
}

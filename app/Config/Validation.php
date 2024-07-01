<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $mahasiswa = [
        "nama_mahasiswa" => [
            "label" => "Nama Mahasiswa",
            "rules" => "required",
            "errors" => [
                "required" => "Nama Mahasiswa Tidak Boleh Kosong"
            ]
        ],
        "email" => [
            "label" => "Email",
            "rules" => "required|is_unique[mahasiswa.email]",
            "errors" => [
                "required" => "Email Tidak Boleh Kosong",
                "is_unique" => "Email yang sama sudah ada"
            ]
        ],
        "nik" => [
            "label" => "NIK",
            "rules" => "required|is_unique[mahasiswa.nik]|max_length[16]|min_length[16]",
            "errors" => [
                "required" => "NIK Tidak Boleh Kosong",
                "is_unique" => "NIK yang sama sudah ada",
                "max_length" => "NIK tidak boleh lebih dari 16 karakter",
                "min_length" => "NIK tidak boleh kurang dari 16 karakter",
            ]
        ],
    ];

    public array $matakuliah = [
        "kode_mata_kuliah" => [
            "label" => "Kode Matakuliah",
            "rules" => "required|is_unique[matakuliah.kode_mata_kuliah]",
            "errors" => [
                "required" => "kode_mata_kuliah Tidak Boleh Kosong",
                "is_unique" => "kode_mata_kuliah yang sama sudah ada"
            ]
        ],
        "nama_mata_kuliah" => [
            "label" => "Nama Matakuliah",
            "rules" => "required|is_unique[matakuliah.nama_mata_kuliah]",
            "errors" => [
                "required" => "nama_mata_kuliah Tidak Boleh Kosong",
                "is_unique" => "nama_mata_kuliah yang sama sudah ada"
            ]
        ],
        "id_prodi" => [
            "label" => "Id Program Studi",
            "rules" => "required",
            "errors" => [
                "required" => "id_prodi Tidak Boleh Kosong"
            ]
        ],
        "id_jenis_mata_kuliah" => [
            "label" => "Jenis Matakuliah",
            "rules" => "required",
            "errors" => [
                "required" => "id_jenis_mata_kuliah Tidak Boleh Kosong"
            ]
        ],
        "id_kelompok_mata_kuliah" => [
            "label" => "Kelompok Matakuliah",
            "rules" => "required",
            "errors" => [
                "required" => "id_kelompok_mata_kuliah Tidak Boleh Kosong"
            ]
        ],
        "sks_mata_kuliah" => [
            "label" => "SKS Matakuliah",
            "rules" => "required",
            "errors" => [
                "required" => "sks_mata_kuliah Tidak Boleh Kosong"
            ]
        ],
    ];

    public array $aktivitasMahasiswa = [
        "jenis_anggota" => [
            "label" => "Jenis Anggota",
            "rules" => "required",
            "errors" => [
                "required" => "Jenis Anggota Tidak Boleh Kosong"
            ]
        ],
        "id_jenis_aktivitas" => [
            "label" => "Id Jenis Aktivitas",
            "rules" => "required",
            "errors" => [
                "required" => "Id Jenis Aktivitas Tidak Boleh Kosong"
            ]
        ],
        "id_prodi" => [
            "label" => "Id Prodi",
            "rules" => "required",
            "errors" => [
                "required" => "Id Prodi Tidak Boleh Kosong"
            ]
        ],
        "id_semester" => [
            "label" => "Id Semester",
            "rules" => "required",
            "errors" => [
                "required" => "Id Semester Tidak Boleh Kosong"
            ]
        ],
        "judul" => [
            "label" => "Judul",
            "rules" => "required",
            "errors" => [
                "required" => "Judul Tidak Boleh Kosong"
            ]
        ]
    ];

    public array $userMahasiswa = [
        "username" => [
            "label" => "Email",
            "rules" => "required",
            "errors" => [
                "required" => "Email Mahasiswa Tidak Boleh Kosong karna dijadikan sebagai username"
            ]
        ],
        "email" => [
            "label" => "Email",
            "rules" => "required|is_unique[mahasiswa.email]",
            "errors" => [
                "required" => "Email Tidak Boleh Kosong",
                "is_unique" => "Email yang sama sudah ada"
            ]
        ],
    ];

    public array $kelasKuliah = [
        "id_prodi" => [
            "label" => "Id Prodi",
            "rules" => "required",
            "errors" => [
                "required" => "id_prodi Tidak Boleh Kosong"
            ]
        ],
        "id_semester" => [
            "label" => "Id Semester",
            "rules" => "required",
            "errors" => [
                "required" => "id_semester Tidak Boleh Kosong"
            ]
        ],
        "matakuliah_id" => [
            "label" => "Matakuliah Id",
            "rules" => "required",
            "errors" => [
                "required" => "matakuliah_id Tidak Boleh Kosong"
            ]
        ],
        "nama_kelas_kuliah" => [
            "label" => "Nama Kelas Kuliah",
            "rules" => "required",
            "errors" => [
                "required" => "nama_kelas_kuliah Tidak Boleh Kosong"
            ]
        ],
    ];

    public array $pesertaKelas = [
        "id_riwayat_pendidikan" => [
            "label" => "id_riwayat_pendidikan",
            "rules" => "required",
            "errors" => [
                "required" => "id_riwayat_pendidikan Tidak Boleh Kosong"
            ]
        ],
        "kelas_kuliah_id" => [
            "label" => "kelas_kuliah_id",
            "rules" => "required",
            "errors" => [
                "required" => "kelas_kuliah_id Tidak Boleh Kosong"
            ]
        ],
        "mahasiswa_id" => [
            "label" => "mahasiswa_id",
            "rules" => "required",
            "errors" => [
                "required" => "mahasiswa_id Tidak Boleh Kosong"
            ]
        ],
        "matakuliah_id" => [
            "label" => "matakuliah_id",
            "rules" => "required",
            "errors" => [
                "required" => "matakuliah_id Tidak Boleh Kosong"
            ]
        ],
        "id_prodi" => [
            "label" => "id_prodi",
            "rules" => "required",
            "errors" => [
                "required" => "id_prodi Tidak Boleh Kosong"
            ]
        ],
    ];

    public array $pengajarKelas = [
        "id_registrasi_dosen" => [
            "label" => "id_registrasi_dosen",
            "rules" => "required",
            "errors" => [
                "required" => "id_registrasi_dosen Tidak Boleh Kosong"
            ]
        ],
        "id_prodi" => [
            "label" => "id_prodi",
            "rules" => "required",
            "errors" => [
                "required" => "id_prodi Tidak Boleh Kosong"
            ]
        ],
        "id_semester" => [
            "label" => "id_semester",
            "rules" => "required",
            "errors" => [
                "required" => "id_semester Tidak Boleh Kosong"
            ]
        ],
        "kelas_kuliah_id" => [
            "label" => "kelas_kuliah_id",
            "rules" => "required",
            "errors" => [
                "required" => "kelas_kuliah_id Tidak Boleh Kosong"
            ]
        ],
        "id_jenis_evaluasi" => [
            "label" => "id_jenis_evaluasi",
            "rules" => "required",
            "errors" => [
                "required" => "id_jenis_evaluasi Tidak Boleh Kosong"
            ]
        ],
        
    ];

    public array $dosenWali = [
        "id_riwayat_pendidikan" => [
            "label" => "id_riwayat_pendidikan",
            "rules" => "required",
            "errors" => [
                "required" => "id_riwayat_pendidikan Tidak Boleh Kosong"
            ]
        ],
        "id_dosen" => [
            "label" => "id_dosen",
            "rules" => "required",
            "errors" => [
                "required" => "id_dosen Tidak Boleh Kosong"
            ]
        ],
        "nama_mahasiswa" => [
            "label" => "nama_mahasiswa",
            "rules" => "required",
            "errors" => [
                "required" => "nama_mahasiswa Tidak Boleh Kosong"
            ]
        ],
        "nama_dosen" => [
            "label" => "nama_dosen",
            "rules" => "required",
            "errors" => [
                "required" => "nama_dosen Tidak Boleh Kosong"
            ]
        ],
        "nim" => [
            "label" => "nim",
            "rules" => "required",
            "errors" => [
                "required" => "nim Tidak Boleh Kosong"
            ]
        ],
        
    ];

    public array $aktivitasKuliah = [
        "id_riwayat_pendidikan" => [
            "label" => "id_riwayat_pendidikan",
            "rules" => "required",
            "errors" => [
                "required" => "id_riwayat_pendidikan Tidak Boleh Kosong"
            ]
        ],
        "id_semester" => [
            "label" => "id_semester",
            "rules" => "required",
            "errors" => [
                "required" => "id_semester Tidak Boleh Kosong"
            ]
        ],
        "nama_mahasiswa" => [
            "label" => "nama_mahasiswa",
            "rules" => "required",
            "errors" => [
                "required" => "nama_mahasiswa Tidak Boleh Kosong"
            ]
        ],
        "nim" => [
            "label" => "nim",
            "rules" => "required",
            "errors" => [
                "required" => "nim Tidak Boleh Kosong"
            ]
        ],
        
    ];
}

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
        "nisn" => [
            "label" => "nisn",
            "rules" => "required|is_unique[mahasiswa.nisn]|max_length[10]|min_length[10]",
            "errors" => [
                "required" => "NISN Tidak Boleh Kosong",
                "is_unique" => "NISN yang sama sudah ada",
                "max_length" => "NISN tidak boleh lebih dari 10 karakter",
                "min_length" => "NISN tidak boleh kurang dari 10 karakter",
            ]
        ],
        "id_wilayah" => [
            "label" => "id_wilayah",
            "rules" => "required",
            "errors" => [
                "required" => "id_wilayah Tidak Boleh Kosong",
            ]
        ],
        "id_agama" => [
            "label" => "id_agama",
            "rules" => "required",
            "errors" => [
                "required" => "id_agama Tidak Boleh Kosong",
            ]
        ],
        "kewarganegaraan" => [
            "label" => "kewarganegaraan",
            "rules" => "required",
            "errors" => [
                "required" => "kewarganegaraan Tidak Boleh Kosong",
            ]
        ],
        "oap" => [
            "label" => "oap",
            "rules" => "required",
            "errors" => [
                "required" => "oap Tidak Boleh Kosong",
            ]
        ],
        "suku" => [
            "label" => "suku",
            "rules" => "required",
            "errors" => [
                "required" => "suku Tidak Boleh Kosong",
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
        ]
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
        ]
    ];

    public array $pengajarKelas = [
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
        ]
    ];

    public array $matakuliahKurikulum = [
        "id_prodi" => [
            "label" => "id_prodi",
            "rules" => "required",
            "errors" => [
                "required" => "id_prodi Tidak Boleh Kosong"
            ]
        ],
        "kurikulum_id" => [
            "label" => "kurikulum_id",
            "rules" => "required",
            "errors" => [
                "required" => "kurikulum_id Tidak Boleh Kosong"
            ]
        ],
        "matakuliah_id" => [
            "label" => "matakuliah_id",
            "rules" => "required",
            "errors" => [
                "required" => "matakuliah_id Tidak Boleh Kosong"
            ]
        ],
        "semester" => [
            "label" => "semester",
            "rules" => "required",
            "errors" => [
                "required" => "semester Tidak Boleh Kosong"
            ]
        ]
    ];

    public array $aktivitasMahasiswa = [
        "jenis_anggota" => [
            "label" => "Jenis Anggota",
            "rules" => "required",
            "errors" => [
                "required" => "Jenis Anggota Tidak Boleh Kosong"
            ]
        ],
        "id_jenis_aktivitas_mahasiswa" => [
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

    public array $anggotaAktivitasMahasiswa = [
        "aktivitas_mahasiswa_id" => [
            "label" => "aktivitas_mahasiswa_id",
            "rules" => "required",
            "errors" => [
                "required" => "aktivitas_mahasiswa_id Tidak Boleh Kosong"
            ]
        ],
        "id_riwayat_pendidikan" => [
            "label" => "id_riwayat_pendidikan",
            "rules" => "required",
            "errors" => [
                "required" => "id_riwayat_pendidikan Tidak Boleh Kosong"
            ]
        ],
        "jenis_peran" => [
            "label" => "jenis_peran",
            "rules" => "required",
            "errors" => [
                "required" => "jenis_peran Tidak Boleh Kosong"
            ]
        ]
    ];

    public array $bimbingMahasiswa = [
        "aktivitas_mahasiswa_id" => [
            "label" => "aktivitas_mahasiswa_id",
            "rules" => "required",
            "errors" => [
                "required" => "aktivitas_mahasiswa_id Tidak Boleh Kosong"
            ]
        ],
        "id_kategori_kegiatan" => [
            "label" => "id_kategori_kegiatan",
            "rules" => "required",
            "errors" => [
                "required" => "id_kategori_kegiatan Tidak Boleh Kosong"
            ]
        ],
        "id_dosen" => [
            "label" => "id_dosen",
            "rules" => "required",
            "errors" => [
                "required" => "id_dosen Tidak Boleh Kosong"
            ]
        ],
        "pembimbing_ke" => [
            "label" => "pembimbing_ke",
            "rules" => "required",
            "errors" => [
                "required" => "pembimbing_ke Tidak Boleh Kosong"
            ]
        ]
    ];

    public array $ujiMahasiswa = [
        "aktivitas_mahasiswa_id" => [
            "label" => "aktivitas_mahasiswa_id",
            "rules" => "required",
            "errors" => [
                "required" => "aktivitas_mahasiswa_id Tidak Boleh Kosong"
            ]
        ],
        "id_kategori_kegiatan" => [
            "label" => "id_kategori_kegiatan",
            "rules" => "required",
            "errors" => [
                "required" => "id_kategori_kegiatan Tidak Boleh Kosong"
            ]
        ],
        "id_dosen" => [
            "label" => "id_dosen",
            "rules" => "required",
            "errors" => [
                "required" => "id_dosen Tidak Boleh Kosong"
            ]
        ],
        "penguji_ke" => [
            "label" => "penguji_ke",
            "rules" => "required",
            "errors" => [
                "required" => "penguji_ke Tidak Boleh Kosong"
            ]
        ]
    ];

    public array $skalaSKS = [
        "ips_min" => [
            "label" => "ips_min",
            "rules" => "required",
            "errors" => [
                "required" => "ips_min Tidak Boleh Kosong"
            ]
        ],
        "ips_max" => [
            "label" => "ips_max",
            "rules" => "required",
            "errors" => [
                "required" => "ips_max Tidak Boleh Kosong"
            ]
        ],
        "sks_max" => [
            "label" => "sks_max",
            "rules" => "required",
            "errors" => [
                "required" => "sks_max Tidak Boleh Kosong"
            ]
        ]
    ];

    public array $settingPembiayaan = [
        "id_prodi" => [
            "label" => "id_prodi",
            "rules" => "required",
            "errors" => [
                "required" => "id_prodi Tidak Boleh Kosong"
            ]
        ],
        "angkatan" => [
            "label" => "angkatan",
            "rules" => "required",
            "errors" => [
                "required" => "angkatan Tidak Boleh Kosong"
            ]
        ],
        "biaya" => [
            "label" => "biaya",
            "rules" => "required",
            "errors" => [
                "required" => "biaya Tidak Boleh Kosong"
            ]
        ]
    ];

    public $resetPassword = [
        'password' => [
            'label' => 'Auth.password',
            'rules' => 'required|max_byte[72]|strong_password[]',
            'errors' => [
                'max_byte' => 'Auth.errorPasswordTooLongBytes'
            ]
        ],
        'password_confirm' => [
            'label' => 'Auth.passwordConfirm',
            'rules' => 'required|matches[password]',
        ],
    ];
}

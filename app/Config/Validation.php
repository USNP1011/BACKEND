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
}

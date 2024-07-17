<?php

function handleErrorDB($code) {
    if($code == 1062) return "Maaf, data ini sudah digunakan";
    else if($code == 1045) return "Mohon ";
    else if($code == 1054) return "Maaf, terjadi kesalahan dalam mengakses data. Pastikan Anda memasukkan data yang benar.";
    else if($code == 1364) return "Mohon maaf, data yang Anda masukkan tidak lengkap. Pastikan semua kolom yang dibutuhkan diisi dengan benar.";
    else if($code == 1452) return "Maaf, operasi Anda tidak dapat dilakukan karena terdapat keterkaitan data yang tidak ditemukan dalam sistem kami.";
    else return "Maaf terjadi kesalahan, silahkan hubungi pengembang";
}
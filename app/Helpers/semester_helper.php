<?php

function getSemesterAktif() : object {
    $model = new \App\Models\SemesterModel();
    return $model->where('a_periode_aktif', '1')->first();
}
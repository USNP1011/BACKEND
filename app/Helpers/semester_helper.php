<?php

function getSemesterAktif() : object {
    $model = new \App\Models\SemesterModel();
    return $model->where('a_periode_aktif', '1')->first();
}

function getSemesterById($id=null){
    $model = new \App\Models\SemesterModel();
    return $model->where('id_semester', $id)->first();
}
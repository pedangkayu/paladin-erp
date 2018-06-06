<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_karyawan_detail extends Model
{
    protected $table 	= 'view_karyawan_detail';
    protected $fillable = [
    'id_karyawan',
    'id_profesi',
    'nm_jabatan',
    'NIK',
    'nm_depan',
    'nm_belakang',
    'email',
    'hp',
    'tempat_lahir',
    'tgl_lahir',
    'foto',
    'jabatan',
    'tgl_bergabung',
    'id_departemen',
    'nama_profesi',
    'kd_departemen',
    'nm_departemen',
];

}

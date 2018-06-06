<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_pinjaman_karyawan extends Model
{
     protected $table 	= 'view_count_pinjaman_karyawan';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];
}
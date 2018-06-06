<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_potongan_karyawan extends Model
{
    protected $table 		= 'data_potongan_karyawan';
	protected $primaryKey 	= 'id_potongan';
	protected $fillable 	= [
        'id_log_honor',
        'id_loan',
        'nm_potongan',
        'jumlah_potonngan',
        'tipe_potongan',
	];
}

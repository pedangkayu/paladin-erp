<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_karyawan_potongan extends Model
{
    protected $table 		= 'data_karyawan_potongan';
	protected $primaryKey 	= 'id_potongan';
	protected $fillable 	= [
        'id_log_honor',
        'id_loan',
        'nm_potongan',
        'jumlah_potongan',
        'tipe_potongan',
	];
}

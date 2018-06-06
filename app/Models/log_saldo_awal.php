<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class log_saldo_awal extends Model {

    protected $table 		= 'log_saldo_awal';
	protected $fillable 	= [
		'id_coa',
		'id_karyawan',
		'total_current',
		'total_old'
	];
}

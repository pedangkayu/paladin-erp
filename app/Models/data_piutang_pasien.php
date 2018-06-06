<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_piutang_pasien extends Model {
	
    protected $table 		= 'data_piutang_pasien';
	protected $primaryKey 	= 'id_piutang_pasien';
	protected $fillable 	= [
		'id_faktur',
		'id_pasien',
		'status', // 1:Belum lunas | 2:Lunas
		'total',
		'tgl_jatuh_tempo'
	];
}

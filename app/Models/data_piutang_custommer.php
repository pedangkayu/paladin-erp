<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_piutang_custommer extends Model {
	
    protected $table 		= 'data_piutang_custommer';
	protected $primaryKey 	= 'id_piutang_custommer';
	protected $fillable 	= [
		'id_faktur',
		'id_payer',
		'status', // 1:Belum lunas | 2:Lunas
		'total',
		'tgl_jatuh_tempo'
	];
}

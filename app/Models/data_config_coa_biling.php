<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_config_coa_biling extends Model {


    protected $table 		= 'data_config_coa_biling';
	protected $fillable 	= [
		'coa_sebelum_dibayar',
		'coa_adjustment',
		'coa_item_tambahan',
		'coa_rawat_inap',
		'coa_pendapatan_resep',
		'coa_saldo',
		'aktif' // Default 1
	];


	public function scopeActive($query){
		return $query->where('aktif', 1);
	}
}

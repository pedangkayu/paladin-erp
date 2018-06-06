<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_coa_config_deposit extends Model {

    protected $table 		= 'data_coa_config_deposit';
	protected $fillable 	= [
		'coa_deposit',
		'coa_pembayaran_cash',
		'aktif' // Default 1
	];


	public function scopeActive($query){
		return $query->where('aktif', 1);
	}
}

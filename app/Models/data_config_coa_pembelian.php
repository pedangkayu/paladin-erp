<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_config_coa_pembelian extends Model {

    protected $table 		= 'data_config_coa_pembelian';
	protected $fillable 	= [
		'coa_ppn',
		'coa_adjustment',
		'coa_jumlah_sebelum_dibayar',
		'coa_penambahan_item',
		'coa_pembayaran_cash',
		'aktif'// default 1
	];


	public function scopeActive($query){
		return $query->where('aktif', 1);
	}

}

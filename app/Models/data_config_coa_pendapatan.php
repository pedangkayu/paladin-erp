<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_config_coa_pendapatan extends Model {
    
    protected $table 		= 'data_config_coa_pendapatan';
	protected $fillable 	= [
		'coa_pendapatan_lainnya',
		'coa_adjustment',
		'coa_piutang',
		'coa_ppn',
		'coa_ppn',
		'coa_pembayaran_cash',
		'status',
		'aktif' // Default 1
	];


	public function scopeActive($query){
		return $query->where('aktif', 1);
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_config_coa_loan extends Model
{
    protected $table 		= 'data_config_coa_loan';
	protected $fillable 	= [
        'coa_loan',
        'coa_pembayaran_cash',
        'aktif',///1

	];

	public function scopeActive($query){
		return $query->where('aktif', 1);
	}
}

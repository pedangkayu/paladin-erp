<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_hutang_vendor extends Model {

    protected $table 		= 'data_hutang_vendor';
	protected $primaryKey 	= 'id_hutang_vendor';
	protected $fillable 	= [
		'id_faktur',
		'id_vendor',
		'status', // 1:Belum lunas | 2:Lunas
		'total',
		'tgl_jatuh_tempo'
	];


	public function scopeHutang($query){
		return $query->where('status', 1)
			->select(\DB::raw('SUM(total) as total'));
	}

	public function scopeHutangjatuhtempo($query){
		return $query->where('status', 1)
			->where(\DB::raw('DATE(tgl_jatuh_tempo)'), '<=', date('Y-m-d'))
			->select(\DB::raw('SUM(total) as total'));
	}


	public function scopeCounthutang($query){
		return $query->where('status', 1)
			->select(\DB::raw('COUNT(total) as total'));
	}

	public function scopeCounthutangjatuhtempo($query){
		return $query->where('status', 1)
			->where(\DB::raw('DATE(tgl_jatuh_tempo)'), '<=', date('Y-m-d'))
			->select(\DB::raw('COUNT(total) as total'));
	}

}

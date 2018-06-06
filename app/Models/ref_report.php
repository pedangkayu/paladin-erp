<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_report extends Model {

    protected $table = 'ref_report';
    protected $primaryKey = 'id_report';
	protected $fillable = [
		'id_coa',
		'tipe', /* 1:aruskas|2:rugi laba | 3: neraca */
		'kode_coa',
		'nm_coa',
		'status'
	];


	public function scopeTipe($query, $tipe, $req = []){
		$res = $query->join('ref_coa', 'ref_coa.id_coa', '=', 'ref_report.id_coa')
			->where('ref_coa.coa_kategori', $tipe);

			$res->select('ref_coa.*')
			->groupby('ref_coa.id_coa')
			->orderby('ref_coa.kode', 'asc');
	}

}

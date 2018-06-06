<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_voucer_jurnal extends Model {

    protected $table 		= 'data_voucer_jurnal';
	protected $primaryKey 	= 'id_voucer_jurnal';
	protected $fillable 	= [
		'keterangan',
		'status',
		'tanggal'
	];

	public function scopeLaporanbiling($query, $req = []){

		$ju = $query;

		if(!empty($req['jurnal'])){
			switch($req['jurnal']):

				case "1":
					if(empty($req['id_vendor'])):
						$ju->join('data_jurnal', 'data_jurnal.id_voucer_jurnal', '=', 'data_voucer_jurnal.id_voucer_jurnal')
							->join('data_faktur', 'data_faktur.id_faktur', '=', 'data_jurnal.id_faktur')
							->where('data_faktur.id_vendor', '>', 0)
							->where('data_faktur.id_pasien', 0)
							->where('data_faktur.id_faktur', '>', 0)
							->groupby('data_voucer_jurnal.id_voucer_jurnal');
					else:
						$ju->join('data_jurnal', 'data_jurnal.id_voucer_jurnal', '=', 'data_voucer_jurnal.id_voucer_jurnal')
							->join('data_faktur', 'data_faktur.id_faktur', '=', 'data_jurnal.id_faktur')
							->where('data_faktur.id_vendor', $req['id_vendor'])
							->where('data_faktur.id_pasien', 0)
							->groupby('data_voucer_jurnal.id_voucer_jurnal');
					endif;
				break;

				case "2":
					if(empty($req['id_pasien'])):
						$ju->join('data_jurnal', 'data_jurnal.id_voucer_jurnal', '=', 'data_voucer_jurnal.id_voucer_jurnal')
							->join('data_faktur', 'data_faktur.id_faktur', '=', 'data_jurnal.id_faktur')
							->where('data_faktur.id_pasien', '!=', 0)
							->groupby('data_voucer_jurnal.id_voucer_jurnal');
					else:
						$ju->join('data_jurnal', 'data_jurnal.id_voucer_jurnal', '=', 'data_voucer_jurnal.id_voucer_jurnal')
							->join('data_faktur', 'data_faktur.id_faktur', '=', 'data_jurnal.id_faktur')
							->where('data_faktur.id_pasien', $req['id_pasien'])
							->groupby('data_voucer_jurnal.id_voucer_jurnal');
					endif;
				break;

				case "3":
					$ju->join('data_jurnal', 'data_jurnal.id_voucer_jurnal', '=', 'data_voucer_jurnal.id_voucer_jurnal')
						->where('data_jurnal.id_coa', $req['id_coa'])
						->groupby('data_voucer_jurnal.id_voucer_jurnal');
				break;

				endswitch;
		}

		if($req['waktu'] == 1)
				$ju->where(\DB::raw('MONTH(data_voucer_jurnal.tanggal)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_voucer_jurnal.tanggal)'), $req['tahun']);
			else
				$ju->whereBetween(\DB::raw('DATE(data_voucer_jurnal.tanggal)'), [$req['dari'], $req['sampai']]);

		$ju->select('data_voucer_jurnal.*');

	}

	public function jurnal(){
		return $this->hasMany('App\Models\data_jurnal', 'id_voucer_jurnal');
	}

}

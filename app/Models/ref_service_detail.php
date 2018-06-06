<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_service_detail extends Model
{
	   protected $table = 'ref_service_detail';
    protected $primaryKey = 'id_service_detail';
	protected $fillable = [
			'id_service_detail',
			'id_service_kode',
			'id_unit',
			'kebutuhan',

	];

	public function scopeCari($query, $req = []){
		$me = \Me::subgudang()->id_gudang;

		$data = $query->leftJoin('ref_service_kode', 'ref_service_kode.service_kode' ,'=' ,'ref_service_detail.id_service_kode')
					->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=' ,'ref_service_detail.id_unit')
					->whereIn('ref_service_kode.type',[2]);
					// ->where('ref_service_detail.id_unit', $me);
					if(!empty($req['tindakan']))
		  					$data->where('ref_service_kode.nm_service','LIKE', '%' . $req['tindakan'] . '%');
		  			if(!empty($req['unit_jasa']))
 							$data->where('ref_service_detail.id_unit', $req['unit_jasa']);
							$data->select('ref_service_detail.id_service_detail','ref_service_detail.id_service_kode',
								'ref_service_detail.id_unit as unit','ref_service_detail.kebutuhan',
								'ref_service_kode.*')
						->orderBy('nm_service', 'asc');

	}

	public function scopeEdit($query, $id){
		// $query->leftJoin('ref_service_kode', 'ref_service_kode.service_kode' ,'=' ,'ref_service_detail.id_service_kode')
               $query->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=' ,'ref_service_detail.id_unit')
               ->where('ref_service_detail.id_service_kode',$id)
               ->select('ref_service_detail.*','ref_gudang.nm_gudang');
	}
	public function scopeData($query, $req){
		$query->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service_detail.id_service_kode')
					->select('ref_service_kode.*','ref_service_detail.*');
	}
}

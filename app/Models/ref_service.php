<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_service extends Model
{
  protected $table = 'ref_service';
	protected $primaryKey = 'id_service';
	protected $fillable = [
					'id_grup',
					'service_kode',
					'parend_id',
					'unit',
					'id_service_detail',		
					'status',//1 aktif 2 nonnaktf


	];

   public function scopeCek($query,$id){
   	$up= $query->leftjoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
                  ->where('ref_service.id_service',$id)
                  ->select('ref_service_kode.nm_service','ref_service.*')
                  ->orderBy('nm_service', 'asc');
   }

		public function scopePaket($query, $req = []){
			$me= \me::subgudang()->id_gudang;
			$query->Join('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
					->leftJoin('ref_service_grup','ref_service_grup.id_grup','=', 'ref_service.id_grup')
					->where('ref_service.parend_id',0);
                    // ->where('ref_service.unit',$me);
		if(!empty($req['grup']))
			$query->where('ref_service_kode.service_kode', $req['grup']);
		if(!empty($req['usg']))
			$query->where('ref_service.id_grup', $req['usg']);
		if(!empty($req['unit_jasa']))
 			$query->where('ref_service.unit', $req['unit_jasa']);
		if(!empty($req['tindakan']))
			$query->where('ref_service_kode.nm_service','LIKE', '%' . $req['tindakan'] . '%');
       				$query->select('ref_service.*','ref_service_kode.nm_service')->get();
		}
 	 public function scopeService($query, $req = []){
 	 	 $me = \Me::subgudang()->id_gudang;
  			$query->leftJoin('ref_service_grup', 'ref_service_grup.id_grup', '=', 'ref_service.id_grup')
  					->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
  					->where('ref_service.parend_id',0)
  					->where('ref_service.unit',$me);

		if(!empty($req['grup']))
			$query->where('ref_service_kode.service_kode', $req['grup']);
		if(!empty($req['usg']))
			$query->where('ref_service.id_grup', $req['usg']);
		if(!empty($req['tindakan']))
			$query->where('ref_service_kode.nm_service','LIKE', '%' . $req['tindakan'] . '%');
			$query->select(
			'ref_service.*',
			
			'ref_service_kode.nm_service'
		)->get();
	}
	 public function scopeService1($query, $req = []){
 	 	 // $me = \Me::subgudang()->id_gudang;
  			$query->leftJoin('ref_service_grup', 'ref_service_grup.id_grup', '=', 'ref_service.id_grup')
  					->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
  					->where('ref_service.parend_id',0);
  					// ->where('ref_service.unit',$me);

		if(!empty($req['usg']))
			$query->where('ref_service.id_grup', $req['usg']);
		if(!empty($req['unit_jasa']))
 			$query->where('ref_service.unit', $req['unit_jasa']);
		if(!empty($req['tindakan']))
			$query->where('ref_service_kode.nm_service','LIKE', '%' . $req['tindakan'] . '%');
			$query->select(
			'ref_service.*',
			
			'ref_service_kode.nm_service'
		)->get();
	}

	public function scopeGrup($query, $req = []){
			$me= \me::subgudang()->id_gudang;
			$grup = $query->join('ref_service_kode', 'ref_service_kode.service_kode', '=','ref_service.service_kode')
							->where('ref_service.parend_id',0)
							->where('ref_service.unit',$me);
			if(!empty($req['nm_service']))
							$query->where('ref_service_kode.nm_service','LIKE', '%' . $req['nm_service'] . '%');
							$query->select('ref_service.*', 'ref_service_kode.nm_service')->get();
	}
	// public function scopeHide($query,$id){
	// 	return $query->leftjoin
	// }

	public function serviceitem(){
  	return $this->hasMany('App\Models\ref_service_item', 'id_service');
  }
}

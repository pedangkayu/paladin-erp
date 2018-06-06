<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_service_kode extends Model
{
     protected $table = 'ref_service_kode';
	protected $primaryKey = 'service_kode';
	protected $fillable = [
			'service_kode',
			'nm_service',
			'type', //1=tindakan 2=jasa
			'coa',
			'coa_rs',
			'id_unit',
			'persen_rs',
			'coa_pendapatan',
			'coa_dr',
			'persen_dr',
			'tarif_dasar',
			'status', ///1==tidak 2=aktif  //3=hapus/
			'keterangan',

	];


	public function scopeCari($query, $req = []){
		if(!empty($req['tindakan']))
		  $query->where('ref_service_kode.nm_service','LIKE', '%' . $req['tindakan'] . '%');
		$query->select('ref_service_kode.*');
	}
	public function scopeKod($query, $req =[]){
		$me = \Me::data()->id_karyawan;
	    $sub= \Me::subgudang()->id_gudang;
	    // dd($req['id_unit']);
		$r= $query->leftjoin('ref_coa', 'ref_coa.id_coa', '=', 'ref_service_kode.coa')
			->whereIn('ref_service_kode.type',[1]);
          // ->where('ref_service_kode.id_unit',$me)
			 if($sub > 0){
				if(!empty($me))
					$r->where('ref_service_kode.id_unit', $me);
				}elseif(($sub ==20)||($sub < 1)){
				if(!empty($req['id_unit']))
					$t->where('ref_service_kode.id_unit', $req['id_unit']);
				}
           $r->select('ref_service_kode.*', 'ref_coa.kode','nm_coa');
	}
	public function scopeEdit($query, $id){
		 return $query->leftjoin('ref_coa', 'ref_coa.id_coa', '=', 'ref_service_kode.coa')
		 		->where('ref_service_kode.service_kode', $id)
				->select('ref_service_kode.*', 'ref_coa.kode','nm_coa');
			}

	public function scopeHid($query, $id){
		return  $query->leftjoin('ref_coa', 'ref_coa.id_coa', '=', 'ref_service_kode.coa')
                      ->leftjoin('ref_coa as a', 'a.id_coa', '=', 'ref_service_kode.coa_rs')
                      ->leftjoin('ref_coa as d', 'd.id_coa', '=', 'ref_service_kode.coa_dr')
                      ->where('ref_service_kode.service_kode', $id)
      				  ->select('ref_service_kode.*', 'ref_coa.kode','a.kode as rs_coa', 'd.kode as dr_coa');
	}

  	public function scopeJasa($query, $nm_service, $req= []){
  		$jasa = $query->leftjoin('ref_coa', 'ref_coa.id_coa', '=', 'ref_service_kode.coa')
                      ->leftjoin('ref_coa as a', 'a.id_coa', '=', 'ref_service_kode.coa_rs')
                      ->leftjoin('ref_coa as d', 'd.id_coa', '=', 'ref_service_kode.coa_dr')
                      // ->leftjoin('ref_service_detail', 'ref_service_detail.id_service_kode', '=', 'ref_service_kode.service_kode')
                      ->whereIn('ref_service_kode.type',[2]);
		  	if(!empty($nm_service))
				  $jasa->where('ref_service_kode.nm_service','LIKE', '%' .$nm_service. '%');
					 $jasa->select('ref_service_kode.*', 'ref_coa.kode','a.kode as rs_coa', 'd.kode as dr_coa');
  	}

		//----------kemungkinan tidak di pakek nant  ini----------//
	public function scopeData($query, $nm_service, $id_unit, $status, $req = []){
		// dd($req->id_unit);
		$data = $query->leftjoin('ref_coa', 'ref_coa.id_coa', '=', 'ref_service_kode.coa')
                       ->leftjoin('ref_coa as a', 'a.id_coa', '=', 'ref_service_kode.coa_rs')
                       ->leftjoin('ref_coa as d', 'd.id_coa', '=', 'ref_service_kode.coa_dr')
                       ->leftjoin('ref_gudang','ref_gudang.id_gudang', '=', 'ref_service_kode.id_unit');
                    if(!empty($nm_service))
		     			$data->where('ref_service_kode.nm_service', 'LIKE' , '%'. $nm_service .'%');
	     			if(!empty($id_unit))
						$data->where('ref_service_kode.id_unit', $id_unit);
		     		if(!empty($status))
		     			$data->where('ref_service_kode.status',$status);
                  	 $data->select('ref_service_kode.*', 'ref_coa.kode','a.kode as rs_coa', 'd.kode as dr_coa');

	}
	//----------------untuk ke uangan--------------------//
	public function scopePng($query){
		$cek = $query->leftjoin('ref_coa', 'ref_coa.id_coa', '=', 'ref_service_kode.coa')
                                  ->leftjoin('ref_coa as a', 'a.id_coa', '=', 'ref_service_kode.coa_rs')
                                  ->leftjoin('ref_coa as d', 'd.id_coa', '=', 'ref_service_kode.coa_dr')
                                  ->whereIn('ref_service_kode.type',[2])
                  				 ->select('ref_service_kode.*', 'ref_coa.kode','a.kode as rs_coa', 'd.kode as dr_coa');
	}



}

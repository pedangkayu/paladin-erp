<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_treatment extends Model
{
  protected $table 		= 'data_treatment';
	protected $primaryKey 	= 'id_treatment';
	protected $fillable 	= [
		'nomor_treatment',
		'tgl_input',
		'tgl_pemeriksa',
		'id_pembuat',
		'id_unit', // //id_gudangnya / unutk membaca jasanya dari mana
		'id_unit_item',////ini untuk prametere obatnya pakek dari gudang yang mana
		'catatan',
		'id_pasien',
		'keterangan',
		'id_paket',
		'id_tindakan',
		'grand_total',
		'kelas', //kelas kamr //="0">-ODC-//="1">Kelas I//="2">Kelas II//="3">Kelas III//="4">Kelas VIP
		'status' /* 0:baru|1:selseai /Bayar*/
	];

  	public function scopeListtretment($query){
      $me = \Me::data()->id_karyawan;
      	 $gudang= \Me::subgudang()->id_gudang;
    	$treatment=$query->leftJoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_treatment.id_pasien')
			 ->where('data_treatment.id_unit', $gudang)
			->select(
				'data_pasien.nama_pasien',
				'data_pasien.id_pasien_hc',
				'data_treatment.*'
			)
			->orderby('status', 'asc');
	}

  	public function scopeBytreatment($query, $nomor_treatment=0,$id_pasien_hc=0, $id_gudang=0, $status=0, $req = []){
  		// dd($id_gudang);
	    $me = \Me::data()->id_karyawan;
	    $sub= \Me::subgudang()->id_gudang;
	    $gudang= \Me::subgudang()->id_gudang;
	    $tre = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_treatment.id_pasien')
	    		->join('ref_gudang','ref_gudang.id_gudang', '=', 'data_treatment.id_unit');
	   		 if($sub > 0){
    		 	if(!empty($gudang))
				$tre->where('data_treatment.id_unit', $gudang);
    		 }else{
	   			if(!empty($req['id_gudang']))
	     	 	$tre->where('data_treatment.id_unit', $req['id_gudang']);
    		 }
			// endif;
	    if(!empty($req['nomor_treatment']))
	     	 $tre->where('data_treatment.nomor_treatment', $req['nomor_treatment']);
	     if(!empty($req['status']))
			$tre->where('data_treatment.status', $req['status']);
	    if(!empty($req['id_pasien_hc']))
			$tre->where('data_treatment.id_pasien', $req['id_pasien_hc']);

	    $tre->orderby('id_treatment', 'desc');
	    $tre->select(
	   	'ref_gudang.nm_gudang as unit',
	    'data_pasien.nama_pasien',
	    'data_pasien.id_pasien_hc',
	    'data_treatment.*'
	  );
  }
	public function scopeByview($query,$req = []){
		// dd($id_gudang_jasa);
	    // $me = \Me::data()->id_karyawan;
	    $gudang= \Me::subgudang()->id_gudang;
	    $tre = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_treatment.id_pasien')
	    ->whereIn('data_treatment.status',[0]);
	    $tre->select(
	    'data_pasien.nama_pasien',
	    'data_pasien.id_pasien_hc',
	    'data_treatment.*'
	  );
  }

  public function scopeHc($query, $id){
  	 $gudang= \Me::subgudang()->id_gudang;
		return $query->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_treatment.id_pasien')
					->leftJoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_treatment.id_dokter')
					->join('ref_kelas', 'ref_kelas.id_kelas', '=', 'data_treatment.kelas')
						->where('data_treatment.id_treatment', $id)
					    // ->where('data_treatment.id_pasien',$id)
						// ->where('data_treatment.id_unit',$gudang)
						->whereIn('data_treatment.status',[0])
						->select(
						'data_treatment.*',
						'data_pasien.nama_pasien',
						'data_pasien.alamat_pasien',
						'data_pasien.kota_pasien',
						'data_pasien.hp_pasien',
						'data_karyawan.nm_depan',
						'data_karyawan.nm_belakang',
						//'ref_service_tindakan.tindakan',
						'data_pasien.id_pasien_hc',
						'ref_kelas.nm_kelas'
		);

	}
  public function scopeHid($query, $id){
  	 $gudang= \Me::subgudang()->id_gudang;
		return $query->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_treatment.id_pasien')
					->leftJoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_treatment.id_dokter')
					->join('ref_kelas', 'ref_kelas.id_kelas', '=', 'data_treatment.kelas')
						->where('data_treatment.id_treatment', $id)
						// ->where('data_treatment.id_unit',$gudang)
						->select(
						'data_treatment.*',
						'data_pasien.nama_pasien',
						'data_pasien.alamat_pasien',
						'data_pasien.kota_pasien',
						'data_pasien.hp_pasien',
						'data_karyawan.nm_depan',
						'data_karyawan.nm_belakang',
						//'ref_service_tindakan.tindakan',
						'data_pasien.id_pasien_hc',
						'ref_kelas.nm_kelas'
		);

	}
	public function scopeKelas($query, $id){
		return $query->where('data_treatment.id_treatment', $id);
	}
	 public function scopeRekaptreatment($query, $req = []){
		$item = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_treatment.id_pasien')
						->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_treatment.id_dokter');

		if(!empty($req['nama_pasien']))
			$item->where('data_pasien.nama_pasien', $req['nama_pasien']);

		if(!empty($req['waktu']) && $req['waktu'] == 1)
			$item->where(\DB::raw('MONTH(data_treatment.created_at)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_treatment.created_at)'), $req['tahun']);

		else if(!empty($req['waktu']) && $req['waktu'] == 2)
			$item->whereBetween(\DB::raw('DATE(data_treatment.created_at)'), [$req['dari'], $req['sampai']]);

		// $item->select(
		// 	'data_treatment.*',
		// 	'data_pasien.nama_pasien',
		// 	'data_pasien.id_pasien_hc',
		// 	'data_karyawan.nm_depan',
		// 	'data_karyawan.nm_belakang'
		// 	);
	}
	/* --------------------- HEXTERS ---------------- */
	// 18-02-2016
	public function scopeBilingtreatment($query, $id){
		return $query->where('data_treatment.status', 0)
			->join('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_treatment.id_unit')
			->whereIn('id_treatment', $id)
			->select('data_treatment.*', 'ref_gudang.nm_gudang');
	}

	public function items(){
		return $this->hasMany('App\Models\data_treatment_item', 'id_treatment');
	}



}

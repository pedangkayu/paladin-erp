<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class data_resep extends Model
{
   protected $table = 'data_resep';
   protected $primaryKey = 'id_resep';
	protected $fillable = [

		'id_pasien',
		'id_karyawan',
		'tgl_input',
		'nomor_resep',
		'tgl_pemeriksa',
		'id_pembuat',
		'id_gudang',
		'grand_total',
		'catatan',
		'kategori', /// 1= rawat Jalan 2 = Rawat Inap 3= No Resep 
		'status_resep', /* 0 => belum kasih obat 1=n dikasihkan semua */
		'status' /*0, 1= baru 2=suda bayar*/

	];

	/*----Holil-------ini untuk query join data resep dengan bangsanya */
	public function scopeListresep($query){
		  $me = \Me::data()->id_karyawan;
		$gudang= \Me::subgudang()->id_gudang;
		$resep = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_resep.id_pasien')
		->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan')
		->where('data_resep.status', 0)
		->where('data_resep.id_gudang', $gudang)
			->select(
			'data_resep.*',
			'data_pasien.nama_pasien',
			'data_karyawan.nm_depan',
			'data_karyawan.nm_belakang',
			'data_pasien.id_pasien_hc'	)
			->orderby('id_resep', 'dsc');
	}

	public function scopeHid($query, $id){
		return $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_resep.id_pasien')
		->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan')
			->where('data_resep.id_resep', $id)
			->select(
			'data_resep.*',
			'data_pasien.nama_pasien',
			'data_pasien.alamat_pasien',
			'data_pasien.kota_pasien',
			'data_pasien.hp_pasien',
			'data_karyawan.nm_depan',
			'data_karyawan.nm_belakang',
			'data_pasien.id_pasien_hc'
		);

	}
	public function scopeBbb($query,  $req = []){
 
	    $me = \Me::data()->id_karyawan;
	    $gudang= \Me::subgudang()->id_gudang;
	    $resep = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_resep.id_pasien')
			->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan')
	        ->where('data_resep.status_resep', 0)
	        ->orderby('data_resep.id_resep', 'desc')
		    ->select(
		    'data_resep.*',
		    'data_pasien.nama_pasien',
		    'data_pasien.alamat_pasien',
		    'data_pasien.kota_pasien',
		    'data_pasien.hp_pasien',
		    'data_karyawan.nm_depan',
		    'data_karyawan.nm_belakang',
		    'data_pasien.id_pasien_hc'
	  );
  }
  	public function scopeByidpasien($query, $id_pasien_hc=0, $nama_pasien=0, $nomor_resep=0, $status=0, $status_resep=0, $req = []){
  		// dd($status);
	    $me = \Me::data()->id_karyawan;
	    $sub= \Me::subgudang()->id_gudang;
	    if($sub < 1):
	    	$gudang=26;
	    else:
	    	$gudang= \Me::subgudang()->id_gudang;
	    endif;
		    $resep = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_resep.id_pasien')
				->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan')
		      // ->where('data_resep.status', 0)
		  	->where('data_resep.id_gudang', $gudang);
		    if(!empty($id_pasien_hc))
		      $resep->where('data_pasien.id_pasien_hc', 'LIKE' , '%'. $id_pasien_hc .'%');
		    if(!empty($nomor_resep))
		      $resep->where('data_resep.nomor_resep', 'LIKE', '%'. $nomor_resep.'%');
		   if(!empty($nama_pasien))
		      $resep->where('data_pasien.nama_pasien', 'LIKE', '%'. $nama_pasien.'%');
		  if(!empty($status_resep))
				$resep->where('data_resep.status_resep', $req['status_resep']);
		   if(!empty($req['status']))
				$resep->where('data_resep.status', $req['status']);
		    $resep->orderby('data_resep.id_resep', 'desc')
		    ->select(
		    'data_resep.*',
		    'data_pasien.nama_pasien',
		    'data_pasien.alamat_pasien',
		    'data_pasien.kota_pasien',
		    'data_pasien.hp_pasien',
		    'data_karyawan.nm_depan',
		    'data_karyawan.nm_belakang',
		    'data_pasien.id_pasien_hc'
		  );
  }
  public function scopeRekapresep($query, $req = []){
		$item = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_resep.id_pasien')
						->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan');
		if(!empty($req['nama_pasien']))
			$item->where('data_pasien.nama_pasien', $req['nama_pasien']);

		if(!empty($req['kategori']))
			$item->where('data_resep.kategori', $req['kategori']);

		if(!empty($req['waktu']) && $req['waktu'] == 1)
			$item->where(\DB::raw('MONTH(data_resep.created_at)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_resep.created_at)'), $req['tahun']);
		else if(!empty($req['waktu']) && $req['waktu'] == 2)
			$item->whereBetween(\DB::raw('DATE(data_resep.created_at)'), [$req['dari'], $req['sampai']]);

		// $item->select(
		// 	'data_resep.*',
		// 	'data_pasien.nama_pasien',
		// 	'data_pasien.id_pasien_hc',
		// 	'data_karyawan.nm_depan',
		// 	'data_karyawan.nm_belakang'
		// 	);
	}

  /* ------------------------- HEXTERS ------------------------- */
  // 18-02-2016
  public function scopeBilingpasien($query, $id){
  	return $query->where('status', 0)
  		->whereIn('id_resep', $id);
  }

  public function obat(){
  	return $this->hasMany('App\Models\data_resep_item', 'id_resep');
  }

}

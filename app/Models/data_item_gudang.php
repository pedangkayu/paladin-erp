<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_item_gudang extends Model{
    protected $table 		= 'data_item_gudang';
	protected $primaryKey 	= 'id_item_gudang';
	protected $fillable 	= [
		'id_barang',
		'in',
		'out',
		'keterangan',
		'id_gudang',
		'status'
	];

	public function scopeByidbarang($query, $id){
		return $query->join('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_item_gudang.id_gudang')
		->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
		->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
		->where('data_item_gudang.id_barang', $id)
		->select('data_item_gudang.*', 'ref_gudang.nm_gudang', 'ref_satuan.nm_satuan')
		->get();
	}

	public function scopeDetail($query, $req = []){
		$me = \Me::subgudang();

		$item = $query->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
			->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
			->join('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
			->where('data_item_gudang.status', 1);
		if(count($req) > 0){
			if(!empty($req['src']))
				$item->where('data_barang.nm_barang', 'LIKE', '%' . $req['src'] . '%');
			if(!empty($req['kode']))
				$item->where('data_barang.kode','LIKE', '%'. $req['kode'] . '%');
			if(!empty($req['id_kategori']))
				$item->where('data_barang.id_kategori', $req['id_kategori']);
			if(!empty($req['tipe']))
				$item->where('data_barang.tipe', $req['tipe']);
			if($req['stok'] == 'true')
				$item->where(\DB::raw('data_item_gudang.in - data_item_gudang.out'), '=', 0);
		}

		if(!empty($req['gudang'])){
			$item->where('data_item_gudang.id_gudang', $req['gudang']);
		} else if($me->id_gudang > 0){
			$item->where('data_item_gudang.id_gudang', $me->id_gudang);
		}else if(\Auth::user()->permission < 3 && !$me->access){
			$item->where('data_item_gudang.id_gudang', 0);
		}

		$item->select(
			'data_item_gudang.*',
			'data_barang.kode',
			'data_barang.nm_barang',
			'data_barang.tipe',
			'ref_satuan.nm_satuan',
			'ref_kategori.nm_kategori'
		);
	}

	public function scopeHabis($query){
		$me = \Me::subgudang();
		$item = $query->where('status', 1)
			->where(\DB::raw('(`in` - `out`)'), '=', 0);
		if($me->id_gudang > 0){
			$item->where('id_gudang', $me->id_gudang);
		}else if(\Auth::user()->permission < 3 && !$me->access){
			$item->where('id_gudang', 0);
		}
	}

	public function scopeAdj($query, $req = [], $ids){

		$me = \Me::subgudang();

		$items = $query->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
						->join('ref_satuan','ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
			->where('data_item_gudang.id_gudang', $me->id_gudang);

		if(count($req) > 0){
			if(!empty($req['kode']))
			$items->where('data_barang.kode', $req['kode']);
			if(!empty($req['item']))
				$items->where('data_barang.nm_barang', 'LIKE', '%' . $req['item'] . '%');
			if(!empty($req['kat']))
				$items->where('data_barang.id_kategori',  $req['kat']);
		}
		if(count($ids) > 0)
			$items->whereNotIn('data_barang.id_barang', $ids);

		$items->select('data_barang.*','data_item_gudang.in as masuk', 'data_item_gudang.out as keluar','ref_satuan.nm_satuan');

	}


	/* ----HOLIL -------- */
	public function scopeResepitems($query, $req = []){

		$me = \Me::subgudang()->id_gudang;

		$items = $query->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
		->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan');
		if(!empty($req['kode']))
			$items->where('data_barang.kode', $req['kode']);
		if(!empty($req['barang']))
			$items->where('data_barang.nm_barang','LIKE', '%' . $req['barang'] . '%');
		$items->where('data_item_gudang.id_gudang', $me)
		// $items->where('data_item_gudang.id_gudang', 26)
			->where('data_barang.tipe', 1);
		$items->select(
			'data_item_gudang.id_item_gudang',
			'data_barang.id_barang',
			'data_barang.nm_barang',
			'data_barang.kode',
			'data_item_gudang.in',
			'ref_satuan.nm_satuan',
			'data_item_gudang.id_gudang',
			'data_item_gudang.out'
		);
	}


  public function scopeTreatment($query, $req = []){
    $me = \Me::subgudang()->id_gudang;
    $items = $query->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan');
    if(!empty($req['kode']))
      $items->where('data_barang.kode', $req['kode']);
    if(!empty($req['barang']))
      $items->where('data_barang.nm_barang','LIKE', '%' . $req['barang'] . '%');
  if(!empty($req['unit']))
 		$items->where('data_item_gudang.id_gudang', $req['unit'])
    // $items->where('data_item_gudang.id_gudang', $me)
    // $items->where('data_item_gudang.id_gudang', 26)
      ->where('data_barang.tipe', 1)
       ->whereIn('data_barang.status',[1])
     ->whereIn('data_barang.id_kategori',[1,2,3,4,5]);
    $items->select(
      'data_item_gudang.id_item_gudang',
      'data_barang.id_barang',
      'data_barang.nm_barang',
      'data_barang.kode',
      'data_item_gudang.in',
      'ref_satuan.nm_satuan',
      'data_item_gudang.id_gudang',
      'data_item_gudang.out'
    ) ->orderBy('nm_barang', 'asc');
  }

  public function scopeMaster($query, $req = []){
    $items = $query->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan');
    if(!empty($req['kode']))
      $items->where('data_barang.kode', $req['kode']);
    if(!empty($req['barang']))
      $items->where('data_barang.nm_barang','LIKE', '%' . $req['barang'] . '%')
     // $items->where('data_barang.tipe', 1)
     ->whereIn('data_barang.id_kategori',[1,2,3,4,5]);
    $items->select(
      'data_item_gudang.id_item_gudang',
      'data_barang.id_barang',
      'data_barang.nm_barang',
      'data_barang.kode',
      'data_item_gudang.in',
      'ref_satuan.nm_satuan',
      'data_item_gudang.id_gudang',
      'data_item_gudang.out'
    );
  }
   public function scopePaket($query, $req = []){
    $me = \Me::subgudang()->id_gudang;
    $items = $query->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan');
    if(!empty($req['kode']))
      $items->where('data_barang.kode', $req['kode']);
    if(!empty($req['barang']))
      $items->where('data_barang.nm_barang','LIKE', '%' . $req['barang'] . '%');
    $items->where('data_item_gudang.id_gudang', $me)
     // $items->where('data_item_gudang.id_gudang', 26)
      ->where('data_barang.tipe', 1)
      ->whereIn('data_barang.id_kategori',[1,2]);
    $items->select(
      'data_item_gudang.id_item_gudang',
      'data_barang.id_barang',
      'data_barang.nm_barang',
      'data_barang.kode',
      'data_item_gudang.in',
      'ref_satuan.nm_satuan',
      'data_item_gudang.id_gudang',
      'data_item_gudang.out'
    );
  }
  public function scopeByunit($query, $req= []){
  			//dd($req['gudang']);
  	 $items = $query->join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
  	 				->join('ref_gudang', 'ref_gudang.id_gudang', '=','data_item_gudang.id_gudang')
   					 ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan');
    if(!empty($req['kode']))
      $items->where('data_barang.kode', $req['kode']);
    if(!empty($req['barang']))
      $items->where('data_barang.nm_barang','LIKE', '%' . $req['barang'] . '%');
 	if(!empty($req['gudang']))
 		$items->where('data_item_gudang.id_gudang', $req['gudang']);

    $items->select(
      'data_item_gudang.id_item_gudang',
      'data_item_gudang.id_gudang',
      'data_barang.id_barang',
      'data_barang.nm_barang',
      'ref_gudang.nm_gudang',
      'data_barang.kode',
      'data_item_gudang.in',
      'ref_satuan.nm_satuan',
      'data_item_gudang.id_gudang',
      'data_item_gudang.out'
    );
  }

}

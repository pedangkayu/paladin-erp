<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_resep_campur extends Model {

	protected $table 		= 'data_resep_campur';
	protected $primaryKey 	= 'id_resep_campur';
	protected $fillable 	= [
		'qty',
		'id_resep_item',
		'id_barang',
    'id_resep',
    'harga_jual',
    'id_satuan_campur',
		'id_item_gudang'
	];

  // public function scopeByid($query, $id ){
  // return  $query->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
  //   ->join('data_resep_item', 'data_resep_item.id_resep_item', '=', 'data_resep_campur.id_resep_item')
  // 	->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_campur.id_satuan_campur')
  //         	->where('data_resep_campur.id_resep', $id)
  //           ->where('data_resep_item.id_barang', 0)
		// 				// ->where('data_resep_campur.id_resep_item',$id)
  //           ->select(
  //             'data_resep_campur.*',
  //             'data_barang.kode',
  //             'data_barang.nm_barang',
  //             'ref_satuan.nm_satuan',
  //             'data_resep_item.keterangan',
		// 					'data_resep_item.id_resep_aturan',
  //             'data_resep_campur.id_resep_item'
  //             // Pengambilan stok nya ngambil ke filed in & out nya data_item_gudang
  //           );
  // }
	public function itemdata()
	    {
	        // return $this->belongsTo('App\Models\data_resep_item');
					return $this->belongsTo('data_resep_item', 'id_resep_item');
	    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_mutasi_skb_item extends Model
{

    protected $table='data_mutasi_skb_item';
    protected $primaryKey='id_mutasi_skb_item';
    protected $fillable=[
    	'id_mutasi_spb_item',
		'id_mutasi_skb',
		'id_mutasi_spb',
		'id_item',
		'id_gudang',
		'qty',
		'qty_awal',
		'id_satuan',
		'keterangan',
		'status',
		'sisa',

    ];

    public function scopeByid($query, $id){
    	$gudang= \Me::subgudang()->id_gudang;
		return $query->join('data_barang', 'data_barang.id_barang', '=', 'data_mutasi_skb_item.id_item')
			->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
			->join('data_item_gudang', 'data_item_gudang.id_barang', '=','data_barang.id_barang')
			->join('data_mutasi_spb_item', 'data_mutasi_spb_item.id_mutasi_spb_item', '=', 'data_mutasi_skb_item.id_mutasi_spb_item')
			->where('data_mutasi_skb_item.status', 1)
			->where('data_mutasi_skb_item.id_mutasi_skb', $id)
			->where('data_item_gudang.id_gudang',$gudang)
			->select(
				'data_barang.kode', 
				'data_barang.nm_barang',
				'data_item_gudang.in', 
				'data_item_gudang.out', 
				'ref_satuan.nm_satuan', 
				'data_mutasi_spb_item.qty_awal',
				'data_mutasi_skb_item.*'
			);
	}
	 public function scopeBysmb($query, $id){
    	$gudang= \Me::subgudang()->id_gudang;
		return $query->join('data_barang', 'data_barang.id_barang', '=', 'data_mutasi_skb_item.id_item')
			->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
			->join('data_item_gudang', 'data_item_gudang.id_barang', '=','data_barang.id_barang')
			->join('data_mutasi_spb_item', 'data_mutasi_spb_item.id_mutasi_spb_item', '=', 'data_mutasi_skb_item.id_mutasi_spb_item')
			->where('data_mutasi_skb_item.status', 1)
			->where('data_mutasi_skb_item.id_mutasi_skb', $id)
			->where('data_item_gudang.id_gudang',$gudang)
			->select(
				'data_barang.kode', 
				'data_barang.nm_barang',
				'data_item_gudang.in', 
				'data_item_gudang.out', 
				'ref_satuan.nm_satuan', 
				'data_mutasi_spb_item.qty_awal',
				'data_mutasi_skb_item.*'
			);
	}
}

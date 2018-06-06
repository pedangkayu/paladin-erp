<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_paket_item extends Model
{
   	protected $table = 'data_paket_item';
	protected $primaryKey = 'id_paket_item';
	protected $fillable = [
		'id_paket_item',
		'id_paket',
		'id_service',
		// 'id_barang',
		// 'qty',
		// 'id_satuan',
  //   	'status',//1 aktfi 2= tidak aktif
  //   	'id_item_gudang', 
  //   	'id_gudang',
		'created_at',
		'updated_at',
	];

 
  
	public function scopePaket( $query , $req= []){
		$items= $query->join('data_paket', 'data_paket.id_paket', '=', 'data_paket_item.id_paket');
		if(!empty($req['nm_paket']))
			$items->where('data_paket.nm_paket', 'LIKE', '%' .$req['nm_paket']. '%');
			$items->select(
				'data_paket_item.*',
				'data_paket.nm_paket',
				'data_paket.tarif',
				'data_paket.id_paket'
				);
			}

	public function scopeByid($query, $id){
		$query->join('ref_service', 'ref_service.id_service', '=', 'data_paket_item.id_service')
				->where('data_paket_item.id_paket', $id)
				->select(
					'data_paket_item.*',
					'ref_service.nama_service',
					'ref_service.kode_service',
					'ref_service.tipe'
					)
			->orderby('data_paket_item.id_paket_item', 'desc');

				}
	public function paketitem(){
  	return $this->hasMany('App\Models\data_paket_resep', 'id_paket_item');
  }
}

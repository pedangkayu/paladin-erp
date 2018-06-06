<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_paket_resep extends Model
{
    protected $table = 'data_paket_resep';
	protected $primaryKey = 'id_paket_resep';
	protected $fillable = [
		'id_paket_resep',
		'id_paket_item',
		'id_barang',
		'qty',
		'id_satuan',
		'harga_jual',
		'created_at',
		'updated_at',
	];

	// public function scopeHid($query, $id){
 //  		$cek = $query->join('data_paket_item', 'data_paket_item.id_paket_item', '=', 'data_paket_resep.id_paket_item')
 //  		->where('id_paket_item', $id);
 // 	 }

  // public function paketresep(){
  // 	return $this->hasMany('App\Models\data_resep_item', 'id_resep');
  // }
}

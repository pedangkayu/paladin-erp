<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_paket extends Model
{
     protected $table = 'data_paket';
	protected $primaryKey = 'id_paket';
	protected $fillable = [
		'id_paket',
		'nm_paket',
		'tarif',
		'tarif_rs',
		'tarif_dokter',
    	'status',//1 aktfi 2= tidak aktif
    	'id_paket',
		'created_at',
		'updated_at',
		];


	public function scopeHid($query, $id){
  		return $query->where('id_paket', $id);
 	 }

 	   	public function scopeBypaket($query, $nm_paket,  $req = []){
		if(!empty($req['nm_paket']))
			$query->where('data_paket.nm_paket','LIKE', '%' . $req['nm_paket'] . '%');
			$query->select(
			'data_paket.*'
		);
	}
	 public function scopePakettindakan($query, $req = []){
    $me = \Me::subgudang();
  
		if(!empty($req['nm_paket']))
			$query->where('data_paket.nm_paket','LIKE', '%' . $req['nm_paket'] . '%');
			$query->select(
			'data_paket.*'
		);
		}
	public function paket(){
  	return $this->hasMany('App\Models\data_paket_item', 'id_paket');
  }
}

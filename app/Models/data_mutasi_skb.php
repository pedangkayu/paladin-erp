<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_mutasi_skb extends Model
{
    protected $table='data_mutasi_skb';
    protected $primaryKey = 'id_mutasi_skb';
    protected $fillable=[
				'id_mutasi_spb',
				'no_mutasi_skb',
				'id_petugas',
				'id_departemen',
				'item',
				'keterangan',
				'status',
				'tipe',
				'id_unit_tujuan',
				'id_unit_asal',

				];

	public function scopeListsmb($query, $req = []){

		$akses = \Me::accessGudang();
		$gudang= \Me::subgudang()->id_gudang;
		$skb = $query->join('data_mutasi_spb', 'data_mutasi_spb.id_mutasi_spb', '=', 'data_mutasi_skb.id_mutasi_spb')
			->join('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_skb.id_departemen')
			->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_mutasi_skb.id_petugas')
			->join('data_mutasi_skb_item', 'data_mutasi_skb_item.id_mutasi_skb', '=', 'data_mutasi_skb.id_mutasi_skb')
			->join('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_mutasi_skb_item.id_gudang')
			->join('ref_gudang AS pemohon','pemohon.id_gudang', '=', 'data_mutasi_skb.id_unit_asal')
			// ->where('data_mutasi_skb.id_mutasi_skb', $id)
			->where('data_mutasi_skb.id_unit_tujuan',$gudang);
		if(count($req) > 0){
			if(!empty($req['no_mutasi_spb']))
				$skb->where('data_mutasi_spb.no_mutasi_spb','LIKE', '%'. $req['no_mutasi_spb'] . '%');
			if(!empty($req['no_mutasi_skb']))
				$skb->where('data_mutasi_skb.no_mutasi_skb', 'LIKE', '%'. $req['no_mutasi_skb'] . '%');
			if(!empty($req['pemohon']))
				$skb->where('data_mutasi_skb.id_unit_asal', $req['pemohon']);
			if(!empty($req['tanggal']))
				$skb->where(\DB::raw('DATE(data_mutasi_skb.created_at)'), $req['tanggal']);
		}
		$skb->select(
			'data_mutasi_skb.*',
			'data_mutasi_spb.id_mutasi_spb',
			'data_mutasi_spb.no_mutasi_spb',
			'data_departemen.nm_departemen',
			'data_karyawan.nm_depan',
			'data_karyawan.nm_belakang',
			'ref_gudang.nm_gudang',
			'pemohon.nm_gudang As nm_gudang_asal'
		)
		->groupby('data_mutasi_skb.id_mutasi_skb');
	}

public function scopeByid($query, $id){
	$gudang= \Me::subgudang()->id_gudang;
		return $query->join('data_mutasi_spb', 'data_mutasi_spb.id_mutasi_spb', '=', 'data_mutasi_skb.id_mutasi_spb')
			->join('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_skb.id_departemen')
			->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_mutasi_skb.id_petugas')
			->join('data_mutasi_skb_item', 'data_mutasi_skb_item.id_mutasi_skb', '=', 'data_mutasi_skb.id_mutasi_skb')
			->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_mutasi_skb_item.id_gudang')
			->join('ref_gudang AS pemohon','pemohon.id_gudang', '=', 'data_mutasi_skb.id_unit_asal')
			->where('data_mutasi_skb.id_mutasi_skb', $id)
			->where('data_mutasi_skb.id_unit_tujuan',$gudang)
			->select(
				'data_mutasi_skb.*',
				'data_mutasi_spb.no_mutasi_spb',
				'data_mutasi_spb.status AS status_spb',
				'data_departemen.nm_departemen',
				'data_karyawan.nm_depan',
				'data_karyawan.nm_belakang',
				'ref_gudang.nm_gudang',
				'pemohon.nm_gudang As nm_gudang_asal'
			)
			->groupby('data_mutasi_skb.id_mutasi_skb')
			->first();
	}
	public function scopeRekapsmb($query, $req= []){
		$gudang= \Me::subgudang()->id_gudang;
		$item = $query->join('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_skb.id_departemen')
				->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_mutasi_skb.id_petugas')
				->join('ref_gudang AS pemohon','pemohon.id_gudang', '=', 'data_mutasi_skb.id_unit_asal')
				->join('ref_gudang AS termohon','termohon.id_gudang', '=', 'data_mutasi_skb.id_unit_tujuan')
				->where('data_mutasi_skb.id_unit_tujuan',$gudang);
		if(!empty($req['tipe']))
			$item->where('data_barang.tipe', $req['tipe']);
		if(!empty($req['pemohon']))
			$item->where('data_mutasi_skb.id_unit_asal',$req['pemohon']);
		
		if(!empty($req['waktu']) && $req['waktu'] == 1)
			$item->where(\DB::raw('MONTH(data_mutasi_skb.created_at)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_mutasi_skb.created_at)'), $req['tahun']);
		else if(!empty($req['waktu']) && $req['waktu'] == 2)
			$item->whereBetween(\DB::raw('DATE(data_mutasi_skb.created_at)'), [$req['dari'], $req['sampai']]);

		$item->select(
			'data_mutasi_skb.*',
			'data_departemen.nm_departemen',
			'data_karyawan.nm_depan',
			'data_karyawan.nm_belakang',
			'pemohon.nm_gudang As nm_gudang_asal',
			'termohon.nm_gudang As nm_gudang_tujuan'
			);
	}
			
	
	public function rekapsmbu(){
			return $this->hasMany('App\Models\data_mutasi_skb_item', 'id_mutasi_skb');
	}
}

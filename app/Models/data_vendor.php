<?php

namespace App\Models;

use App\Models\data_hutang_vendor;
use Illuminate\Database\Eloquent\Model;

class data_vendor extends Model {

    protected $table 		= 'data_vendor';
	protected $primaryKey 	= 'id_vendor';
	protected $fillable 	= [
		'kode',
		'nm_vendor',
		'pemilik',
        'no_npwp',
		'alamat',
		'telpon',
		'fax',
		'status',
		'rating',
		'id_karyawan',
		'email',
		'website'
	];

	public function scopeListall($query, $req = [], $status = 1){
		$vendor = $query->leftJoin('data_hutang_vendor', 'data_hutang_vendor.id_vendor', '=', 'data_vendor.id_vendor')
			->where('data_vendor.status', $status);
		if(!empty($req)){

			if($req['hutang'] == 1){
				$vendor->where('data_hutang_vendor.status', 1);
			}else if($req['hutang'] == 2){
				$vendor->where('data_hutang_vendor.status', 1)
					->where(\DB::raw('DATE(tgl_jatuh_tempo)'), '<=', date('Y-m-d'));
			}


			if($req['nm_vendor'])
				$vendor->where('data_vendor.nm_vendor', 'LIKE', '%' . $req['nm_vendor'] . '%');
			if($req['kode'])
				$vendor->where('data_vendor.kode', $req['kode']);
			if($req['tanggal'])
				$vendor->where(\DB::raw('DATE(`data_vendor`.`created_at`)'), $req['tanggal']);
		}

		$vendor->select('data_vendor.*');
	}

	public function scopeView($query, $id){
		return $query->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_vendor.id_karyawan')
			->where('data_vendor.id_vendor', $id)
			->where('data_vendor.status', 1)
			->select('data_vendor.*', 'data_karyawan.nm_depan', 'data_karyawan.nm_belakang')
			->first();
	}


	public function scopeListbiling($query){
		return $query->join('data_faktur', 'data_faktur.id_vendor', '=', 'data_vendor.id_vendor')
			->join('data_jurnal', 'data_jurnal.id_faktur', '=', 'data_faktur.id_faktur')
			->groupby('data_vendor.id_vendor')
			->select('data_vendor.*');

	}

}

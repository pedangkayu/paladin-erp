<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_barang extends Model{

    protected $table = 'data_log_barang';
	protected $primaryKey = 'id_log_barang';
	protected $fillable = [
		'id_barang',
		'qty',
		'keterangan',
		'id_gudang',
		'kondisi', /* 1:in|2:out|3:return | 4: refund barang pasien*/ 
		'tipe', /* 1:SKB|2:SPBM|3:penyesuaian|4:return Internal|5:return Eksternal |6:resep Obat  7treatmen */ //8=mutasi barang 9=/retur obt resep
		'id_parent', /* ID  SKB atau SPBM */
		'id_karyawan'
	];

	public function scopeKartostokbyitem($query, $req = []){
		$ks = $query->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_log_barang.id_karyawan')
			->where('data_log_barang.id_barang', $req['barang']);
			if($req['waktu'] == 1){
				$ks->where(\DB::raw('DATE(data_log_barang.created_at)'), '<=', $req['tahun'] . '-' . $req['bulan'] . '-01');
			}else{
				$ks->whereBetween(\DB::raw('DATE(data_log_barang.created_at)'), [$req['dari'], $req['sampai']]);
			}

			if(!empty($req['gudang']))
				$ks->where('data_log_barang.id_gudang', $req['gudang']);
			else
				$ks->where('data_log_barang.id_gudang', 0);

			$ks->orderby('data_log_barang.id_log_barang', 'asc')
				->select(
				'data_log_barang.*',
				'data_karyawan.nm_depan',
				'data_karyawan.nm_belakang'
			);
	}

	public function scopeSisapriode($query, $req = []){
		$ks = $query->where('id_barang', $req['barang']);
		if($req['waktu'] == 1){
			$ks->where(\DB::raw('DATE(data_log_barang.created_at)'), '<', $req['tahun'] . '-' . $req['bulan'] . '-01');
		}else{
			$ks->where(\DB::raw('DATE(created_at)'), '<', $req['dari']);
		}

		if(!empty($req['gudang']))
			$ks->where('data_log_barang.id_gudang', $req['gudang']);
		else
			$ks->where('data_log_barang.id_gudang', 0);

		$ks->select('qty', 'kondisi');
	}

	public function scopeGudangkecil($query, $req = []){

		$ks = $query->join('data_barang','data_barang.id_barang','=','data_log_barang.id_barang')
			->join('ref_gudang','ref_gudang.id_gudang','=','data_log_barang.id_gudang')
			->where('data_log_barang.id_gudang', $req['id_gudang']);
		if($req['waktu'] == 1){
			$ks->where(\DB::raw('DATE(data_log_barang.created_at)'), '<', $req['tahun'] . '-' . $req['bulan'] . '-01');
		}else{
			$ks->whereBetween(\DB::raw('DATE(data_log_barang.created_at)'), [$req['dari'], $req['sampai']]);
		}

		$ks->select('data_barang.nm_barang','data_barang.in','ref_gudang.nm_gudang','data_barang.out','data_log_barang.qty', 'data_log_barang.created_at');
	}
}

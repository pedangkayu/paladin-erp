<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_pasien extends Model {

    protected $table = 'data_log_pasien';
	protected $primaryKey = 'id_log_pasien';
	protected $fillable = [
		'id_pasien',
		'id_layanan',
		'tipe', // 1= resep  2= Treatment 3=rawat inap
		'nomor_antrian',
		'no_antrian_hc',
		'nama_pasien',
		'id_kelas',
		'user_validasi',
		'waktu_validasi',
        'waktu_transaksi',
		'status_validasi', //1 validasi
		'status' /* 1:in | 2:Out */

	];

	public function scopePindah($query, $id){
		$query->leftJoin('ref_kelas', 'ref_kelas.id_kelas', '=', 'data_log_pasien.id_kelas')
		->leftJoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_log_pasien.id_pasien')
		->where('data_log_pasien.id_layanan', $id)
		->select(
			'data_pasien.nama_pasien',
			'data_pasien.alamat_pasien',
			'data_pasien.kota_pasien',
			'data_pasien.hp_pasien',
			'data_pasien.id_pasien_hc',
			'ref_kelas.nm_kelas as k',
			'data_log_pasien.*');
	}
	public function scopeCheckin($query, $req = []){
		$user = $query->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_log_pasien.id_pasien');

		if(!empty($req['id_pasien'])){
			$user->where('data_pasien.id_pasien_hc', $req['id_pasien']);
		}

		$user->where('data_log_pasien.status', 1)
			->select(
				'data_pasien.alamat_pasien',
				'data_pasien.nama_pasien',
				'data_pasien.tgllahir_pasien',
				'data_log_pasien.id_pasien'
			)
			->groupby('data_log_pasien.id_pasien');
	}

	public function scopeResep($query, $id){
		$items = $query->where('id_pasien', $id)->where('tipe', 1)->where('status', 1)->get();
		$res = [];
		if(count($items) > 0):
		foreach($items as $item){
			$res[] = $item->id_layanan;
		}
		endif;

		return [
			'result' => $res
		];
	}

	public function scopeTreatment($query, $id){
		$items = $query->where('id_pasien', $id)->where('tipe', 2)->where('status', 1)->get();
		$res = [];
		if(count($items) > 0):
		foreach($items as $item){
			$res[] = $item->id_layanan;
		}
		endif;

		return [
			'result' => $res
		];
	}


	public function scopeRinap($query, $id){
		$items = $query->where('id_pasien', $id)->where('tipe', 3)->where('status', 1)->get();
		$res = [];
		if(count($items) > 0):
		foreach($items as $item){
			$res[] = $item->id_layanan;
		}
		endif;

		return [
			'result' => $res
		];
	}

	public function scopePasiens($query, $req = []){

        $item = $query->groupby('nama_pasien')->groupby('status')->orderby('waktu_transaksi','desc');

		if(!empty($req['nama']))
			$item->where('nama_pasien', 'LIKE', '%' . $req['nama'] . '%');
		if(!empty($req['tanggal']))
			$item->where(\DB::raw('DATE(waktu_transaksi)'), $req['tanggal']);
		if(!empty($req['status']))
			$item->where('status', $req['status']);

		$item->select('id_pasien', 'nama_pasien', 'status', 'created_at');

	}

}

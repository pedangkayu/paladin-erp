<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_faktur extends Model {
    
	protected $table 		= 'data_faktur';
	protected $primaryKey 	= 'id_faktur';
	protected $fillable 	= [
		'nomor_faktur',
		'nomor_type',
		'prefix',
		'type', /* 1:faktur pembelian|2:biling  3: pendapatan */
		'id_vendor',
		'id_pasien',
		'id_po',
		'tgl_faktur',
		'id_payer',
		'duodate',
		'id_payment_terms',
		'id_acc_faktur', // yang memberi acc tukar faktur
		'tgl_tukar_faktur', // tgl tukar faktur
		'status_faktur', //0 default = 1 tukar faktur
		'ppn',
		'diskon',
		'adjustment',
		'subtotal',
		'total',
		'keterangan',
		'status', /* 0:belum bayar|1:nyicil|2:lunas|3:delete */
		'id_karyawan',
		'amount_due',
		'payment_status_pembayaran' // 1:belum menentukan payment | 2 : sudah menentukan payment | 3 : sudah input ke coa
	];

	public function scopeViews($query, $id){

		return $query->join('data_vendor', 'data_vendor.id_vendor', '=', 'data_faktur.id_vendor')
			->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
			->whereIn('data_faktur.status', [0,1,2])
			->where('data_faktur.id_faktur', $id)
			->select(
				'data_faktur.*', 
				'data_vendor.kode',
				'data_vendor.nm_vendor', 
				'data_vendor.alamat',
				'data_vendor.telpon',
				'data_vendor.email',
				'ref_payment_terms.payment_terms'
			);


	}
	public function scopeViewpendapatan($query, $id){
		return $query->join('data_payer', 'data_payer.id_payer', '=', 'data_faktur.id_payer')
			->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
			->whereIn('data_faktur.status', [0,1,2])
			->where('data_faktur.id_faktur', $id)
			->select(
				'data_faktur.*', 
				'data_payer.kode',
				'data_payer.nm_payer', 
				'data_payer.nm_last', 
				'data_payer.alamat',
				'data_payer.telpon',
				'data_payer.email',
				'ref_payment_terms.payment_terms'
			);
	}

	public function scopeDaftar($query, $req = []){
		$item = $query;
			$item->whereIn('data_faktur.type',[1]);
		if(!empty($req['no_faktur']))
			$item->where('nomor_faktur', $req['no_faktur']);
		if(!empty($req['tanggal']))
			$item->where('tgl_faktur', $req['tanggal']);
		if(!empty($req['duodate']))
			$item->where('duodate', $req['duodate']);
		if(isset($req['status']) && $req['status'] != '-')
			$item->where('status', $req['status']);
		else
			$item->whereIn('status', [0,1,2]);

	}
	public function scopePendapatan($query, $req =[]){
		$data = $query;
			$data->whereIn('data_faktur.type',[3]);
			if(!empty($req['no_faktur']))
				$data->where('nomor_faktur', $req['no_faktur']);
			if(!empty($req['tanggal']))
				$data->where('tgl_faktur', $req['tanggal']);
			if(!empty($req['duodate']))
				$data->where('duodate', $req['duodate']);
			if(isset($req['status']) && $req['status'] != '-')
				$data->where('status', $req['status']);
			else
				$data->whereIn('status', [0,1,2]);
	}

	/* BILING */
	public function scopeDaftarbiling($query, $data = []){
		$items = $query->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien');
		
		if(!empty($data['no_faktur']))
			$items->where('nomor_faktur', 'LIKE', '%'.$data['no_faktur']);
		if(!empty($data['tanggal']))
			$items->where('tgl_faktur', $data['tanggal']);
		if(!empty($data['duodate']))
			$items->where('duodate', $data['duodate']);
		if($data['status'] != '-')
			$items->where('data_faktur.status', $data['status']);
		else
			$items->whereIn('data_faktur.status', [0,1,2]);

		$items->where('type', 2)
			->select('data_faktur.*', 'data_pasien.nama_pasien')
			->groupby('data_faktur.nomor_faktur');

	}

	public function scopeBiling($query, $id){
		return $query->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
			->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
			->where('data_faktur.id_faktur', $id)
			->select(
				'data_faktur.*', 
				'data_pasien.nama_pasien', 
				'ref_payment_terms.payment_terms',
				'data_pasien.alamat_pasien'
			);
	}
	public function scopeAsuransi($query, $req=[]){
		$a=$query->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
						//->join('data_jurnal_pembayaran','data_jurnal_pembayaran.id_faktur', '=', 'data_faktur.id_faktur')
						->whereIn('data_faktur.type',[2]);
						if(!empty($req['waktu']) && $req['waktu'] == 1)
			$a->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
		else if(!empty($req['waktu']) && $req['waktu'] == 2)
			$a->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']])
						->select('data_faktur.*',
							'data_pasien.nama_pasien'
							);
						
			// if(!empty($req['asuransi']))
			// $a->where('data_jurnal_pembayaran.id_asuransi', $req['asuransi']);
			

	}	
	public function rekapasn()
	{
		return $this->hasMany('App\Models\data_jurnal_pembayaran', 'id_faktur');
	}


	public function scopeForvendor($query, $id, $req = []){
		$item = $query->leftJoin('data_hutang_vendor', 'data_hutang_vendor.id_faktur', '=', 'data_faktur.id_faktur')
			->where('data_faktur.id_vendor', $id);

		if(!empty($req['no_faktur']))
			$item->where('data_faktur.nomor_faktur', 'LIKE', '%' . $req['no_faktur'] . '%');
		if(!empty($req['tanggal']))
			$item->where('data_faktur.tgl_faktur', $req['tanggal']);

		if(!empty($req['unpaid']) && $req['unpaid'] == "true")
			$item->whereIn('data_faktur.status', [0,1]);
		if(!empty($req['jatuhtempo']) && $req['jatuhtempo'] == "true")
			$item->where('data_hutang_vendor.status', 1)->where(\DB::raw('DATE(data_hutang_vendor.tgl_jatuh_tempo)'), '<=', date('Y-m-d'));
		

		$item->select('data_faktur.*');
	}


}

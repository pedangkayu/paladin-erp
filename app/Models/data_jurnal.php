<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_jurnal extends Model {

    protected $table 		= 'data_jurnal';
	protected $primaryKey 	= 'id_jurnal';
	protected $fillable 	= [
		'id_faktur',
		'id_coa',
		'tanggal',
		'deskripsi',
		'id_payment_methode',
		'tipe', // 1:dr|2:cr
		'total',
		'id_karyawan',
		'keterangan_voucer',
		
		'tipe_jurnal', 
			/* 
				---> Faktur pembelian
					1:faktur
					2:saldo_awal
					3:adjustment pembelian
					4:PPH Pembelian
					5:Pembeyaran cash Pembelian
					6:Hutang pembelian

				---> Biling
					7:Piutang usaha
					8:item tambahan
					9:Coa Resep Pendapatan
					10:Coa Persediaan
					11:Coa biaya² HPP
					12:Biaya DR
					13:Biaya RS
					14:Pendapatan RS

					15:persediaan barang BHP
					16:HPP BHP
					17:Pendapatan BHP

					18:Pembayaran saldo
					19:payment Method
					20:jurnal Pembayaran

					---> DEPOSIT
					21:Penambahan Deposit ke kas /bank
					22:Pendapatan dibayar dimuka

					23:Penurangan saldo dari kas
					24:debit ke Pendpatan dibayar dimuka

				--> Biling
					25:Coa Resep Pendapatan (campur)
					26:Coa Persediaan (campur)
					27:Coa biaya² HPP (campur)

					28:Pendeapatan Rinap

				--> Pendaptan
					29:piutang usaha pendapatan
					30:Coa pendapatan lainnya
					31:Pembayaran pendapatan Piutang usaha
					32:Pembayaran pendapatan bank

			*/


		'id_option', // id yang bersangkutan dengan jurnal
		'link_slug',

		'debit',
		'kredit',
		'id_voucer_jurnal'

	];

	public function scopeFaktur($query, $id){
		return $query->join('ref_coa', 'ref_coa.id_coa', '=', 'data_jurnal.id_coa')
			->where('data_jurnal.id_faktur', $id)
			->where('data_jurnal.tipe', 2)
			->select(
				'data_jurnal.tanggal',
				'ref_coa.nm_coa AS akun',
				'data_jurnal.deskripsi',
				'data_jurnal.total'
			);
	}


	public function scopeLaporanbiling($query, $id_voucer_jurnal, $req = []){
		$item = $query->leftJoin('data_faktur', 'data_faktur.id_faktur', '=', 'data_jurnal.id_faktur')
			->join('ref_coa', 'ref_coa.id_coa', '=', 'data_jurnal.id_coa')
			->where('data_jurnal.id_voucer_jurnal', $id_voucer_jurnal);
		
		if($req['jurnal'] == 3){
			$item->where('data_jurnal.id_coa', $req['id_coa']);
		}

		$item->select('data_jurnal.*', 'ref_coa.kode', 'ref_coa.nm_coa')
			->groupBy('data_jurnal.id_jurnal')
			->orderby('data_jurnal.id_jurnal', 'asc');
	}


	 public function scopeLogakun($query,$id_coa, $req = []){
	 	$item = $query->where('id_coa', $id_coa);

	 		if(!empty($req['dari']) && !empty($req['sampai']))
	 		$item->whereBetween(\DB::raw('DATE(data_jurnal.tanggal)'), [$req['dari'], $req['sampai']]);

	 	$item->orderby('tanggal', 'asc');

	 }


	 public function scopeBalance($query, $id_coa, $tipe, $req = []){

	 	$ju = $query->where('id_coa', $id_coa)
	 		->where('tipe', $tipe);

	 	if(!empty($req['dari']) && !empty($req['sampai']))
	 		$ju->whereBetween(\DB::raw('DATE(data_jurnal.tanggal)'), [$req['dari'], $req['sampai']]);

	 }
	 public function scopeRekap($query, $req=[]){
				$a=$query->leftjoin('data_faktur','data_faktur.id_faktur', '=', 'data_jurnal.id_faktur')
						->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_method', 'ref_payment_method.id_payment_method', '=', 'data_jurnal.id_payment_methode')
						->whereIn('data_faktur.type',[2]);
					if(!empty($req['metode']))
							$a->where('data_jurnal.id_payment_methode', $req['metode']);
						if(!empty($req['waktu']) && $req['waktu'] == 1)
						$a->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$a->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']]);
						$a->select('data_faktur.nomor_faktur',
							'data_pasien.nama_pasien',
							'data_jurnal.*'
							);

	}


	public function scopeBukubesar($query, $req = []){
		$item = $query->join('ref_coa', 'ref_coa.id_coa', '=', 'data_jurnal.id_coa');

		if(!empty($req['dari']) && !empty($req['dari']))
			$item->whereBetween(\DB::raw('DATE(data_jurnal.created_at)'), [$req['dari'], $req['sampai']]);

		if(empty($req['all'])){
			$item->whereBetween('ref_coa.seri', [$req['coa_dari'], $req['coa_sampai']]);
		}

		$item->select(
		'data_jurnal.id_coa',
		'data_jurnal.tanggal',
		'ref_coa.kode',
		'ref_coa.nm_coa',
		'data_jurnal.deskripsi AS keterangan',
		'data_jurnal.debit',
		'data_jurnal.kredit');
		$item->orderBy('ref_coa.kode', 'asc');
	}


	public function scopeNeracasaldo($query, $req = []){
		$item = $query->join('ref_coa', 'ref_coa.id_coa', '=', 'data_jurnal.id_coa');
		if(!empty($req['dari']) && !empty($req['dari']))
			$item->whereBetween(\DB::raw('DATE(data_jurnal.created_at)'), [$req['dari'], $req['sampai']]);

		$item->select(
			'ref_coa.id_coa',
			'ref_coa.seri',
			'ref_coa.kode',
			'ref_coa.nm_coa',
			\DB::raw('SUM(data_jurnal.debit) AS debit'),
			\DB::raw('SUM(data_jurnal.kredit) AS kredit')
			// \DB::raw('IF(data_jurnal.debit > data_jurnal.kredit,SUM(data_jurnal.debit - data_jurnal.kredit), 0) AS saldo_debit'),
			// \DB::raw('IF(data_jurnal.kredit > data_jurnal.debit,SUM(data_jurnal.kredit - data_jurnal.debit), 0) AS saldo_kredit')
		);
		$item->groupBy('data_jurnal.id_coa')
			->orderby('ref_coa.seri', 'asc');
	}

}

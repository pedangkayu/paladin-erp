<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_jurnal_pembayaran extends Model {

    protected $table 		= 'data_jurnal_pembayaran';
	protected $primaryKey 	= 'id_jurnal_pembayaran';
	protected $fillable 	= [
		'id_faktur',
		'id_asuransi',
		'no_asuransi',
		'id_bank',
		'id_pasien',
		'tipe_payment_method',
			/*
				1:Cash
				2:Bank
				3:Subsidi / CSR
				4:Subangan
				5:Piutang (Asuransi)
				6:Deposit
			*/
		'jumlah',
		'id_karyawan',
    'id_payment_method_item'
	];


		public function scopeAsuransi($query, $req=[]){
				$a=$query->leftjoin('data_faktur','data_faktur.id_faktur', '=', 'data_jurnal_pembayaran.id_faktur')
						->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
						->join('ref_asuransi','ref_asuransi.id_asuransi','=', 'data_jurnal_pembayaran.id_asuransi')
						->whereIn('data_faktur.type',[2])
						->whereIn('data_jurnal_pembayaran.tipe_payment_method',[5]);
					if(!empty($req['asuransi']))
							$a->where('data_jurnal_pembayaran.id_asuransi', $req['asuransi']);
						if(!empty($req['waktu']) && $req['waktu'] == 1)
						$a->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$a->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']]);
						$a->select('data_faktur.nomor_faktur',
							'data_pasien.nama_pasien',
							'ref_asuransi.nm_asuransi',
							'data_jurnal_pembayaran.*'
							);

		}
		public function scopeBank($query, $req=[]){
			$rekap=$query->join('data_faktur','data_faktur.id_faktur', '=', 'data_jurnal_pembayaran.id_faktur')
						->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
						->join('ref_bank', 'ref_bank.id_bank','=', 'data_jurnal_pembayaran.id_bank')
						->whereIn('data_jurnal_pembayaran.tipe_payment_method',[2])
						->whereIn('data_faktur.type',[2]);
					if(!empty($req['bank']))
							$rekap->where('data_jurnal_pembayaran.id_bank', $req['bank']);
					if(!empty($req['waktu']) && $req['waktu'] == 1)
						$rekap->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$brekap->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']]);
						$rekap->select('data_faktur.nomor_faktur',
							'data_pasien.nama_pasien',
							'ref_bank.nm_bank',
							'data_jurnal_pembayaran.*'
							);
		}
		public function scopeCash($query, $req=[]){
			$rekap=$query->join('data_faktur','data_faktur.id_faktur', '=', 'data_jurnal_pembayaran.id_faktur')
						->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
						->whereIn('data_jurnal_pembayaran.tipe_payment_method',[1])
						->whereIn('data_faktur.type',[2]);
					if(!empty($req['waktu']) && $req['waktu'] == 1)
						$rekap->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$brekap->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']]);
						$rekap->select('data_faktur.nomor_faktur',
							'data_pasien.nama_pasien',
							'data_jurnal_pembayaran.*'
							);
		}
		public function scopeSubsidi($query, $req=[]){
			$rekap=$query->join('data_faktur','data_faktur.id_faktur', '=', 'data_jurnal_pembayaran.id_faktur')
						->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
						->whereIn('data_jurnal_pembayaran.tipe_payment_method',[3])
						->whereIn('data_faktur.type',[2]);
					if(!empty($req['waktu']) && $req['waktu'] == 1)
						$rekap->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$brekap->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']]);
						$rekap->select('data_faktur.nomor_faktur',
							'data_pasien.nama_pasien',
							'data_jurnal_pembayaran.*'
							);
		}
		public function scopeSumbangan($query, $req=[]){
			$rekap=$query->join('data_faktur','data_faktur.id_faktur', '=', 'data_jurnal_pembayaran.id_faktur')
						->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
						->whereIn('data_jurnal_pembayaran.tipe_payment_method',[4])
						->whereIn('data_faktur.type',[2]);
					if(!empty($req['waktu']) && $req['waktu'] == 1)
						$rekap->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$brekap->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']]);
						$rekap->select('data_faktur.nomor_faktur',
							'data_pasien.nama_pasien',
							'data_jurnal_pembayaran.*'
							);
		}
		public function scopeDeposit($query, $req=[]){
			$rekap=$query->join('data_faktur','data_faktur.id_faktur', '=', 'data_jurnal_pembayaran.id_faktur')
						->join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_faktur.id_pasien')
						->join('ref_payment_terms', 'ref_payment_terms.id_payment_terms', '=', 'data_faktur.id_payment_terms')
						->whereIn('data_jurnal_pembayaran.tipe_payment_method',[6])
						->whereIn('data_faktur.type',[2]);
					if(!empty($req['waktu']) && $req['waktu'] == 1)
						$rekap->where(\DB::raw('MONTH(data_faktur.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(data_faktur.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$brekap->whereBetween(\DB::raw('DATE(data_faktur.created_at)'), [$req['dari'], $req['sampai']]);
						$rekap->select('data_faktur.nomor_faktur',
							'data_pasien.nama_pasien',
							'data_jurnal_pembayaran.*'
							);
		}
}

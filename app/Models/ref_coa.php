<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_coa extends Model
{
    protected $table 		= 'ref_coa';
	protected $primaryKey 	= 'id_coa';
	protected $fillable 	= [
		'parent_id',
		'type',
		'kode',
		'grup', //grup=1 leadger=2
		'nm_coa',
		'status',
		'keterangan',
		'balance',
		'cash',
		'saldo_awal',
		'masuk',
		'keluar',
		'coa_kategori'

		/*
			Harta 1
			kewajiban   2
			modal   3
			Pendapatan  4
			biaya²   5
			pendapatan diluar usaha 6
			biaya di luar usaha 7
			pajak 8
		*/
	];

	public function scopeKategori($query, $tipe, $req = []){
		$item = $query->where('ref_coa.coa_kategori', $tipe);

		$dari = empty($req['dari']) ? date('Y-m-d', strtotime('-1 Month', time())) : $req['dari'];
		$sampai = empty($req['sampai']) ? date('Y-m-d') : $req['sampai'];

		$item->select(
			'ref_coa.nm_coa', 
			'ref_coa.kode', 
			'ref_coa.type AS tipe',
			// DEBIT 
			\DB::raw('(

				SELECT
					SUM(data_jurnal.debit)
				FROM
					data_jurnal
				WHERE
					data_jurnal.id_coa = ref_coa.id_coa
				AND DATE(data_jurnal.tanggal) BETWEEN "' . $dari . '"
				AND "' . $sampai . '"

			) AS debit'),
			// KREDIT
			\DB::raw('(

				SELECT
					SUM(data_jurnal.kredit)
				FROM
					data_jurnal
				WHERE
					data_jurnal.id_coa = ref_coa.id_coa
				AND DATE(data_jurnal.tanggal) BETWEEN "' . $dari . '"
				AND "' . $sampai . '"

			) AS kredit')
		);

		$item->orderBy('ref_coa.seri', 'asc');

	}


	public function scopeAruskas($query, $kode, $req = []){
		$item = $query->where('ref_coa.kode', $kode)
			->leftJoin('data_jurnal', 'data_jurnal.id_coa', '=', 'ref_coa.id_coa');

		$dari = empty($req['dari']) ? date('Y-m-d', strtotime('-1 Month', time())) : $req['dari'];
		$sampai = empty($req['sampai']) ? date('Y-m-d') : $req['sampai'];

		$item->select(
			'ref_coa.id_coa', 
			'ref_coa.kode', 			
			// DEBIT 
			\DB::raw('SUM(data_jurnal.debit) AS debit, SUM(data_jurnal.kredit) AS kredit')
		);
		$item->groupby('ref_coa.id_coa');
		$item->orderBy('ref_coa.seri', 'asc');

	}
	
	public function scopeNeracasaldo($query, $ids = []){
		return $query->whereNotIn('id_coa', $ids)
			->where('grup', 2)
			->select('kode', 'seri', 'nm_coa', \DB::raw('0 AS kredit, 0 AS debit'));
	}

	public function ledger() {
		return $this->hasMany('App\Models\ref_coa_ledger','grup_coa');
	}

	public function kodeservice(){
		return $this->hasMany('App\Models\ref_service_kode', 'coa');
	}
}

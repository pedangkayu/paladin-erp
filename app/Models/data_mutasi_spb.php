<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_mutasi_spb extends Model
{
     protected $table 		='data_mutasi_spb';
     protected	$primaryKey	='id_mutasi_spb';
     protected $fillable	=[

				'no_mutasi_spb',
				'id_departemen',
				'id_pemohon',
				'id_acc',
				'keterangan',
				'deadline',
				'status', /*1:baru  4:hapus/batal | 5:diselesaika */
				'id_unit_tujuan',
				'id_unit_asal',
				'tipe',
				'tgl_approval',

     		];
     public function scopeTermohon($query,$kode, $pemohon_gud, $status = 0, $req=[])
     {
     	
     	$gudang= \Me::subgudang()->id_gudang;
		$termohon = $query->Leftjoin('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_spb.id_departemen')
					->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_mutasi_spb.id_pemohon')
					->leftjoin('ref_gudang AS termohon', 'termohon.id_gudang', '=', 'data_mutasi_spb.id_unit_tujuan')
					->leftjoin('ref_gudang AS pemohon','pemohon.id_gudang', '=', 'data_mutasi_spb.id_unit_asal')
					->where('data_mutasi_spb.id_unit_tujuan',$gudang);
			if(!empty($deadline))
				$termohon->where('data_mutasi_spb.deadline', $deadline);
			if(!empty($kode))
					$termohon->where('data_mutasi_spb.no_mutasi_spb', 'LIKE', '%'. $kode.'%');
			if(!empty($req['pemohon_gud']))
					$termohon->where('data_mutasi_spb.id_unit_asal', $req['pemohon_gud']);

			if(!empty($req['status']))
					$termohon->where('data_mutasi_spb.status', $req['status']);		
			else
			$termohon->whereIn('data_mutasi_spb.status', [1,2,3]);
			$termohon->orderby('data_mutasi_spb.id_mutasi_spb', 'desc')
				->select('data_mutasi_spb.*',
				 	'data_departemen.nm_departemen', 
				 	'data_karyawan.nm_depan', 'data_karyawan.nm_belakang',
				 	'pemohon.nm_gudang as gudang_pemohon'
				 	);
	}
	public function scopePemohon($query,$kode,$gtujuan=0, $status = 0, $req=[])
     {
     	$gudang= \Me::subgudang()->id_gudang;
		$pemohon = $query->Leftjoin('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_spb.id_departemen')
					->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_mutasi_spb.id_pemohon')
					->join('ref_gudang AS termohon', 'termohon.id_gudang', '=', 'data_mutasi_spb.id_unit_tujuan')
					->join('ref_gudang AS pemohon','pemohon.id_gudang', '=', 'data_mutasi_spb.id_unit_asal')
					->where('data_mutasi_spb.id_unit_asal',$gudang);

				if(!empty($req['no_verif']) && $req['no_verif'] == 'true')
					$pemohon->where('data_mutasi_spb.id_acc', 0);
				
				// if(!empty($req['finish_smb']) && $req['finish_smb'] == 'true')
				// 	$pemohon->where('data_mutasi_spb.status',5);

				if(!empty($kode))
					$pemohon->where('data_mutasi_spb.no_mutasi_spb', 'LIKE', '%'. $kode.'%');
				if(!empty($req['gtujuan']))
						$pemohon->where('data_mutasi_spb.id_unit_tujuan', $req['gtujuan']);

				if(!empty($req['status']))
						$pemohon->where('data_mutasi_spb.status', $req['status']);
				else
				$pemohon->whereIn('data_mutasi_spb.status', [1,2]);
				$pemohon->orderby('data_mutasi_spb.id_mutasi_spb', 'desc')
					->select('data_mutasi_spb.*',
					 	'data_departemen.nm_departemen', 
					 	'data_karyawan.nm_depan', 'data_karyawan.nm_belakang',
					 	'termohon.nm_gudang as gudang_termohon'
					 	);
	}
	public function scopeByid($query, $id){
			$gudang= \Me::subgudang()->id_gudang;
		return $query->leftjoin('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_spb.id_departemen')
					->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_mutasi_spb.id_pemohon')
					->join('ref_gudang AS termohon', 'termohon.id_gudang', '=', 'data_mutasi_spb.id_unit_tujuan')
					->join('ref_gudang AS pemohon','pemohon.id_gudang', '=', 'data_mutasi_spb.id_unit_asal')
					->where('data_mutasi_spb.id_unit_asal',$gudang)
					->where('data_mutasi_spb.id_mutasi_spb',$id)
					->select('data_mutasi_spb.*',
					 	'data_departemen.nm_departemen', 
					 	'data_karyawan.nm_depan', 'data_karyawan.nm_belakang',
					 	'termohon.nm_gudang as gudang_termohon'
					 	);

	}
	public function scopeRekappmbu($query, $req = []){
		$gudang= \Me::subgudang()->id_gudang;
		$item = $query->leftjoin('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_spb.id_departemen')
			->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_mutasi_spb.id_pemohon')
			->join('ref_gudang AS termohon', 'termohon.id_gudang', '=', 'data_mutasi_spb.id_unit_tujuan')
			->join('ref_gudang AS pemohon','pemohon.id_gudang', '=', 'data_mutasi_spb.id_unit_asal')
			->where('data_mutasi_spb.id_unit_asal',$gudang);
			
		if(!empty($req['tipe']))
			$item->where('data_barang.tipe', $req['tipe']);

		if(!empty($req['waktu']) && $req['waktu'] == 1)
			$item->where(\DB::raw('MONTH(data_mutasi_spb.created_at)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_mutasi_spb.created_at)'), $req['tahun']);
		else if(!empty($req['waktu']) && $req['waktu'] == 2)
			$item->whereBetween(\DB::raw('DATE(data_mutasi_spb.created_at)'), [$req['dari'], $req['sampai']]);

		$item->select(
			'data_mutasi_spb.*',
			'data_departemen.nm_departemen',
			'data_karyawan.nm_depan',
			'data_karyawan.nm_belakang',
			'termohon.nm_gudang as gudang_termohon'
			);
	}
	public function spbm(){
  	return $this->hasMany('App\Models\data_mutasi_spb_item', 'id_mutasi_spb');
  }

}

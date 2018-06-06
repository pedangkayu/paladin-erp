<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_deposit extends Model
{
    protected $table ='data_deposit';
    protected $primaryKey='id_deposit';
    protected $fillable =[
			'id_pasien',
			'saldo',
			'id_karyawan',
			'catatan',
			'tanggal',
			];

			public function scopeByidpasien($query, $id_pasien_hc=0, $nama_pasien=0, $req = []){
  		// dd($status);
	   
		    $saldo = $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_deposit.id_pasien')
				->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_deposit.id_karyawan');
		    if(!empty($id_pasien_hc))
		      $saldo->where('data_pasien.id_pasien_hc', 'LIKE' , '%'. $id_pasien_hc .'%');
		   if(!empty($nama_pasien))
		      $saldo->where('data_pasien.nama_pasien', 'LIKE', '%'. $nama_pasien.'%');
		 
		    $saldo->orderby('data_deposit.id_deposit', 'desc')
		    ->select(
		    'data_deposit.*',
		    'data_pasien.nama_pasien',
		    'data_pasien.alamat_pasien',
		    'data_pasien.kota_pasien',
		    'data_pasien.hp_pasien',
		    'data_karyawan.nm_depan',
		    'data_karyawan.nm_belakang',
		    'data_pasien.id_pasien_hc'
		  );
  		}
  		public function scopeHid($query, $id){
		return $query->leftjoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_deposit.id_pasien')
		->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_deposit.id_karyawan')
			->where('data_deposit.id_deposit', $id)
			->select(
			'data_deposit.*',
			'data_pasien.nama_pasien',
			'data_pasien.alamat_pasien',
			'data_pasien.kota_pasien',
			'data_pasien.hp_pasien',
			'data_karyawan.nm_depan',
			'data_karyawan.nm_belakang',
			'data_pasien.id_pasien_hc',
			'data_pasien.alamat_pasien',
			'data_pasien.telp_pasien'
		);

	}
    }

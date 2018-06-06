<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_deposit extends Model
{
   protected $table = 'data_log_deposit';
    protected $primaryKey 	= 'id_log_deposit';
	protected $fillable 	= [
				'id_pasien',
				'id_payment_method',
				'keterangan',
				'id_bank',
				'id_deposit',
				'masuk',
				'id_karyawan',
				'keluar',
				'catatan',
	];
	 public function scopeByid($query, $id){

		$query->leftJoin('ref_bank', 'ref_bank.id_bank', '=', 'data_log_deposit.id_bank')
			->leftJoin('data_karyawan', 'data_karyawan.id_karyawan', '=' ,'data_log_deposit.id_karyawan')
				
				->where('data_log_deposit.id_deposit', $id)
				->select(
					'data_log_deposit.*',
					'ref_bank.cabang',
					'ref_bank.nm_bank',
					
					'data_karyawan.nm_depan',
					'data_karyawan.nm_belakang'
					)
				->orderby('created_at', 'dsc');
	}
}

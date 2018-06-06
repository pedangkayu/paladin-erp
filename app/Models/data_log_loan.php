<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_loan extends Model
{
   	protected $table = 'data_log_loan';
    protected $primaryKey 	= 'id_log_loan';
	protected $fillable 		= [
						'id_loan',
						'keterangan',
						'id_bank',
						'id_payment_method',
						'sisa_hutang', //sisa hutang stelah di bayar
						'bayar', //jumlah di bayr saat itu
						'id_user',
						'catatan',

					];
		public function scopeByid($query, $id){
		$query->leftJoin('ref_bank', 'ref_bank.id_bank', '=', 'data_log_loan.id_bank')
			->leftJoin('data_karyawan', 'data_karyawan.id_karyawan', '=' ,'data_log_loan.id_user')
				
				->where('data_log_loan.id_loan', $id)
				->select(
					'data_log_loan.*',
					'ref_bank.cabang',
					'ref_bank.nm_bank',
					'data_karyawan.nm_depan',
					'data_karyawan.nm_belakang'
					)
				->orderby('created_at', 'dsc');
	}
}

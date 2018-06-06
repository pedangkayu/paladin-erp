<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_payment_method_item extends Model {

    protected $table = 'ref_payment_method_item';
	protected $primaryKey = 'id_payment_method_item';
	protected $fillable = [
		'id_payment_method',
		'id_coa',
		'keterangan',
		'id_option',
		'tipe_payment_method'
			/*
				1:Cash
				2:Bank
				3:Subsidi / CSR
				4:Subangan
				5:Piutang (Asuransi)
				6:Deposit
			*/
	];
}

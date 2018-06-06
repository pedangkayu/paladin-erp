<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_coa extends Model {

    protected $table 		= 'data_log_coa';
	protected $fillable 	= [
		'id_coa',
		// 'parent_id',
		// 'grup',
		// 'type',
		'kode',
		'nm_coa',
		// 'balance',
		'saldo_awal',
		// 'cash',
		// 'status',
		// 'keterangan',
		// 'coa_kategori',
		'id_karyawan'
	];
}

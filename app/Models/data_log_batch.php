<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_batch extends Model {

    protected $table = 'data_log_batch';
	protected $primaryKey = 'id_log_batch';
	protected $fillable = [
		'id_batch',
		'id_barang',
		'qty_in',
		'qty_out',
		'keterangan',
		'id_gudang',
		'tipe', /* 1:SKB|2:return Internal|3:return Eksternal */
		'id_parent',
		'id_karyawan'
	];
}

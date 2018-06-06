<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_faktur_pasien_item extends Model {
    
	protected $table 		= 'data_faktur_pasien_item';
	protected $primaryKey 	= 'id_faktur_pasien_item';
	protected $fillable 	= [
		'id_faktur_pasien',
		'id_barang',
		'id_bhp', /* id_resep_item */
		'harga',
		'id_satuan',
		'diskon',
		'subtotal',
		'qty',
		'id_resep_campur',
		'status'
	];


}

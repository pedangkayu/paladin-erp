<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_service_item extends Model
{
  
    protected $table 		= 'ref_service_item';
	protected $primaryKey 	= 'id_service_item';
	protected $fillable 	= [
    'id_service_item',
	'id_barang',
	'id_satuan',
	'id_service',
	'qty',
	'status', //1 obt aktf 2/obt non aktf
	'created_at',
	'updated_at',
];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_layanan extends Model
{
	 protected $table 		= 'ref_layanan';
	protected $primaryKey 	= 'id_layanan';
	protected $fillable 	= [
		    'id_layanan',
			'nm_layanan',
	];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_komponen_honor extends Model
{
     protected $table = 'ref_komponen_honor';
	protected $fillable = [
		'id_komponen_honor',
		'nm_komponen_honor',
		'status',
		'tipe',

	];
}

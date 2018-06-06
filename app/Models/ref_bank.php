<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_bank extends Model {

    protected $table = 'ref_bank';
	protected $primaryKey = 'id_bank';
	protected $fillable = [
		'nm_bank',
		'no_rekening',
		'cabang',
		'status',
		'id_coa'
	];
}

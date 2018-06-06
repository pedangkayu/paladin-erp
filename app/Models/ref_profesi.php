<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_profesi extends Model
{
    protected $table = 'ref_profesi';
    protected $primaryKey ='id_profesi';
	protected $fillable = [
		'nama_profesi',
		'status',
	];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_asuransi extends Model {
    
	protected $table = 'ref_asuransi';
	protected $primaryKey = 'id_asuransi';
	protected $fillable = [
		'nm_asuransi',
		'perusahaan',
		'alamat',
		'no_kontak',
		'status'
	];

}

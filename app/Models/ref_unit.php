<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_unit extends Model
{
	 protected $table = 'ref_unit';
	protected $fillable = [
    'id_layanan',
	'nama_layanan_rs',
	'id_bagian_organisasi',
	'kode_layanan_rs',
	'kategori_layanan',
	];

}

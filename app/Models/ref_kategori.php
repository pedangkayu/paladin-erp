<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_kategori extends Model{

    protected $table = 'ref_kategori';
	protected $primaryKey = 'id_kategori';
	protected $fillable = [
		'nm_kategori',
		'alias',
		'id_coa', // Akun Persediaan
		'coa_pembelian' // Akun HPP
	];
}

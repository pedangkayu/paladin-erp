<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_sph extends Model {

    protected $table 	= 'view_count_sph';
    protected $fillable = [
    	'tahun',
    	'jumlah',
    	'batal',
    	'proses',
    	'terpilih',
    	'hapus_manual'
    ];
}

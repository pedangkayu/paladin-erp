<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_rugi_laba_perbulan extends Model {

    protected $table 	= 'view_rugi_laba_perbulan';
    protected $fillable = [
    	'bulan',
    	'tahun',
    	'rugi_laba',
    	'rugi_laba_luar_usaha',
		'pajak'
    ];
}

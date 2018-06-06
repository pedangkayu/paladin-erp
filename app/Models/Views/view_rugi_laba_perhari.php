<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_rugi_laba_perhari extends Model {

	protected $table 	= 'view_rugi_laba_perhari';
    protected $fillable = [
    	'tanggal',
		'tahun',
		'rugi_laba',
		'rugi_laba_luar_usaha',
		'pajak'
    ];
    
    
}

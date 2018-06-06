<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_rugi_laba_tahunan extends Model
{
    protected $table 	= 'view_rugi_laba_tahunan';
    protected $fillable = [
    	'tahun',
    	'rugi_laba',
    	'rugi_laba_luar_usaha',
		'pajak'
    ];
}

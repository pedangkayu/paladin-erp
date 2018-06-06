<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_faktur_pembelian extends Model {
    
	protected $table 	= 'view_faktur_pembelian';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];

}

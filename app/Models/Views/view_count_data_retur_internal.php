<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_data_retur_internal extends Model {

	protected $table 	= 'view_count_data_retur_internal';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];
	
}

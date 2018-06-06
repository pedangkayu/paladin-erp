<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_sph_grup extends Model {

    protected $table 	= 'view_count_sph_grup';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];
}

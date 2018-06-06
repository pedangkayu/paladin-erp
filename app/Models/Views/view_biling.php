<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_biling extends Model {
    protected $table 	= 'view_biling';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];
}

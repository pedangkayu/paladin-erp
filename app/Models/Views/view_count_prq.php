<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_prq extends Model {

    protected $table 	= 'view_count_prq';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];
}

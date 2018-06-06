<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_gr extends Model {

    protected $table 	= 'view_count_gr';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];
}

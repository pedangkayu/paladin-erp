<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_roa_pertahun extends Model
{
    protected $table 	= 'view_roa_pertahun';
    protected $fillable = [
    	'tahun',
    	'roa'
    ];
}

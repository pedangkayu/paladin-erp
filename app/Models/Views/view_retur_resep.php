<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_retur_resep extends Model
{
      protected $table 	= 'view_retur_resep';
    protected $fillable = [
    	'tahun',
    	'jumlah'
    ];
}

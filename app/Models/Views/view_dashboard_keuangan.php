<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_dashboard_keuangan extends Model {
    
	protected $table    = 'view_dashboard_keuangan';
  	protected $fillable = [
  		'total_hutang',
  		'hutang_jth_tempo',
  		'total_piutang',
  		'total_piutang_jth_tempo'
  	];

}

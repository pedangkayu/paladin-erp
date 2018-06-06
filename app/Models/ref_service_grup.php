<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_service_grup extends Model
{
     protected $table = 'ref_service_grup';
	protected $primaryKey = 'id_grup';
	protected $fillable = [
				
				'id_grup',
				'unit',
				'grup',
				'created_at',
				'updated_at',
				];
}

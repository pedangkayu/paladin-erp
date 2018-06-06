<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ref_kelas extends Model
{
     protected $table = 'Ref_kelas';
	protected $primaryKey = 'id_kelas';
	protected $fillable = [
		
		'kelas',
		'harga' 

	];


}

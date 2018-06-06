<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_honor_item extends Model
{
    protected $table = 'data_log_honor_item';
    protected $primaryKey ='id_log_honor_item';
	protected $fillable = [
        'id_log_honor',
        'id_komponen_honor',
        'id_karyawan_honor',
        'nilai',

	];


}

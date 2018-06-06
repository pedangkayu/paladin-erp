<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_treatment_dokter extends Model
{
	  protected $table 		= 'data_treatment_dokter';
	protected $primaryKey 	= 'id_treatment_dokter';
	protected $fillable 	= [
				    'id_treatment_dokter',
					'id_treatment_item',
					'id_dokter',
					'tarif',
					'jabatan', //1=dokter dpjb 2=anggota

					];
}

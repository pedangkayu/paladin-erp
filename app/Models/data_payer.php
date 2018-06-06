<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_payer extends Model
{
    protected $table='data_payer';
    protected $primaryKey='id_payer';
    protected $fillable=[
					'kode',
					'nm_payer',
					'nm_last',
					'alamat',
					'telpon',
					'fax',
					'status', /* 1= aktif  2: tidak aktif*/
					'id_karyawan',
					'email',
    ];
}

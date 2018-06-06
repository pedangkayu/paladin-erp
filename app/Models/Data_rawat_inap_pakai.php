<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_rawat_inap_pakai extends Model
{
    protected $table 		= 'data_rawat_inap_pakai';
	protected $primaryKey 	= 'id_rinap_pakai';
	protected $fillable 	= [
	'id_rinap',
    'id_rinap_pakai',
	'id_rinap_pakai_hc',
	'tgl_pakai',
	'no_tagihan',
	'tgl_mulai_tagihan',
	'id_kamar',
	'id_antrian',
	'daftar_rinap',
	'selesai_rinap',
	'No_trans', ///sorry no_trans jdkn nilai 2 , 0 Masih cekin , 1sudh cekou tapi belum bayar, 2 sudah bayar
	'created_at',
	'updated_at',
	];
}

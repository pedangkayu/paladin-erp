<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelayanan_rawat_inap extends Model
{
	protected $table	='pelayanan_rawat_inap';
	protected $primaryKey ='id_palayanan_ri';	
	protected $fillable 	= [
			'id_antrian_rwt_inap',
			'tgl_pendaftaran_rwt_inap',
			'id_rs',
			'id_pgw',
			'id_status_jenis_kedatangan',
			'id_pasien_hc',
			'id_layanan_rs',
			'rencana_mrs',
			'rencana_krs',
			'keterangan_rwt_inap',
			'status_masuk_rwt_inap',
			'status_keluar_rwt_inap',
			'ket_mrs',
		

	];
}
//testing

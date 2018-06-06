<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_antrian extends Model
{
   protected $table = 'data_antrian';
    protected $primaryKey 	= 'id_jadwal';
	protected $fillable 	= [

		
		'id_jadwal',
		'id_jadwal_hc',
		'mas_id_pgw',
		'id_layanan_rs',
		'tanggal_jadwal',
		'no_antrian',
		'id_pasien_hc',
		'id_pgw',
		'id_status_jenis_kedatangan',
		'id_alasan_periksa',
		'jam_mulai_periksa',
		'jam_akhir_periksa',
		'tgl_pemeriksaan',
		'alasan_periksa',
		'kosong_antrian',
		'konfirmasi_antrian',
		'process_antrian',
		'onhold_antrian',
		'finish_antrian',
		'keterangan',
		'jam_datang',
		'jam_masuk',
		'promo',
		'note',
		'flag_imr',
		'created_at',
		'updated_at',

		];
}

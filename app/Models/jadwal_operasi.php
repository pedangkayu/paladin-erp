<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jadwal_operasi extends Model
{
	protected $table	='jadwal_operasi';
	protected $primaryKey ='id_jadwal_operasi';	
	protected $fillable 	= [

	'id_jadwal_operasi_hc',
	'tanggal_jadwal_operasi',
	'tanggal_operasi',
	'id_status_jenis_kedatangan',
	'id_layanan_rs',
	'id_master_ruang_operasi',
	'id_pasien',
	'jam_operasi',
	'ketrangan_jadwal_operasi',
	'surat_pembiusan_operasi',
	'konfirmasi_jadwal_operasi',
	'proses_jadwal_operasi',
	'on_hold_jadwal_operasi',
	'finish_jadwal_operasi',
	'diagnosa_jadwal_operasi',
	'status_jadwal',

	];

}

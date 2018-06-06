<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class periksa_penunjang_medis extends Model
{
    //testing
  protected $table 		= 'periksa_penunjang_medis';
	protected $primaryKey 	= 'id_pemeriksaan';
	protected $fillable 	= [
			'id_pemeriksaan',
           'id_pemeriksaan_pm_hc',
           'tgl_periksa_pm',
            'id_pasien_hc',
            'id_alasan_periksa',
           'id_status_jenis_kedatangan',
            'id_pgw',
            'id_layanan_rs',
           'perkiraan_selesai_pm',
            'tgl_selesai_pm',
            'proses_pm',
            'on_hold_pm',
            'finish_pm',
            'status_report_pm',
           'pembatalan_pm',
            'tidakhadir_pm',
            'no_antrian_pm',
            'keterangan_pm',
            'jam_pm',
            'id_slot_pm',
            'konfirmasi_pm',
            'jam_datang',
            'kiriman',
            'usg',
            'mm',
            'thx',
            'lp',
            'pt',
            'fna',
            'pa',
            'promo',
            'sms',
            'note',
            'baca',
            'jam_masuk',
	];
}

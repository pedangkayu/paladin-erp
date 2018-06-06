<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_antrian_non_poli extends Model
{
    protected $table = 'data_antrian_non_poli';
	protected $primaryKey = 'id_antrian_poli';
	protected $fillable = [
					'no_antrian',
					'tgl_nonpoli',
					'id_karyawan',
					'peg_idpgw',
					'id_alasan_periksa',
					'id_layanan_rs',
					'id_pasien',
					'id_status_jenis_kedatangan',
					'alasan_periksa',
					'proses',
					'on_hold',
					'finish',
					'status_report',
					'tidak_hadir',
					'pembatalan',
					'diagnosa',
					'masalah',
					'waktu_hc',
					'keterangan',
					'jam_np',
					'id_slot',
					'jam_konfirmasi',
					'jam_masuk',
					'flag_mr',

				];
}

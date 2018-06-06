<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_krs extends Model
{
   protected $table 	= 'view_krs';
    protected $fillable = [
    	'id_antrian',
		'id_status_jenis_kedatangan',
		'rencana_mrs',
		'status_masuk_rinap',
		'status_keluar_rinap',
		'ket_mrs',
		'tgl_pakai',
		'tgl_mulai_tagihan',
		'id_kamar',
		'daftar_rinap',
		'selesai_rinap',
		'id_rinap',
		'nm_kamar',
		'id_pasien',
		'nama_pasien',
		'noktp_pasien',
		'noasuransi_pasien',
		'jk_pasien',
		'tgllahir_pasien',
		'tempatlahir_pasien',

    ];


    public function scopeKrs($query, $req = []){
		$laporan = $query;
			if(!empty($req['waktu']) && $req['waktu'] == 1)
				$laporan->where(\DB::raw('MONTH(view_krs.selesai_rinap)'), $req['bulan'])
					->where(\DB::raw('YEAR(view_krs.selesai_rinap)'), $req['tahun']);
			else if(!empty($req['waktu']) && $req['waktu'] == 2)
				$laporan->whereBetween(\DB::raw('DATE(view_krs.selesai_rinap)'), [$req['dari'], $req['sampai']]);
				$laporan->select('view_krs.*'
					);
	}
	
}

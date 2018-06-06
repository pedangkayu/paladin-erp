<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_report_jasa_medis extends Model
{
    protected $table 	= 'view_report_jasa_medis';
    protected $fillable = [
    	'id_faktur',
    	'tgl_input',
    	'nm_gudang',
  		'tarif_dr',
  		'id_treatment_item',
  		'service_kode',
  		'nm_service',
  		'nm_depan',
  		'nm_belakang',
  		'id_pasien_hc',
  		'nama_pasien',
  		'jabatan',
  		'id_dokter'
    ];

	public function scopeMedis($query, $req = []){
		$rekap = $query;
					if(!empty($req['nm_depan']))
						$rekap->where('view_report_jasa_medis.nm_depan','LIKE', '%'. $req['nm_depan'] . '%');
					if(!empty($req['nm_belakang']))
						$rekap->where('view_report_jasa_medis.nm_belakang','LIKE', '%'.$req['nm_belakang'].'%');
					if(!empty($req['nama_pasien']))
						$rekap->where('view_report_jasa_medis.nama_pasien', 'LIKE', '%'. $req['nama_pasien']. '%');
					if(!empty($req['id_karyawan']))
						$rekap->where('view_report_jasa_medis.id_dokter',  $req['id_karyawan']);
					if(!empty($req['waktu']) && $req['waktu'] == 1)
						$rekap->where(\DB::raw('MONTH(view_report_jasa_medis.created_at)'), $req['bulan'])
							->where(\DB::raw('YEAR(view_report_jasa_medis.created_at)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$brekap->whereBetween(\DB::raw('DATE(view_report_jasa_medis.created_at)'), [$req['dari'], $req['sampai']]);
						$rekap->select('view_report_jasa_medis.*'
							);
	}
}

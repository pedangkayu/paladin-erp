<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_rawat_inap extends Model {

    protected $table 		= 'data_rawat_inap';
	protected $primaryKey 	= 'id_rinap';
	protected $fillable 	= [
		'id_rinap',
		'id_antrian',
		'daftar_rinap',
		'id_rs',
		'id_karyawan',
		'id_status_jenis_kedatangan',
		'id_pasien',
		'id_layanan_rs',
		'rencana_mrs',
		'rencana_krs',
		'keterangan',
		'status_masuk_rinap', ///1=masuk 
		'status_keluar_rinap',//1 kluar
		'ket_mrs',
		'created_at',
		'updated_at',

	];


	public function scopeLoadinvoice($query, $id_rinaps = []){
		return $query->leftjoin('data_rawat_inap_pakai', 'data_rawat_inap_pakai.id_rinap', '=', 'data_rawat_inap.id_rinap')
			->leftjoin('ref_kamar', 'ref_kamar.id_kamar', '=', 'data_rawat_inap_pakai.id_kamar')
			->whereIn('data_rawat_inap.id_rinap', $id_rinaps);
	}


	public function scopeAmbil($query, $id_rinaps = []){
		return $query->leftjoin('data_rawat_inap_pakai', 'data_rawat_inap_pakai.id_rinap', '=', 'data_rawat_inap.id_rinap')
			->leftjoin('ref_kamar', 'ref_kamar.id_kamar', '=', 'data_rawat_inap_pakai.id_kamar')
			->where('data_rawat_inap.id_rinap', $id_rinaps);
	}

	// --------Holil--------------------//
	public function scopeRawat($query){
		$rawat = $query->leftJoin('data_rawat_inap_pakai', 'data_rawat_inap_pakai.id_rinap', '=', 'data_rawat_inap.id_rinap')
						->leftjoin('ref_kamar', 'ref_kamar.id_kamar', '=', 'data_rawat_inap_pakai.id_kamar')
						->leftJoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_rawat_inap.id_pasien')
						->whereIn('data_rawat_inap.status_keluar_rinap',[0])
						->select('ref_kamar.nm_kamar', 'data_rawat_inap_pakai.tgl_pakai', 'data_rawat_inap_pakai.No_trans',
								'data_pasien.nama_pasien', 'data_pasien.alamat_pasien', 'data_pasien.tgllahir_pasien',
								'data_rawat_inap.*');
	}
	public function scopeRekaprinap($query, $req = []){
		$rinap = $query->Join('data_rawat_inap_pakai', 'data_rawat_inap_pakai.id_rinap', '=', 'data_rawat_inap.id_rinap')
						->join('ref_kamar', 'ref_kamar.id_kamar', '=', 'data_rawat_inap_pakai.id_kamar')
						->leftJoin('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_rawat_inap.id_pasien');
					if(!empty($req['id_kamar']))
					$rinap->where('ref_kamar.id_kamar', $req['id_kamar']);

				if(!empty($req['waktu']) && $req['waktu'] == 1)
					$rinap->where(\DB::raw('MONTH(data_rawat_inap.created_at)'), $req['bulan'])
						->where(\DB::raw('YEAR(data_rawat_inap.created_at)'), $req['tahun']);

				else if(!empty($req['waktu']) && $req['waktu'] == 2)
					$rinap->whereBetween(\DB::raw('DATE(data_rawat_inap.created_at)'), [$req['dari'], $req['sampai']]);

					$rinap->select('ref_kamar.nm_kamar', 'data_rawat_inap_pakai.tgl_pakai','data_rawat_inap_pakai.selesai_rinap', 'data_rawat_inap_pakai.No_trans',
							'data_pasien.nama_pasien', 'data_pasien.alamat_pasien', 'data_pasien.tgllahir_pasien',
							'data_rawat_inap.*');
	}

	
    public function scopeCari($query, $nama_pasien=0, $id_antrian=0, $id_pasien=0, $No_trans='', $req = []){
    	  $me = \Me::data()->id_karyawan;
	   $sub= \Me::subgudang()->id_gudang;
		    if($sub < 1):
		    	$gudang='';
		    else:
		    	$gud=\Me::subgudang()->id_gudang;
		    endif;
    	$rawat = $query->Join('data_rawat_inap_pakai', 'data_rawat_inap_pakai.id_rinap', '=', 'data_rawat_inap.id_rinap')
						->join('ref_kamar', 'ref_kamar.id_kamar', '=', 'data_rawat_inap_pakai.id_kamar')
						->Join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_rawat_inap.id_pasien');
						if(!empty($id_pasien))
							$rawat->where('data_rawat_inap.id_pasien', 'LIKE',$id_pasien);
						if(!empty($nama_pasien))
							$rawat->where('data_pasien.nama_pasien', $nama_pasien);
						if(!empty($id_antrian))
							$rawat->where('data_rawat_inap.id_antrian', 'LIKE', '%' .$id_antrian. '%');
						if(!empty($No_trans))
							$rawat->where('data_rawat_inap.status_keluar_rinap', $req['No_trans']);
						$rawat->select('ref_kamar.nm_kamar', 'data_rawat_inap_pakai.tgl_pakai', 'data_rawat_inap_pakai.No_trans',
								'data_pasien.nama_pasien', 'data_pasien.alamat_pasien', 'data_pasien.tgllahir_pasien',
								'data_rawat_inap.*');
    }

}
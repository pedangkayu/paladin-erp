<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_faktur_pasien extends Model {
    
	protected $table 		= 'data_faktur_pasien';
	protected $primaryKey 	= 'id_faktur_pasien';
	protected $fillable 	= [
		'id_faktur',
		'id_payment_method',
		'id_resep',
		'qty',
		'id_satuan',
		'id_resep_item',
		'id_item',
		'diskon',
		'harga_jual',
		'subtotal',
		'id_treatment',
		'id_treatment_item',
		'tarif_dasar',
		'tarif_dr',
		'tarif_dr_real',
		'persen_dr',
		'persen_dr_real',
		'tarif_rs',
		'persen_rs',
		'status',
		'id_rinap',
		'id_kamar',
		'tarif_kamar',
		'tarif_dasar_rinap',
		'check_in',
		'check_out',
		'total_sewa',
		'diskon_rinap'
	];


	public function scopeResep($query, $id){
		return $query->join('data_resep', 'data_resep.id_resep', '=', 'data_faktur_pasien.id_resep')
			// ->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan') mohon maaf saya rubh atas permntaan dr heryy
			->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan') 
			->where('id_faktur', $id)
			->where('data_faktur_pasien.id_resep' , '>', 0)
			->select('data_resep.*', 'data_karyawan.nm_depan', 'data_karyawan.nm_belakang')
			->groupby('data_faktur_pasien.id_resep');
	}

	public function scopeTreatment($query, $id){
		return $query->join('data_treatment', 'data_treatment.id_treatment', '=', 'data_faktur_pasien.id_treatment')
			->join('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_treatment.id_unit')
			->where('id_faktur', $id)
			->where('data_faktur_pasien.id_treatment' , '>', 0)
			->select('data_treatment.*', 'ref_gudang.nm_gudang')
			->groupby('data_faktur_pasien.id_treatment');
	}

	public function scopeItemresep($query, $id_resep){
		return $query->join('data_resep', 'data_resep.id_resep', '=', 'data_faktur_pasien.id_resep')
			->leftJoin('data_barang', 'data_barang.id_barang', '=','data_faktur_pasien.id_item')
			->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_faktur_pasien.id_satuan')
			->leftJoin('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
			->where('data_faktur_pasien.id_resep', $id_resep)
			->select(
				'data_faktur_pasien.id_item AS id_barang',
				'data_barang.nm_barang',
				'data_barang.harga_beli',
				'data_faktur_pasien.id_resep_item',
				'data_faktur_pasien.id_faktur_pasien',
				'data_faktur_pasien.qty',
				'ref_satuan.nm_satuan',
				'data_faktur_pasien.harga_jual',
				'data_faktur_pasien.diskon',
				'data_faktur_pasien.subtotal',
				'ref_kategori.coa_pembelian as id_coa'
			);
	}

	public function scopeItemtreatments($query, $id_treatment){
		// return $query->join('data_treatment_item', 'data_treatment_item.id_treatment_item', '=', 'data_faktur_pasien.id_treatment_item')
		// 	->join('ref_service', 'ref_service.id_service', '=', 'data_treatment_item.id_service')
		// 	->join('ref_service_grup', 'ref_service_grup.id_grup', '=','ref_service.id_grup')
		// 	//->leftJoin('ref_service_tindakan', 'ref_service_tindakan.id_tindakan', '=','ref_service.parend_id')
		// 	->join('ref_service_kode', 'ref_service_kode.service_kode', '=','ref_service.service_kode')
		// 	->where('data_faktur_pasien.id_treatment', $id_treatment)maaf saya rubah
		return $query->join('data_treatment_item', 'data_treatment_item.id_treatment_item', '=', 'data_faktur_pasien.id_treatment_item')
			->join('ref_service_kode', 'ref_service_kode.service_kode', '=','data_treatment_item.service_kode')
			->where('data_faktur_pasien.id_treatment', $id_treatment)
			->where('data_treatment_item.id_treatment', $id_treatment)
			->select(
				// 'ref_service_grup.grup', 
				'ref_service_kode.nm_service', 
				'ref_service_kode.nm_service AS tindakan', 
				'data_faktur_pasien.id_faktur_pasien',
				'data_faktur_pasien.tarif_dasar',
				'data_faktur_pasien.persen_rs', 
				'data_faktur_pasien.persen_dr',
				//'ref_service_tindakan.tindakan', 
				'data_faktur_pasien.tarif_dr',
				'data_faktur_pasien.tarif_rs',
				'data_faktur_pasien.diskon',
				'data_faktur_pasien.subtotal',
				'data_treatment_item.id_treatment_item',
				'data_treatment_item.status',
				'data_treatment_item.tipe',

				'ref_service_kode.coa', 
				'ref_service_kode.coa_rs',
				'ref_service_kode.coa_dr'
			);
	}


	public function scopeRinaps($query, $id){
		$query->join('ref_kamar', 'ref_kamar.id_kamar', '=', 'data_faktur_pasien.id_kamar')
			->join('data_rawat_inap', 'data_rawat_inap.id_rinap', '=', 'data_faktur_pasien.id_rinap')
			->where('data_faktur_pasien.id_faktur', $id)
			->select(
				'ref_kamar.nm_kamar',
				'ref_kamar.kode_kamar',
				'data_faktur_pasien.check_in',
				'data_faktur_pasien.check_out',
				'data_faktur_pasien.tarif_kamar',
				'data_faktur_pasien.tarif_dasar_rinap',
				'data_faktur_pasien.total_sewa',
				'data_faktur_pasien.diskon_rinap'
			);
	}

	public function campur_bhp(){
		return $this->hasMany('App\Models\data_faktur_pasien_item', 'id_faktur_pasien');
	}

}

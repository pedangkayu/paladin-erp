<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_batch extends Model {
    protected $table = 'data_batch';
	protected $primaryKey = 'id_batch';
	protected $fillable = [
		'nomor_batch',
		'id_barang',
		'id_spbm',
		'id_spbm_item',
		'total_qty',
		'in',
		'out',
		'tgl_expired',
		'status',
		'titipan',
		'id_karyawan'
	];

	public function scopeByidspbmitem($query, $id){
		return $query->where('status', 1)
			->where('id_spbm_item', $id);
	}

	public function scopeShow($query, $id_batch){
		return $query->join('data_spbm_item', 'data_spbm_item.id_spbm_item', '=', 'data_batch.id_spbm_item')
			->join('data_spbm', 'data_spbm.id_spbm', '=', 'data_batch.id_spbm')
			->join('data_barang', 'data_barang.id_barang', '=', 'data_batch.id_barang')
			->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_batch.id_karyawan')
			->join('data_po', 'data_po.id_po', '=', 'data_spbm.id_po')
			->where('data_batch.id_batch', $id_batch)
			->where('data_batch.status', 1)
			->select(
				'data_barang.kode',
				'data_barang.nm_barang',

				
				'data_spbm_item.qty AS qty_item',
				'data_spbm.no_spbm',
				'data_spbm.tgl_terima_barang',
				'data_spbm.nm_pengirim',
				'data_po.no_po',

				'data_karyawan.nm_depan',
				'data_karyawan.nm_belakang',

				'data_batch.nomor_batch',
				'data_batch.tgl_expired',
				'data_batch.total_qty',
				'data_batch.in',
				'data_batch.out',
				'data_batch.titipan'
			);
	}

	public function scopeTransaksi($query, $id_barang){
		return $query
			->where('id_barang', $id_barang)
			->where('status', 1)
			->orderby('tgl_expired', 'asc');
	}

}

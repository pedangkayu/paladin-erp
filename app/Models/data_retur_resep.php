<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_retur_resep extends Model
{
    protected $table='data_retur_resep';
    protected $primaryKey='id_retur_resep';
    protected $fillable=[
			'id_resep',
			'no_retur_resep',
			'id_gudang',
			'tanggal_retur',
			'id_karyawan', //karywan yang menerima pengajuan retur
			'id_acc_retur',
			'alasan',
			'id_pembeli', // customer/pasien
			'status', //1 mengajukan retur = sudh d acc pengajuan retur
			];


	public function Scoperetur($query, $req = []){
		$item = $query->join('data_resep', 'data_resep.id_resep', '=', 'data_retur_resep.id_resep')
						->join('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_retur_resep.id_gudang')
						->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_retur_resep.id_karyawan')
						->join('data_pasien', 'data_pasien.id_pasien', '=', 'data_retur_resep.id_pembeli');
					if(!empty($req['no_resep']))
						$item->where('data_resep.nomor_resep', $req['no_resep']);
					if(!empty($req['id_pasien_hc']))
						$item->where('data_pasien.id_pasien_hc', $req['id_pasien_hc']);
					if(!empty($req['no_retur_resep']))
						$item->where('data_retur_resep.no_retur_resep', $req['no_retur_resep']);
					if(!empty($req['tanggal_retur']))
						$item->where('data_retur_resep.tanggal_retur', $req['tanggal_retur'])
						->select('data_retur_resep.*','data_pasien.nama_pasien', 'data_pasien.id_pasien_hc',
								'data_resep.nomor_resep','data_karyawan.nm_depan', 'data_karyawan.nm_belakang');
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_resep_item extends Model
{
    protected $table = 'data_resep_item';
	protected $primaryKey = 'id_resep_item';
	protected $fillable = [
		'id_resep',
		'id_barang', // <-- untuk id_barang di sini di abaikan saja fokus kan ke id_item_gudang
		'id_item_gudang', //<-- tambahkan di sini id_item gudangnnya, untukj menambahkan id_item_gudang perlu di include pada saat membuat resep
		'harga_jual',
		'id_satuan',
		'qty',
		'id_resep_aturan',
		'status_item_resep', //1= tampil 2=hapus//
		'status_obat', // 1=obat paten , 2=obat campur/ 3=treatment
		'nama_campur',
		'total',
		'id_treatment',
		'coa_pendapatan',
		'dihapus_pada',
		'flat', // 0=default 1=flat
		'tipe', //1 bhp paket/ 2= bhp tambahan
		'id_treatment_item',
		'id_klasifikasi',
		'reuse', //1 digunakan lagi = 2=barang tidak digunakan lagi
		'status', //berisi data status BHP yang tidak di ambil jika nila 0 berrt di ambil jika nilai 1 berrt tidak di ambil
	    'keterangan',
	    'status_retur', // 0= tdak ada retur /=1 ada retur sebagian =2 retur semua
	    'qty_retur', //jumlsah item yng d retur
	    'qty_awal' 
	];


	public function scopeByid($query, $id){
		$me = \Me::subgudang();

		$query->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
			 ->leftJoin('data_item_gudang', 'data_item_gudang.id_item_gudang', '=' ,'data_resep_item.id_item_gudang') //<-- untuk join² gudankan id_item_gudang. Perlu di ingat id_item_gudang tidak sama dengan id_barang
				->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
				->leftJoin('data_resep', 'data_resep.id_resep', '=' , 'data_resep_item.id_resep')
				->leftJoin('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan')
				 ->whereNotIn('data_resep_item.status', [0])
				->where('data_resep_item.id_resep', $id)
				->select(
					'data_resep_item.*',
					'data_barang.kode',
					'data_barang.nm_barang',
					'data_barang.in As m',
					'data_barang.out As k',
					'ref_resep_aturan.resep_aturan',
					'data_item_gudang.out As keluar',
					'data_item_gudang.in As masuk',
					'ref_satuan.nm_satuan'
					// Pengambilan stok nya ngambil ke filed in & out nya data_item_gudang
					)
				->orderby('id_barang', 'dsc');
				
	}
  public function scopeByacc($query, $id){
		$me = \Me::subgudang();

		$query->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
			->leftJoin('data_item_gudang', 'data_item_gudang.id_item_gudang', '=' ,'data_resep_item.id_item_gudang') //<-- untuk join² gudankan id_item_gudang. Perlu di ingat id_item_gudang tidak sama dengan id_barang
				->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
				->leftJoin('data_resep', 'data_resep.id_resep', '=' , 'data_resep_item.id_resep')
				->leftJoin('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan')
				// ->where('data_resep.status', 0)
				->where('data_resep_item.id_resep', $id)
				->where('data_resep_item.status_item_resep',1)

				->select(
					'data_resep_item.*',
					'data_barang.kode',
					'data_barang.nm_barang',
					'data_barang.in As m',
					'data_barang.out As k',
					'ref_resep_aturan.resep_aturan',
					'data_item_gudang.out As keluar',
					'data_item_gudang.in As masuk',
					'ref_satuan.nm_satuan'
					// Pengambilan stok nya ngambil ke filed in & out nya data_item_gudang
					)
				->orderby('id_barang', 'dsc');
	}
	public function scopeBahan($query, $id)
		  {
		    $query->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
		    	->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
		    	->where('data_resep_item.id_treatment_item', $id)
		        ->select(
		          	'data_resep_item.*',
		            'data_barang.nm_barang',
		            'data_barang.kode',
		            'data_barang.harga_jual',
		            'ref_satuan.nm_satuan'   
		          );
		  }
	public function scopeEtiket($query, $id){
		$query->join('data_resep', 'data_resep.id_resep', '=', 'data_resep_item.id_resep')
				->join('data_pasien', 'data_pasien.id_pasien', '=', 'data_resep.id_pasien')
				->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
		    	->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
		    	->join('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan')
		    	->where('data_resep_item.id_resep_item', $id)
		        ->select(
		          	'data_resep_item.*',
		            'data_barang.nm_barang',
		            'data_barang.kode',
		            'data_barang.harga_jual',
		            'ref_satuan.nm_satuan',
		            'data_pasien.nama_pasien',
		            'data_pasien.alamat_pasien',
		            'data_pasien.tgllahir_pasien',
		            'data_resep.nomor_resep',
		            'ref_resep_aturan.resep_aturan'
		         
		          );
	}
	public function scopeRekapracikan($query, $id){
		 $item=$query->join('data_resep', 'data_resep.id_resep', '=', 'data_resep_item.id_resep')
			->where('data_resep_item.status_obat', 2);
		$item->select(
			'data_resep_item.*',
			'data_resep.nomor_resep'
		
			);
	}


	  public function scopeRekapresep($query, $req = []){
		$item = $query->join('data_resep', 'data_resep.id_resep', '=', 'data_resep_item.id_resep')
					->join('data_pasien', 'data_pasien.id_pasien', '=', 'data_resep.id_pasien')
					->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan')
					->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
					->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan');
		if(!empty($req['nama_pasien']))
			$item->where('data_pasien.nama_pasien', $req['nama_pasien']);

		if(!empty($req['waktu']) && $req['waktu'] == 1)
			$item->where(\DB::raw('MONTH(data_resep.created_at)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_resep.created_at)'), $req['tahun']);
		else if(!empty($req['waktu']) && $req['waktu'] == 2)
			$item->whereBetween(\DB::raw('DATE(data_resep.created_at)'), [$req['dari'], $req['sampai']]);

		$item->select(
			'data_resep.*',
			'data_pasien.nama_pasien',
			'data_pasien.id_pasien_hc',
			'data_karyawan.nm_depan',
			'data_karyawan.nm_belakang',
			'data_barang.nm_barang',
			'ref_satuan.nm_satuan'
			);
	}
	public function scopeRekap($query, $req = []){
		$item = $query->join('data_resep', 'data_resep.id_resep', '=', 'data_resep_item.id_resep')
		->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
		->join('data_pasien', 'data_pasien.id_pasien', '=', 'data_resep.id_pasien')
		->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
		->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_resep.id_karyawan');

		if(!empty($req['nama_pasien']))
			$item->where('data_pasien.nama_pasien', $req['nama_pasien']);
		if(!empty($req['waktu']) && $req['waktu'] == 1)
			$item->where(\DB::raw('MONTH(data_resep_item.created_at)'), $req['bulan'])
				->where(\DB::raw('YEAR(data_resep_item.created_at)'), $req['tahun']);
		else if(!empty($req['waktu']) && $req['waktu'] == 2)
			$item->whereBetween(\DB::raw('DATE(data_resep_item.created_at)'), [$req['dari'], $req['sampai']]);

		
			$item->orderby('data_resep_item.id_barang', 'desc');
	}
  public function campur(){
      return $this->hasMany('App\Models\data_resep_campur', 'id_resep_item');
 	 }

}
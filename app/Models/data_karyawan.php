<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_karyawan extends Model{

	protected $table = 'data_karyawan';
	protected $primaryKey = 'id_karyawan';
	protected $fillable = [
		'nm_depan',
		'nm_belakang',
		'telp',
		'email',
		'sex',
		'hp',
		'tempat_lahir',
		'tgl_lahir',
		'jabatan',
		'id_profesi',
		'alamat',
		'agama',
		'pendidikan',
		'id_status',
		'NIK',
		'foto',
		'tgl_bergabung',
		'id_departemen',
	];

	public function scopeDetails($query){
		return $query->join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
		->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
		->where('tipe_status', 1);
	}
	public function scopeKaryawan($query,$id){
		return $query->join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
		->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
		->join('data_departemen','data_departemen.id_departemen', '=', 'data_karyawan.id_departemen')
		->where('data_karyawan.id_karyawan',$id);
	}
	public function scopeKaryawanku($query, $req=[]){
		$prode_bulan = date("m");// tanggal sekarang
		$prode_tahun = date("Y");// tanggal sekarang
		$data =$query->join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
			->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
			->join('data_departemen','data_departemen.id_departemen', '=', 'data_karyawan.id_departemen')
			->join('ref_profesi', 'ref_profesi.id_profesi','=','data_karyawan.id_profesi')
			->leftjoin('data_log_honor','data_log_honor.id_karyawan', '=','data_karyawan.id_karyawan')
			// ->where(\DB::raw('MONTH(data_log_honor.periode)'),$prode_bulan)
	        // ->where(\DB::raw('YEAR(data_log_honor.periode)'),$prode_tahun)
		->where('tipe_status', 1);
		if(!empty($req['id_karyawan']))
				$data->where('data_karyawan.id_karyawan', $req['id_karyawan']);
		if(!empty($req['id_departemen']))
				$data->where('data_karyawan.id_departemen', $req['id_departemen']);
		$data->select(
			'data_karyawan.*',
			'ref_jabatan.nm_jabatan',
			'data_departemen.nm_departemen',
			'ref_profesi.nama_profesi',
			'data_log_honor.status_pembayaran'
		  );
		# code...
	}

}

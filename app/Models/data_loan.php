<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_loan extends Model
{
    protected $table 		= 'data_loan';
	protected $primaryKey 	= 'id_loan';
	protected $fillable 		= [
						'id_karyawan',
						'no_pinjaman',
						'nominal',
						'id_user',
						'start_time',
						'end_time',
						'tgl_approval',
						'id_acc',
						'catatan',
						'total_terbayar',
						'tipe_pinjaman', //1 pinjaman uang
						'status', //1 baru /2 di setujui /3 lunas
						'tanggal',
						'keterangan',
					];
	public function scopePinjaman($query, $req = []){
		// dd($req['status']);
		$pinjaman=$query->leftjoin('data_karyawan as p', 'p.id_karyawan', '=','data_loan.id_karyawan')
						->leftjoin('data_karyawan as user', 'user.id_karyawan', '=', 'data_loan.id_user')
						->leftjoin('data_karyawan as acc', 'acc.id_karyawan', '=', 'data_loan.id_acc')

						->whereIn('data_loan.tipe_pinjaman',[1]);
						$pinjaman->select(
							'data_loan.*', 
							'p.nm_depan as nd',
							'p.nm_belakang AS nb',
							'p.telp',
							'p.alamat', 
							'user.nm_depan as user_a', 
							'user.nm_belakang as user_b',
							'acc.nm_depan as depan_approval',
							'acc.nm_belakang as belakang_approval'
						);

					if(!empty($req['no_pinjaman']))
						$pinjaman->where('no_pinjaman', $req['no_pinjaman']);
					if(!empty($req['nm_depan']))
						$pinjaman->where('data_karyawa.nm_depan', $req['nm_depan']);
					if(isset($req['status']) && $req['status'] != '')
						$pinjaman->where('data_loan.status', $req['status']);
					else
						$pinjaman->whereIn('data_loan.status', [1,2,'']);
						

		# code...
	}
	public function scopeId($query,$id){
		$pinjaman=$query->leftjoin('data_karyawan as p', 'p.id_karyawan', '=','data_loan.id_karyawan')
						->leftjoin('data_karyawan as user', 'user.id_karyawan', '=', 'data_loan.id_user')
						->join('data_karyawan as acc', 'acc.id_karyawan', '=', 'data_loan.id_acc')
						->whereIn('data_loan.tipe_pinjaman',[1])
						->where('data_loan.id_loan',$id);
						$pinjaman->select(
							'data_loan.*', 
							'p.nm_depan as nd',
							'p.telp',
							'p.alamat',
							'acc.nm_depan as acc_depan',
							'acc.nm_belakang as acc_belakang',
							'p.nm_belakang AS nb', 
							'user.nm_depan as user_a', 
							'user.nm_belakang as user_b'
						);
						

		# code...
	}
}

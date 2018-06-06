<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_shift_kasir extends Model{
  protected $table 		= 'data_shift_kasir';
  protected $primaryKey 	= 'id_shift_kasir';
  protected $fillable 	= [
    'shift',
    'saldo_awal',
    'saldo_kembali',
    'pendapatan_kassa',
    'status',
    'id_karyawan'
  ];

  public function scopeItems($query){
    return $query->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_shift_kasir.id_karyawan')
      ->orderby('data_shift_kasir.id_shift_kasir', 'desc')
      ->select('data_shift_kasir.*', 'data_karyawan.nm_depan', 'data_karyawan.nm_belakang');
  }

  public function scopeUser($query, $id){
    return $query->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_shift_kasir.id_karyawan')
      ->where('data_shift_kasir.id_shift_kasir', $id)
      ->select('data_shift_kasir.*', 'data_karyawan.nm_depan', 'data_karyawan.nm_belakang');
  }

  public function scopeBytangal($query, $req = []){
    $tanggal = empty($req['tanggal']) ? date('Y-m-d') : $req['tanggal'];

    $item = $query->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_shift_kasir.id_karyawan');
    $item->where(\DB::raw('DATE(data_shift_kasir.created_at)'), $tanggal);
    if(!empty($req['shift'])){
      $item->where('data_shift_kasir.shift', $req['shift']);
     }
    $item->select('data_shift_kasir.*', 'data_karyawan.nm_depan', 'data_karyawan.nm_belakang');
  }

}

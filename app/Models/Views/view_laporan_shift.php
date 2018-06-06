<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_laporan_shift extends Model {

  protected $table 	= 'view_laporan_shift';
  protected $fillable = [
    'keterangan',
    'nomor_faktur',
    'nama_pasien',
    'tgl_kwitansi',
    'nm_depan',
    'nm_belakang',
    'jumlah',
    'id_karyawan'
  ];

  public function scopeSrc($query, $req = []){
    $items = $query;
    $tanggal = !empty($req['tanggal']) ? $req['tanggal'] : date('Y-m-d');

    if(!empty($req['tanggal']))
      $items->where(\DB::raw('DATE(tgl_kwitansi)'), $tanggal);
    else
      $items->where(\DB::raw('DATE(tgl_kwitansi)'), date('Y-m-d'));

      if(!empty($req['dari']) && !empty($req['sampai']))
        $items->whereBetween(\DB::raw('tgl_kwitansi'), [$tanggal . ' ' . $req['dari'] . ':00', $tanggal . ' ' . $req['sampai'] . ':59']);
      else
        $items->whereBetween(\DB::raw('tgl_kwitansi'), [$tanggal . ' ' .' 00:00:00', $tanggal . ' ' . ' 23:59:59']);
  }

}

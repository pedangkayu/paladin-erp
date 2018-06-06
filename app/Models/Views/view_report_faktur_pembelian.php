<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_report_faktur_pembelian extends Model
{
    protected $table 	= 'view_report_faktur_pembelian';
    protected $fillable = [
        'nomor_faktur',
        'id_faktur',
        'type',
        'tgl_faktur',
        'id_po',
        'ppn',
        'diskon',
        'adjustment',
        'subtotal',
        'total',
        'amount_due',
        'status_bayar',
        'tgl_tukar_faktur'
    ];
    public function scopePembelian($query, $req = []){
		$laporan = $query;
					if(!empty($req['waktu']) && $req['waktu'] == 1)
						$laporan->where(\DB::raw('MONTH(view_report_faktur_pembelian.tgl_faktur)'), $req['bulan'])
							->where(\DB::raw('YEAR(view_report_faktur_pembelian.tgl_faktur)'), $req['tahun']);
					else if(!empty($req['waktu']) && $req['waktu'] == 2)
						$laporan->whereBetween(\DB::raw('DATE(view_report_faktur_pembelian.tgl_faktur)'), [$req['dari'], $req['sampai']]);
						$laporan->select('view_report_faktur_pembelian.*'
							);
	}
}

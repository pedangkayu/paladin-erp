<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_stockout extends Model
{
    protected $table='data_log_stockout';
    protected $primaryKey='id_log_stockout';
    protected $fillable=[
			'id_barang',
			'id_gudang',
			'id_item_gudang',
			'id_treatment_item',
			'id_resep_item',
			'req_qty',
			'stok',
			'hutang',
			'id_karyawan',
			'status', ///1=masih hutang stok 2= tidak punya hutang stok =3 refound 

    	];
    	public function scopeCek($query)
    	{
    		$gudang= \Me::subgudang()->id_gudang;
    			$query->join('data_resep_item','data_resep_item.id_resep_item', '=','data_log_stockout.id_resep_item')
                            ->join('data_treatment_item', 'data_treatment_item.id_treatment_item', '=', 'data_resep_item.id_treatment_item')
                            ->join('data_treatment', 'data_treatment.id_treatment', '=', 'data_treatment_item.id_treatment')
                            ->where('data_log_stockout.id_gudang',$gudang)
                            ->whereIn('data_log_stockout.status',[1])
                            ->select('data_log_stockout.*','data_treatment.nomor_treatment')->groupby('data_treatment_item.id_treatment_item');
    	}
}

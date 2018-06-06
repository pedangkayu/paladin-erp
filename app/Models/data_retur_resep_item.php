<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_retur_resep_item extends Model
{
    protected $table='data_retur_resep_item';
    protected $primaryKey='id_retur_resep_item';
    protected $fillable=[
    		'id_retur_resep',
			'id_resep_item',
			'id_barang',
			'id_satuan',
			'qty_awal',
			'qty_akhir',
			'id_item_gudang',
			'status',
			'qty_retur',
		];
}

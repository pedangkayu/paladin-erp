<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_transfer extends Model
{
    protected $table 		= 'data_transfer';
	protected $primaryKey 	= 'id_transfer';
	protected $fillable 	= [
		'id_transfer',
		'id_layanan',
		'no_antrian',
		'tabel_antrian',
		'id_gudang_jasa', //id_gudang jasa
		'id_gudang_item', //id_gudang obat yang d photong

	];

	public function scopeUser($query,  $id_gudang_jasa=0,$id_gudang_item=0, $req=[])
	{
		 $tre = $query->leftjoin('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_transfer.id_gudang_item')
                            ->leftjoin('ref_gudang as gudang_jasa', 'gudang_jasa.id_gudang','=', 'data_transfer.id_gudang_jasa')
                            ->leftjoin('ref_layanan', 'ref_layanan.id_layanan', '=', 'data_transfer.id_layanan');
                         if(!empty($req['id_gudang_jasa']))
					      	$tre->where('data_transfer.id_gudang_jasa', $req['id_gudang_jasa']);
					     if(!empty($req['id_gudang_item']))
							$tre->where('data_transfer.id_gudang_item', $req['id_gudang_item']);
           					 $tre->select('data_transfer.*', 'ref_gudang.nm_gudang', 'ref_layanan.nm_layanan','gudang_jasa.nm_gudang as jasa');
	}


}

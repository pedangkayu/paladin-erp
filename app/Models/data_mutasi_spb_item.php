<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_mutasi_spb_item extends Model
{

    protected $table= 'data_mutasi_spb_item';
    protected $primaryKey= 'id_mutasi_spb_item';
    protected $fillable = [
    			'id_mutasi_spb',
				'id_item',
				'qty_awal', //qty yng di mnt
				'qty',	//qty yng d penuhi
				// 'qty_lg', //qty  yng d minta 
				// 'keterangan',
				'status', //1:proses|2:selesai 
				'id_satuan',
				'id_unit',//unit yng minta
				// 'id_gudang',

    ];



    public function scopeByitem($query, $id){
    		$query->join('data_barang','data_barang.id_barang', '=', 'data_mutasi_spb_item.id_item')
                ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_mutasi_spb_item.id_satuan')
                ->where('data_mutasi_spb_item.id_mutasi_spb', $id)
                ->where('data_mutasi_spb_item.status',1)
                ->select('data_mutasi_spb_item.*', 'data_barang.nm_barang', 
                    'data_barang.kode', 'ref_satuan.nm_satuan');
    }
    public function scopeByunit($query, $id){
        $gudang= \Me::subgudang()->id_gudang;
       $query->leftjoin('data_barang', 'data_barang.id_barang', '=', 'data_mutasi_spb_item.id_item')
            ->leftjoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_mutasi_spb_item.id_satuan')
            ->leftJoin('data_item_gudang', 'data_item_gudang.id_barang', '=', 'data_barang.id_barang')
            ->where('data_mutasi_spb_item.id_mutasi_spb', $id)
            ->where('data_item_gudang.id_gudang',$gudang)
            ->whereIN('data_mutasi_spb_item.status', [1])
            ->select(
                'data_barang.kode', 
                'data_barang.nm_barang',
               
                'ref_satuan.nm_satuan', 
                'data_mutasi_spb_item.*',
                'data_barang.id_satuan AS id_satuan_barang',
                 'data_item_gudang.in',
                'data_item_gudang.out',
                'data_item_gudang.id_item_gudang'

            );
    }
}

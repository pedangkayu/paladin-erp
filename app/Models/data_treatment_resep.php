<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_treatment_resep extends Model
{
  protected $table 		= 'data_treatment_resep';
  protected $primaryKey 	= 'id_treatment_resep';
  protected $fillable 	= [
    'id_treatment',
    'id_treatment_item',
    'id_barang',
    'qty',

  ];
  public function scopeBahan($query, $id)
  {
    $query->join('data_barang', 'data_barang.id_barang', '=', 'data_treatment_resep.id_barang')
    ->where('data_treatment_resep.id_treatment', $id)
        ->select(
          'data_treatment_resep.*',
            'data_barang.nm_barang',
            'data_barang.kode',
            'data_barang.harga_jual'
          );
  }
}

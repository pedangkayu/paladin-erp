<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_skb extends Model {

    protected $table 	= 'view_count_skb';
    protected $fillable = [
    	'tahun',
    	'jumlah',
    	'hapus',
    	'selesai',
    	'jumlah_obat',
    	'jumlah_barang'
    ];
}

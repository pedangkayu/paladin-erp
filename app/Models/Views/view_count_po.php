<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_po extends Model {

    protected $table 	= 'view_count_po';
    protected $fillable = [
    	'tahun',
    	'jumlah',
    	'baru',
    	'proses',
    	'selesai',
    	'hapus',
    	'hapus_manual'
    ];
}

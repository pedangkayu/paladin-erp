<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_smb extends Model
{
     protected $table 	= 'view_count_smb';
    protected $fillable = [
    	'tahun',
    	'jumlah',
    	'hapus',
    	'selesai',
    	'jumlah_obat',
    	'jumlah_barang'
    ];
}

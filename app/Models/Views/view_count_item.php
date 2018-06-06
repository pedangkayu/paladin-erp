<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_item extends Model {

    protected $table 	= 'view_count_item';
    protected $fillable = [
		'tahun',
		'jenis_permintaan',
		'jumlah',
		'baru', /* 1 */
		'proses', /* 2 */
		'selesai', /* 3 */
		'hapus', /* 4 */
		'selesai_manual' /* 5 */
	];
}

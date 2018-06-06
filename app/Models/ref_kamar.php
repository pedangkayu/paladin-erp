<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_kamar extends Model
{
    protected $table = 'ref_kamar';
	protected $primaryKey = 'id_id_kamar';
	protected $fillable = [
					'id_kamar',
					'nm_kamar',
					'kode_kamar',
					'tarif',
					'id_kelas',
					'fasilitas',
					'status',
					];

}

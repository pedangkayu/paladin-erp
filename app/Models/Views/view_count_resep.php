<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_resep extends Model {

    protected $table    = 'view_count_resep';
    protected $fillable = [
        'tahun',
        'jumlah'
    ];
}

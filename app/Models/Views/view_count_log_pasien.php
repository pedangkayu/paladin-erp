<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_log_pasien extends Model
{
  protected $table    = 'view_count_log_pasien';
  protected $fillable = [
      'tahun',
      'jumlah'
  ];
}

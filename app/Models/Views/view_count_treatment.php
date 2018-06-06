<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class view_count_treatment extends Model
{
  protected $table    = 'view_count_treatment';
  protected $fillable = [
      'tahun',
      'jumlah'
  ];
}

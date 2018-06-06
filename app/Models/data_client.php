<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_client extends Model
{
    protected $table 		= 'data_client';
    protected $primarykey  ='id_client';
    protected $fillable 	= [
                        'nm_client',
                        'pemilik',
                        'alamat',
                        'telpon',
                        'email',
                        'website',
                        'no_npwp',
                    ];
}

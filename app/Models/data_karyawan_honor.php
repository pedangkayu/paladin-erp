<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_karyawan_honor extends Model
{
    protected $table = 'data_karyawan_honor';
    protected $primaryKey ='id_karyawan_honor';
	protected $fillable = [
		'id_karyawan',
		'id_komponen_honor',
		'nilai',
		'waktu_honor',
	];

    public function scopeDetail($query){
        return $query->join('data_karyawan','data_karyawan.id_karyawan', '=', 'data_karyawan_honor.id_karyawan')
                    ->join('ref_komponen_honor', 'ref_komponen_honor.id_komponen_honor', '=', 'data_karyawan_honor.id_komponen_honor');

        # code...
    }
    public function scopeKaryawan($query , $req=[])
    {
        $data =  $query->join('data_karyawan','data_karyawan.id_karyawan', '=', 'data_karyawan_honor.id_karyawan')
                    ->join('ref_komponen_honor','data_karyawan_honor.id_komponen_honor','=','ref_komponen_honor.id_komponen_honor')
                    ->join('data_departemen','data_karyawan.id_departemen','=','data_departemen.id_departemen')
                    ->join('ref_profesi','data_karyawan.id_profesi','=','ref_profesi.id_profesi');
                if(!empty($req['id_karyawan']))
            			$data->where('data_karyawan.id_karyawan', $req['id_karyawan']);
                if(!empty($req['id_departemen']))
            			$data->where('data_karyawan.id_departemen', $req['id_departemen']);
    }
}

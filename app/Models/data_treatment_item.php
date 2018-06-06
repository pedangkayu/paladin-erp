<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_treatment_item extends Model
{
  protected $table 		= 'data_treatment_item';
  protected $primaryKey 	= 'id_treatment_item';
  protected $fillable 	= [
    'id_treatment',
    'id_service',
    'tipe', //1 tipe tindakan =/2= tipe jasa ',
    'service_kode',
    'tarif_dasar',
    'tarif_rs',
    'tarif_dr',
    'status' //1 = di ambil 2= permohonan refound(unit) 3= disetujui pembataln/keunagan 4== Lunas
  ];
  public function scopeTindakan($query, $id) {
              $query->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'data_treatment_item.service_kode')
                    ->leftJoin('data_treatment_dokter', 'data_treatment_dokter.id_treatment_item', '=' ,'data_treatment_item.id_treatment_item')
                    ->leftJoin('data_karyawan', 'data_karyawan.id_karyawan' ,'=' ,'data_treatment_dokter.id_dokter')
                    ->where('data_treatment_item.id_treatment', $id)
                    ->whereIn('data_treatment_item.status',[1,2,4])
                    ->select(
                    'data_treatment_item.*',
                    'ref_service_kode.nm_service',
                    'ref_service_kode.tarif_dasar',
                    'data_treatment_dokter.id_dokter',
                    'data_treatment_dokter.id_treatment_dokter',
                    'ref_service_kode.id_unit','data_treatment_dokter.jabatan',
                    'data_karyawan.nm_depan', 'data_karyawan.nm_belakang'

                  );
                  
	               }

  public function bhp(){
    return $this->hasMany('App\Models\data_resep_item', 'id_treatment_item');
  }

}
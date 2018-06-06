<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_log_honor extends Model
{
    protected $table = 'data_log_honor';
    protected $primaryKey ='id_log_honor';
	protected $fillable = [
        'id_karyawan_honor',
        'id_karyawan',
        'total_pendapatan',
        'total_potongan',
        'sisa_gaji',
        'periode',
        'id_user',
        'status_pembayaran', //1= belum di terima  2 sudah di terima
        'status',
	];

    public function scopeByid($query, $id){
    $query->join('data_log_honor_item','data_log_honor_item.id_log_honor','=','data_log_honor.id_log_honor')
            ->join('ref_komponen_honor', 'ref_komponen_honor.id_komponen_honor', '=', 'data_log_honor_item.id_komponen_honor')
            ->where('data_log_honor.id_log_honor', $id)
            ->select(
                'data_log_honor.id_log_honor',
                'ref_komponen_honor.nm_komponen_honor',
                'data_log_honor_item.nilai',
                'data_log_honor_item.id_log_honor_item',
                'data_log_honor_item.id_karyawan_honor');
                    }
    public function scopebygajian($query, $req = []){
        $prode_tahun=$req['tahun'];
        // dd($req['bulan']));
        $prode_bulan=$req['bulan'];
          $item = $query->leftjoin('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_log_honor.id_karyawan')
                          ->leftjoin('data_departemen', 'data_departemen.id_departemen', '=', 'data_karyawan.id_departemen');
          if(!empty($req['id_karyawan']))
              $item->where('data_log_honor.id_karyawan', $req['id_karyawan']);
          if(!empty($req['id_departemen']))
              $item->where('data_karyawan.id_departemen', $req['id_departemen']);
              $item->where(\DB::raw('MONTH(data_log_honor.periode)'),$prode_bulan);
              $item->where(\DB::raw('YEAR(data_log_honor.periode)'),$prode_tahun);
          }

    public function scopeByidprint($query, $id){
        $query->join('data_log_honor_item','data_log_honor_item.id_log_honor','=','data_log_honor.id_log_honor')
            ->join('ref_komponen_honor', 'ref_komponen_honor.id_komponen_honor', '=', 'data_log_honor_item.id_komponen_honor')
            ->where('data_log_honor.id_log_honor', $id)
            ->select(
                'data_log_honor.id_log_honor',
                'ref_komponen_honor.nm_komponen_honor',
                'data_log_honor_item.nilai');
                    }


    public function scopeBypotongan($query,$id){
        $query->join('data_karyawan_potongan','data_karyawan_potongan.id_log_honor','=','data_karyawan_potongan.id_log_honor')
                ->leftjoin('data_loan','data_loan.id_loan', '=', 'data_karyawan_potongan.id_loan')
                ->where('data_log_honor.id_log_honor', $id)
                ->where('data_karyawan_potongan.tipe_potongan',1)
                ->select(
                    'data_karyawan_potongan.*');
                        }


	public function scopeBypotonganprint($query,$id){
		$query->join('data_karyawan_potongan','data_karyawan_potongan.id_log_honor','=','data_karyawan_potongan.id_log_honor')
				->leftjoin('data_loan','data_loan.id_loan', '=', 'data_karyawan_potongan.id_loan')
				->where('data_log_honor.id_log_honor', $id)
				->where('data_karyawan_potongan.tipe_potongan',1)
				->select(
					'data_karyawan_potongan.*');
						}


    public function scopeBycasbon($query,$id){
        $query->join('data_karyawan_potongan','data_karyawan_potongan.id_log_honor','=','data_karyawan_potongan.id_log_honor')
                ->leftjoin('data_loan','data_loan.id_loan', '=', 'data_karyawan_potongan.id_loan')
                ->where('data_log_honor.id_log_honor', $id)
                ->where('data_karyawan_potongan.tipe_potongan',2)
                ->select(
                    'data_karyawan_potongan.*');
                        }


    public function scopeBycasbonprint($query,$id){
        $query->join('data_karyawan_potongan','data_karyawan_potongan.id_log_honor','=','data_karyawan_potongan.id_log_honor')
                ->leftjoin('data_loan','data_loan.id_loan', '=', 'data_karyawan_potongan.id_loan')
                ->where('data_log_honor.id_log_honor', $id)
                ->where('data_karyawan_potongan.tipe_potongan',2)
                ->select(
                    'data_karyawan_potongan.*');
                        }
}

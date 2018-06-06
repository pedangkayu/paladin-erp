<?php

namespace App\Jobs\Penggajian;
use App\Models\data_log_honor;
use App\Models\data_log_honor_item;
use App\Models\data_loan;
use App\Models\data_log_loan;
use App\Models\ref_komponen_honor;
use App\Models\data_karyawan_potongan;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdatePenggajianJob extends Job implements SelfHandling
{
    public $req;
    public function __construct(array $req)
    {
        $this->req = $req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $me = \Me::data()->id_karyawan;
        // dd($this->req);
        try{
           \DB::begintransaction();
               $honor = data_log_honor::find($this->req['id_log_honor']);
               $honor->update([
                'id_karyawan'       => $this->req['id_karyawan'],
                'total_pendapatan'  => $this->req['total_pendapatan'],
                'total_potongan'    => $this->req['total_potongan'],
                'periode'            => date('Y-m-d', strtotime($this->req['tanggal'])),
                'sisa_gaji'         => $this->req['gaji_bersih'],
                'id_user'           => $me,
            ]);
            if(!empty($this->req['id_komponen_honor']) && count($this->req['id_komponen_honor']) > 0){
            foreach ($this->req['id_karyawan_honor'] as $i => $id) {
                data_log_honor_item::find($this->req['id_log_honor_item'][$i])->update([
                    'id_log_honor'      => $this->req['id_log_honor'],
                    'id_karyawan_honor' => $this->req['id_karyawan_honor'][$i],
                    'nilai'             => $this->req['total'][$i],
                ]);
              }
          }
        //   potongan
        if(!empty($this->req['total_potongan_lainnya'])):
              foreach ($this->req['qty_potongan'] as $k => $ad):
                  $ambil=ref_komponen_honor::where('id_komponen_honor',$this->req['id_loanku'][$k])->first();
                    data_karyawan_potongan::create([
                        'id_log_honor'     => $honor->id_log_honor,
                        'id_loan'          => $this->req['id_loanku'][$k],
                        'nm_potongan'      => $ambil->nm_komponen_honor,
                        'jumlah_potongan' => $this->req['total_potongan_lainnya'][$k],
                        'tipe_potongan'    => 1, //a potongan selain kasbon
                      ]);
              endforeach ;
       endif;
          if(!empty($this->req['tipe_potongan_honor'])):
                foreach ($this->req['qty_potongan_ku'] as $k => $ad):
                    data_karyawan_potongan::find($this->req['id_potongan'][$k])->update([
                          'id_log_honor'     => $this->req['id_log_honor'],
                        //   'id_loan'          => $this->req['id_loan'][$k],
                          'jumlah_potongan' => $this->req['total_potongan_lainnya_ku'][$k],
                        ]);
                endforeach ;
         endif;

           \DB::commit();

          return [
              'res' => true,
              'label' => 'success',
              'err' => 'Proses Update Penggajian Berhasil. ',
              'id_log_honor' =>$this->req['id_log_honor'],
          ];

      }catch(\Exception $e){

          \DB::rollback();

          return [
             'res' => false,
              'label' => 'danger',
              'err' => $e->getMessage()
          ];

      }
    }
}

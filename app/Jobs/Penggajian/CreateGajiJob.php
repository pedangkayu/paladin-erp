<?php

namespace App\Jobs\Penggajian;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\data_log_honor;
use App\Models\data_log_honor_item;
use App\Models\data_loan;
use App\Models\data_log_loan;
use App\Models\ref_komponen_honor;
use App\Models\data_karyawan_potongan;
class CreateGajiJob extends Job implements SelfHandling
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
           $honor = data_log_honor::create([
                'id_karyawan'       => $this->req['id_karyawan'],
                'total_pendapatan'  => $this->req['total_pendapatan'],
                'total_potongan'    => $this->req['total_potongan'],
                'periode'            => date('Y-m-d', strtotime($this->req['tanggal'])),
                'sisa_gaji'         => $this->req['gaji_bersih'],
                'id_user'           => $me,
                'status_pembayaran' => 1,
                'status'            => 1,
            ]);
            if(!empty($this->req['id_komponen_honor']) && count($this->req['id_komponen_honor']) > 0){
            foreach ($this->req['id_karyawan_honor'] as $i => $id) {
                data_log_honor_item::create([
                    'id_log_honor'      => $honor->id_log_honor,
                    'id_komponen_honor' => $this->req['id_komponen_honor'][$i],
                    'id_karyawan_honor' => $this->req['id_karyawan_honor'][$i],
                    'nilai'             => $this->req['total'][$i],
                ]);
              }
          }
        //   bayar hutang
         if(!empty($this->req['id_loan'])){
              if(empty($this->req['nominal']))
                  throw new \Exception("Hutang Anda telah terlunasi", 1);

              if(empty($this->req['nilai_hutang']))
                  throw new \Exception("Jumlah pengembalian Tidak Boleh 0", 1);
              $cek1 = data_loan::where('id_karyawan',$this->req['id_karyawan'])->whereIn('status',[3])->first();

               if(count($cek1) >0 )
                  throw new \Exception("Mohon Maaf Pinjaman Anda sudah Lunas", 1);
              //
                  $cek2 = data_loan::where('id_karyawan',$this->req['id_karyawan'])->whereIn('status',[2])->first();
                  $cek_sisa_pinjaman=$cek2->nominal - $cek2->total_terbayar;
                  if($cek_sisa_pinjaman < $this->req['nilai_hutang'])
                      throw new \Exception("Mohon Anda memasukan melebihi Hutang Anda", 1);

                  $pinjam = data_loan::find($this->req['id_loan']);
                  $pinjam->update([
                          'id_karyawan'    =>$this->req['id_karyawan'],
                          'nominal'        => $this->req['nominal'],
                          'total_terbayar' =>$this->req['total_terbayar'] + $this->req['nilai_hutang'],
                      ]);
                  $sisa=$pinjam->nominal - $pinjam->total_terbayar;
                  $hasil=$sisa;
                  if($hasil==0){
                      $pinjam->update([
                              'status'    =>3,
                          ]);
                  }
                    $item=data_log_loan::create([
                            'id_loan'           =>$this->req['id_loan'],
                            'keterangan'        =>'pengembalian pinjaman ,Via potongan gaji',
                            'id_bank'           =>0,
                            'id_payment_method' =>7,
                            'sisa_hutang'       => $hasil,
                            'bayar'             =>$this->req['nilai_hutang'], //jumlah di bayr saat itu
                            'id_user'           =>$me,
                            'catatan'           =>'pengembalian pinjaman ,Via potongan gaji',
                    ]);
                        $d=data_karyawan_potongan::create([
                            'id_log_honor'     => $honor->id_log_honor,
                            'id_loan'          => $this->req['id_loan'],
                            'nm_potongan'      => $this->req['potongan_gaji'],
                            'jumlah_potonngan' => $this->req['total_potongan'],
                            'tipe_potongan'    => 2, //a potongan  kasbon
                          ]);
          }
          if(!empty($this->req['total_potongan_lainnya'])):
                foreach ($this->req['qty_potongan'] as $k => $ad):
                    $ambil=ref_komponen_honor::where('id_komponen_honor',$this->req['id_loanku'][$k])->first();
                      data_karyawan_potongan::create([
                          'id_log_honor'     => $honor->id_log_honor,
                          'id_loan'          => $this->req['id_loanku'][$k],
                          'nm_potongan'      => $ambil->nm_komponen_honor,
                          'jumlah_potonngan' => $this->req['total_potongan_lainnya'][$k],
                          'tipe_potongan'    => 1, //a potongan selain kasbon
                        ]);
                endforeach ;
         endif;

           \DB::commit();

          return [
              'res' => true,
              'label' => 'success',
              'err' => 'Proses Penggajian Berhasil. ',
              'id_log_honor' =>$honor->id_log_honor
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

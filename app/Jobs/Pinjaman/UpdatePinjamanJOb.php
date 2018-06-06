<?php

namespace App\Jobs\Pinjaman;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\data_loan;
use App\Models\data_log_loan;

class UpdatePinjamanJOb extends Job implements SelfHandling
{
     public $req;
    /**
     * Create a new job instance.
     *
     * @return void
     */
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

            if(empty($this->req['nominal']))
                throw new \Exception("Hutang Anda telah terlunasi", 1);

            if(empty($this->req['pengembalian']))
                throw new \Exception("Jumlah pengembalian Tidak Boleh 0", 1);
            $cek1 = data_loan::where('id_karyawan',$this->req['id_karyawan'])->whereIn('status',[3])->first();
             if(count($cek1) >0 )
                throw new \Exception("Mohon Maaf Pinjaman Anda sudah Lunas", 1);

                $cek2 = data_loan::where('id_karyawan',$this->req['id_karyawan'])->whereIn('status',[2])->first();
                $cek_sisa_pinjaman=$cek2->nominal - $cek2->total_terbayar;
                if($cek_sisa_pinjaman < $this->req['pengembalian'])
                    throw new \Exception("Mohon Anda memasukan melebihi Hutang Anda", 1);

                $pinjam = data_loan::find($this->req['id_loan']);
                $pinjam->update([
                        'id_karyawan'    =>$this->req['id_karyawan'],
                        'nominal'        => $this->req['nominal'],
                        'total_terbayar' =>$this->req['total_terbayar'] + $this->req['pengembalian'],
                        'tanggal'        =>date('Y-m-d H:m', strtotime($this->req['datetime_in'])),
                    ]);
                $sisa=$pinjam->nominal - $pinjam->total_terbayar;
                $hasil=$sisa;
                if($hasil==0){
                    $pinjam->update([
                            'status'    =>3,
                        ]);
                }
                $create=data_log_loan::create([
                        'id_loan'           =>$this->req['id_loan'],
                        'keterangan'        =>$this->req['keterangan'],
                        'id_bank'           =>$this->req['method'],
                        'id_payment_method' =>$this->req['tipe'],
                        'sisa_hutang'       => $hasil,
                        'bayar'             =>$this->req['pengembalian'], //jumlah di bayr saat itu
                        'id_user'           =>$me,
                        'catatan'           =>'pengembalian pinjaman',
                ]);



             \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Pembayaran Pinjaman  Berhasil sebesar.  Rp.'. number_format($this->req['pengembalian'] ,0,',','.')
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

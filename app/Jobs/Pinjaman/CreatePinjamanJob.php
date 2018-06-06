<?php

namespace App\Jobs\Pinjaman;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\data_loan;
use App\Models\Views\view_count_pinjaman_karyawan;

class CreatePinjamanJob extends Job implements SelfHandling
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
                throw new \Exception("Jumlah setoran tidak boleh kosong", 1);
                
            $cek1 = data_loan::where('id_karyawan',$this->req['id_karyawan'])->whereIn('status',[1])->first();
            // dd(count($cek1));
             if(count($cek1) >0 )
                throw new \Exception("Maaf Permohonan Pinjaman Anda Masih di Proses", 1);

             $cek = data_loan::where('id_karyawan',$this->req['id_karyawan'])->whereIn('status',[2])->first();
             if(count($cek) > 0 )
                throw new \Exception("Maaf Pinjaman Anda sebelumnya Belum Lunas, Silahkan Lunasi Dulu", 1);
                $seri = view_count_pinjaman_karyawan::whereTahun(date('Y'))->first();
                $seri = empty($seri->jumlah) ? 1 : ($seri->jumlah + 1);
                $no_pinjaman = 'PK/' . date('Y') . '/' . date('m') . '/' . sprintf("%03d", $seri);
                $pinjam = data_loan::Create([
                    'no_pinjaman'   => $no_pinjaman,
                    'id_karyawan'    =>$this->req['id_karyawan'],
                    'nominal'        => $this->req['nominal'],
                    'id_user'        =>$me,
                    'start_time'     =>date('Y-m-d', strtotime($this->req['dari'])),
                    'end_time'       =>date('Y-m-d', strtotime($this->req['sampai'])),
                    'total_terbayar' =>0,
                    'tipe_pinjaman'  =>1,
                    'status'         =>1,
                    'tanggal'        =>date('Y-m-d H:m', strtotime($this->req['datetime_in'])),
                    'keterangan'     =>$this->req['keterangan'],
                ]);

             \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Permohonan Pinjaman Berhasil Dicatat Sebesar Rp.'. number_format($this->req['nominal'] ,0,',','.')
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
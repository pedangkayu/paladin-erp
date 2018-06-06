<?php

namespace App\Jobs\Rawatinap;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_rawat_inap;
use App\data_log_pasien;

class CreateRawatJob extends Job implements SelfHandling
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
    public function handle()
        {
        // dd($this->req);
             try{
            \DB::begintransaction();
         
            $resep = data_rawat_inap::create([
                    'id_pasien'     => $this->req['id_pasien'],
                    'id_pasien'     =>$this->req['id_pasien'],
                    'id_kamar'      =>$this->req['id_kamar'],
                    'checkin'       =>$this->req['tgl_cekin'],
                    'checkout'      =>0,
                    'harga'         =>0,
                    'status_in_out' =>1,
                    'status'        =>1,
            ]);
        \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Verifikasi Pasien Rawat Inap Berhasil'
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
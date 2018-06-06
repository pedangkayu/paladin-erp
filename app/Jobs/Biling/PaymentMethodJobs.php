<?php

namespace App\Jobs\Biling;

use App\Models\data_jurnal_pembayaran;
use App\Models\data_faktur;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class PaymentMethodJobs extends Job implements SelfHandling {

    public $req;
    
    public function __construct(array $req) {
        $this->req = $req;
    }

    public function handle() {
        
        $me = \Me::data()->id_karyawan;

        try{

            \DB::begintransaction();
            data_jurnal_pembayaran::where('id_faktur', $this->req['id_faktur'])->delete();
            foreach($this->req['tipe'] as $i => $tipe){
                data_jurnal_pembayaran::create([
                    'id_faktur' => $this->req['id_faktur'],
                    'id_bank' => $this->req['method'][$i],
                    'id_pasien' => $this->req['id_pasien'],
                    'jumlah' => $this->req['jumlah'][$i],
                    'id_asuransi' => $this->req['method'][$i],
                    'no_asuransi' => $this->req['no_asuransi'][$i],
                    'tipe_payment_method' => $tipe,
                    'id_karyawan' => $me
                ]);
            }



            $faktur = data_faktur::find($this->req['id_faktur']);
            $faktur->payment_status_pembayaran = 2;
            $faktur->status = 2;
            $faktur->save();

            \DB::commit();

            return [
                'result' => true,
                'err' => 'Pembayaran berhasil dilakukan',
                'label' => 'success'
            ];

        }catch(\Exception $e){
            \DB::rollback();
            return [
                'result' => false,
                'err' => $e->getMessage(),
                'label' => 'danger'
            ];

        }

    }
}

<?php

namespace App\Jobs\Biling;

use App\Models\data_jurnal;
use App\Models\data_faktur;
use App\Models\ref_coa;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class InputCOaJob extends Job implements SelfHandling {

    public $req;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $req) {
        $this->req = $req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        
        $me = \Me::data()->id_karyawan;

        try{

            \DB::begintransaction();

            // foreach($this->req['id_coa'] as $i => $id_coa){
            //     if($this->req['total'][$i] > 0)
            //         data_jurnal::create([
            //             'id_faktur' => $this->req['id_faktur'],
            //             'id_coa' => $this->req['id_coa'][$i],
            //             'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
            //             'deskripsi' => $this->req['keterangan'][$i],
            //             'id_payment_methode' => 0,
            //             'tipe' => $this->req['tipe'][$i],
            //             'total' => $this->req['total'][$i],
            //             'id_karyawan' => $me

            //         ]);

            //     $coa = ref_coa::find($this->req['id_coa'][$i]);
            //     if($coa != NULL){
            //         if($this->req['tipe'][$i] == 1){
            //             $coa->balance = $coa->balance + $this->req['total'][$i];
            //         }else{
            //             $coa->balance = $coa->balance - $this->req['total'][$i];
            //         }
            //         $coa->save();
            //     }

            // }

            $faktur = data_faktur::find($this->req['id_faktur']);
            $faktur->payment_status_pembayaran = 3;
            $faktur->save();

            \DB::commit();

            return [
                'result' => true,
                'label' => 'success',
                'err' => 'Payment berhasil dilakukan'
            ];

        }catch(\Exception $e){
            \DB::rollback();

            return [
                'result' => false,
                'label' => 'danger',
                'err' => $e->getMessage()
            ];
        }

    }
}

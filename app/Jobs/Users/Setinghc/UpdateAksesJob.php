<?php

namespace App\Jobs\Users\Setinghc;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\models\data_transfer;

class UpdateAksesJob extends Job implements SelfHandling
{
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
        //dd($this->req);
        try{
            \DB::begintransaction();

            $transfer = data_transfer::find($this->req['id_transfer']);
            $transfer->update([
                    'id_layanan'        =>$this->req['id_layanan'],
                    'no_antrian'        =>$this->req['no_antrian'],
                    'tabel_antrian'     =>$this->req['tabel_antrian'],
                    'id_gudang_jasa'     =>$this->req['id_layanan_sim'],
                    'id_gudang_item'    =>$this->req['id_gudang'],
            ]);

           

            \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => '<center>Data berhasil diperbaharui</center>'
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

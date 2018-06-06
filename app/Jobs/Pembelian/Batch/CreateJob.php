<?php

namespace App\Jobs\Pembelian\Batch;

use App\Models\data_batch;
use App\Models\data_spbm_item;
use App\Models\data_spbm;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateJob extends Job implements SelfHandling {

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
        
        try{

            $me = \Me::data();
            $name = $me->nm_depan . ' ' . $me->nm_belakang;

            \DB::begintransaction();

            $item = data_spbm_item::find($this->req['id_spbm_item']);
            $gr = data_spbm::find($this->req['id_spbm']);
            
            $batch = data_batch::create([
                'nomor_batch' => $this->req['no_batch'],
                'id_spbm' => $this->req['id_spbm'],
                'id_spbm_item' => $this->req['id_spbm_item'],
                'id_barang' => $item->id_barang,
                'total_qty' => $this->req['qty'],
                'in' => $this->req['qty'],
                'out' => 0,
                'tgl_expired' => $this->req['tgl_exp'],
                'titipan' => $gr->titipan,
                'id_karyawan' => $me->id_karyawan
            ]);

            \Loguser::create($name . ' Menambahkan Batch dengan No. ' . $this->req['no_batch']);

            \DB::commit();

            return [
                'result' => true,
                'err' => 'Berhasil dibuat.'
            ];

        }catch(\Exception $e){
            \DB::rollback();
            return [
                'result' => false,
                'err' => $e->getMessage()
            ];
        }

    }
}

<?php

namespace App\Jobs\Treatment;

use App\Models\ref_service;
use App\Models\ref_service_item;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class InsertMasterJob extends Job implements SelfHandling
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
    public function handle()
    {
     //dd($this->req);
       $gudang= \Me::subgudang()->id_gudang;
          try{
            \DB::begintransaction();
         
        if(count($this->req['service_kode']) > 0){
          foreach($this->req['service_kode'] as $i =>$id){
                $item=ref_service::create([
                    'id_grup' => $this->req['id_grup'],
                    'service_kode' => $this->req['service_kode'][$i],
                    'parend_id' =>0,
                    'unit' => $gudang,
                    'id_service_detail' =>0,
                    'status' => 1,
                ]);

           if(!empty($this->req['service_kode_jasa'][$id])) :
             foreach($this->req['service_kode_jasa'][$id] as $a =>$l):
             ref_service::create([
                    'id_grup' => $this->req['id_grup'],
                    'service_kode' => $l,
                    'parend_id' =>$item->id_service,
                    'unit' => $gudang,
                    'id_service_detail' =>$this->req['id_service_detail'][$id][$a],
                    'status' => 1,
                ]);
           
               endforeach; // end jasa
             endif;
            // dd($this->req);
             if(!empty($this->req['id_barang'][$id])) :
            foreach($this->req['id_barang'][$id] as $index => $val):
                    ref_service_item::create([
                    'id_service'     => $item->id_service,
                    'id_barang'    => $val,
                    'status'    =>1,
                    'id_satuan'    =>$this->req['id_satuan'][$id][$index],
                    'qty'          =>$this->req['jumlah_out'][$id][$index],
                ]);
                  endforeach;
                  endif;

                }
              }
                  
            \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Pembuatan Data Treatment Berhasil. '
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


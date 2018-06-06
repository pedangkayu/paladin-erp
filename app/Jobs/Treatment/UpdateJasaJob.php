<?php

namespace App\Jobs\Treatment;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\ref_service;
use App\Models\ref_service_item;

class UpdateJasaJob extends Job implements SelfHandling
{
    public $req;

    public function __construct(array $req){
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

              if(!empty($this->req['id_service_detaili']) && count($this->req['id_service_detaili']) > 0){
                foreach ($this->req['id_service_detaili'] as $i => $id) {
                  ref_service::create([
                        'service_kode' => $this->req['service_kode_jasa'][$i],
                        'id_service_detail' =>$id,
                        'parend_id'       =>$this->req['ketua'],
                        'id_grup'     =>$this->req['grup'],
                        'unit'         =>$this->req['id_unit'],
                        'status'  =>1,
                            ]);
                      }
                  }

                if(!empty($this->req['id_service_item']) && count($this->req['id_service_item']) > 0){
                foreach ($this->req['id_service_item'] as $idi => $idii) {
                  ref_service_item::find($this->req['id_service_item'][$idi])->update([
                      'id_service'     => $this->req['ketua'],
                      'id_barang'    => $this->req['id_barang'][$idi],
                      'id_satuan'    =>$this->req['id_satuan'][$idi],
                      'status'      =>1,
                      'qty'          =>$this->req['qty'][$idi],
                            ]);
                      }
                  }
              if(!empty($this->req['id_barangi']) && count($this->req['id_barangi']) > 0){
                foreach ($this->req['id_barangi'] as $a => $ia) {
                ref_service_item::create([
                    'id_service'     => $this->req['ketua'],
                    'id_barang'    => $ia,
                    'id_satuan'    =>$this->req['id_satuani'][$a],
                    'qty'          =>$this->req['jumlah_out'][$a],
                    'status' =>1,
                    ]);
                      }
                  }
                
                  \DB::commit();
                            return [
                                'res' => true,
                                'label' => 'success',
                                'err' => 'perbaruan data berhasil. '
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

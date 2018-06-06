<?php

namespace App\Jobs\Treatment;

use App\Models\data_paket;
use App\Models\data_paket_item;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class AdditemJob extends Job implements SelfHandling
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

    public function handle()
    {
         // dd($this->req);
          try{
            \DB::begintransaction();
        $pak=data_paket::find($this->req['id_paket'] );
        if(!empty($this->req['id_service']) && count($this->req['id_service']) > 0){
          foreach($this->req['id_service'] as $i =>$id_service){
                data_paket_item::create([
                    'id_paket'     => $pak->id_paket,
                    'id_service'   => $this->req['id_service'][$i],
                    'id_barang'    => 0,
                    'id_satuan'    =>0,
                    'qty'          =>0,
                    'status'       =>1, 
                ]);
                 }
             }


            if(!empty($this->req['id_barang']) && count($this->req['id_barang']) > 0){
                foreach($this->req['id_barang'] as $index => $val):
                    data_paket_item::create([
                    'id_paket'     => $pak->id_paket,
                    'id_barang'    => $this->req['id_barang'][$index],
                    'id_service'   => 0,
                    'id_satuan'    =>$this->req['id_satuan'][$index],
                    'qty'          =>$this->req['jumlah_out'][$index],
                    'status'       =>1, 
                ]);
                  endforeach;
                
            }
              \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Penambahan Item Paket Treatment Berhasil. '.$pak->nm_paket
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

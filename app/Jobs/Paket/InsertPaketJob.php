<?php

namespace App\Jobs\Paket;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\ref_service_kode;

class InsertPaketJob extends Job implements SelfHandling
{
    public $req;
    
     public function __construct(array $req)
   {
        $this->req = $req;
    }

    public function handle()
    {
         //dd($this->req);
          $gudang= \Me::subgudang()->id_gudang;
          try{
            \DB::begintransaction();

            foreach($this->req['nm_service'] as $i =>$id):
                $item=ref_service_kode::create([
                    'nm_service'  => $this->req['nm_service'][$i],
                    'type'        => $this->req['type'][$i],
                    'coa'         => $this->req['coa'][$i],
                    'coa_rs'      => $this->req['coa_rs'][$i],
                    'persen_rs'   => $this->req['persen_rs'][$i],
                    'coa_pendapatan' => $this->req['coa_pendapatan'][$i],

                    'coa_dr'      => $this->req['coa_dr'][$i],
                    'id_unit'     =>$gudang,
                    'persen_dr'   => $this->req['persen_dr'][$i],
                    'keterangan'  =>0,
                    'tarif_dasar' =>0,
                    'status'      =>1,
                ]);
            endforeach;
            
              \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Penambahan Item Paket Treatment Berhasil. '
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

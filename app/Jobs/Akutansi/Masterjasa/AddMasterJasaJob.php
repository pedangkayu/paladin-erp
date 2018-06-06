<?php

namespace App\Jobs\Akutansi\Masterjasa;

use App\Models\ref_service_kode;
use App\Models\ref_service_detail;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class AddMasterJasaJob extends Job implements SelfHandling
{
   public $req;
    
     public function __construct(array $req)
   {
        $this->req = $req;
    }

    public function handle()
    {
        // dd($this->req);
       
           try{
            \DB::begintransaction();
            
              $rs=((100) - ($this->req['persen_dr']));
              //dd($this->req->rs);
                $item=ref_service_kode::create([
                    'nm_service'  => $this->req['nm_service'],
                    'type'        => $this->req['type'],
                    'coa'         => $this->req['coa'],
                    'coa_rs'      => $this->req['coa_rs'],
                    'persen_rs'   => $rs,
                    'coa_dr'      => $this->req['coa_dr'],
                    'id_unit'     =>0,
                    'coa_pendapatan' =>$this->req['coa_pendapatan'],
                    'tarif_dasar' =>$this->req['tarif_dasar'],
                    'persen_dr'   => $this->req['persen_dr'],
                    'keterangan'  =>0,
                    'status'      =>1,
                ]);
          if(!empty($this->req['unit']) && count($this->req['unit']) > 0){
            foreach ($this->req['unit'] as $i => $id) {
                ref_service_detail::create([
                        'id_service_kode' => $item->service_kode,
                        'id_unit'         =>$id,
                        'kebutuhan'       =>$this->req['kebutuhan'][$i],
                        
                        ]);
              }
          }
            
               \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Penambahan Master Jasa Berhasil. '
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

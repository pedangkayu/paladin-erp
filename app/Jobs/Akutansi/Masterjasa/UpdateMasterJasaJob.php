<?php

namespace App\Jobs\Akutansi\Masterjasa;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\ref_service_kode;
use App\Models\ref_service_detail;

class UpdateMasterJasaJob extends Job implements SelfHandling
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
            $rs=((100) - ($this->req['persen_dr']));
             $item=ref_service_kode::find($this->req['service_kode']);
                    $item->nm_service  = $this->req['nm_service'];
                    $item->type        = $this->req['type'];
                    $item->coa         = $this->req['coa'];
                    $item->coa_rs      = $this->req['coa_rs'];
                    $item->persen_rs   = $rs;
                    $item->coa_dr      = $this->req['coa_dr'];
                    // $item->id_unit     =0;
                    $item->coa_pendapatan=$this->req['coa_pendapatan'];
                    $item->tarif_dasar = $this->req['tarif_dasar'];
                    $item->persen_dr   = $this->req['persen_dr'];
                    $item->keterangan  =0;
                    $item->status      =1;
                    $item->save();
                      ///update 
              if(!empty($this->req['id_service_detail']) && count($this->req['id_service_detail']) > 0){
                foreach ($this->req['id_service_detail'] as $i => $id) {

                  ref_service_detail::find($this->req['id_service_detail'][$i])->update([
                        'id_service_kode' => $item->service_kode,
                        'id_unit'         =>$this->req['id_unit'][$i],
                        'kebutuhan'       =>$this->req['kebutuhan1'][$i],
                            ]);
                      }
                  }
                  // tambah lokasi baru
                if(!empty($this->req['unit']) && count($this->req['unit']) > 0){
                foreach ($this->req['unit'] as $ia => $idi) {
                    ref_service_detail::create([
                            'id_service_kode' => $item->service_kode,
                            'id_unit'         =>$idi,
                            'kebutuhan'       =>$this->req['kebutuhan'][$ia],
                            
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

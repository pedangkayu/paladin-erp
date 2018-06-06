<?php

namespace App\Jobs\Treatment;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\ref_service_kode;

class InserPelayananJob extends Job implements SelfHandling
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
        // dd($this->req);
        try{
            \DB::begintransaction();
              $pelayanan = ref_service_kode::create([ 
                   'service' =>$this->req['service'],
                    'coa' =>$this->req['id_coa'],
                    'coa_rs' =>$this->req['coa_rs'] ,
                    'persen_rs' =>$this->req['persen_rs'],
                    'coa_dr' =>$this->req['coa_dr'],
                    'persen_dr' =>$this->req['persen_dr'],
                    'status'    =>0,
                    ]);

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

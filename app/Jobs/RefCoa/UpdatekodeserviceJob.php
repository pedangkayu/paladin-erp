<?php

namespace App\Jobs\RefCoa;

use App\Jobs\Job;
use App\Models\ref_service_kode;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdatekodeserviceJob extends Job implements SelfHandling
{

    public $req;
    public function __construct(array $req)
    {
       $this->req=$req;
    }


    public function handle()
    {
        ref_service_kode::find($this->req['service_kode'])
            ->update([
                'service_kode' =>$this->req['service_kode'],
                'nm_service'   =>$this->req['nm_service'],
                'type'         =>$this->req['type'], 
                'coa'          =>$this->req['coa'],
                'coa_rs'       =>$this->req['coa_rs'],
                'coa_pendapatan' =>$this->req['coa_pendapatan'],
                'persen_rs'    =>$this->req['persen_rs'],
                'tarif_dasar'  =>$this->req['tarif_dasar'],
                'coa_dr'       =>$this->req['coa_dr'],
                'persen_dr'    =>$this->req['persen_dr'],
                'status'       =>$this->req['status'],
                ]);
        return $this->req;
    }
}

<?php

namespace App\Jobs\RefCoa;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\ref_coa;

class CreateCoaJob extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $req;

    public function __construct(array $req){
        $this->req = $req;
    }
    
    public function handle(){
        // dd($this->req);
        return ref_coa::create([
            'parent_id' => $this->req['id_coa'],
            'kode' => $this->req['kode'],
            'nm_coa' => $this->req['nm_coa'],
            'type' => $this->req['type'],
            'grup'  =>$this->req['grup'],
            'keterangan' => $this->req['keterangan'],
            'status' => 1,
            'balance' =>0,
            'cash'=>0,
        ]);
    }
}

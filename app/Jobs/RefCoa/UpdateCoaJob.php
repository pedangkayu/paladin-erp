<?php

namespace App\Jobs\RefCoa;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\ref_coa;

class UpdateCoaJob extends Job implements SelfHandling
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd($this->req);
        ref_coa::find($this->req['id_coa'])
        ->update([
           'parent_id' => $this->req['parent_id'],
            'kode' => $this->req['kode'],
            'nm_coa' => $this->req['nm_coa'],
            'type' => $this->req['type'],
            'grup'  =>$this->req['grup'],
            'keterangan' => $this->req['keterangan'],
            ]);

        return $this->req;
    }
}

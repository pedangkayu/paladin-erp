<?php

namespace App\Jobs\Personalia;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_karyawan;
use App\Models\data_karyawan_honor;

class UpdateHonorJob extends Job implements SelfHandling
{
    public $req;
    /**
     * Create a new job instance.
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
        data_karyawan_honor::find($this->req['id_karyawan_honor'])
        ->update([
            // 'id_karyawan' => $this->req['id_karyawan'],
            'id_komponen_honor' => $this->req['id_komponen_honor'],
            'nilai' => $this->req['nilai'],
            // 'waktu_honor' => $this->req['waktu_honor'],
            ]);

        return $this->req;
    }
}
 
<?php

namespace App\Jobs\Treatment;

use App\Jobs\Job;
use App\Models\data_log_pasien;
use Illuminate\Contracts\Bus\SelfHandling;

class PindahKelasJob extends Job implements SelfHandling
{
    public $req;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( array $req)
    {
        $this->req=$req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            try{
                \DB::begintransaction();
                $Pindah =data_log_pasien::create([
                        'id_layanan'    => $this->req['id_layanan'],
                        'id_pasien'     =>$this->req['id_pasien'],
                        'id_kelas'      =>$this->req['id_kelas'],
                        'nama_pasien'   =>$this->req['nama_pasien'],
                        'tipe'          =>$this->req['tipe'],
                        'nomor_antrian' =>$this->req['nomor_antrian'],
                        'no_antrian_hc' =>$this->req['no_antrian_hc'],
                        'status'        =>$this->req['status'],
                    ]);

                \DB::commit();
                return[
                        'res' =>true,
                        'label' =>'success',
                        'err' =>'Pindah Kelas Berhasil',
                    ];

                }catch(\Exception $e){
                    \DB::rollback();

                    return [
                            'res' =>false,
                            'label' => 'danger',
                            'err' =>$e->getMessage()
                         ];
                }

    }
}

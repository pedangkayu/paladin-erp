<?php

namespace App\Jobs\Users\Setinghc;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\models\data_transfer;

class SetingHcJob extends Job implements SelfHandling
{
     public $req;
    
     public function __construct(array $req)
   {
        $this->req = $req;
    }

    public function handle()
    {
        try {
            \DB::begintransaction();
            foreach ($this->req['id_layanan'] as $i => $v) {
                $hc=data_transfer::create([
                    'id_layanan'        =>$this->req['id_layanan'][$i],
                    'no_antrian'        =>$this->req['no_antrian'][$i],
                    'tabel_antrian'     =>$this->req['tabel_antrian'][$i],
                    'id_gudang_jasa'     =>$this->req['id_layanan_sim'][$i],
                'id_gudang_item'    =>$this->req['id_gudang'][$i],
              ]);
            }
            \DB::commit();
            return [
            'res' =>true,
            'label' =>'success',
            'err' =>'Penambahn Akses HC VS SIM Berhasil',
                ];
            
        } catch (Exception $e) {
             \DB::rollback();

            return [
                'res' => false,
                'label' => 'danger',
                'err' => $e->getMessage()
            ];
        }
    }
}

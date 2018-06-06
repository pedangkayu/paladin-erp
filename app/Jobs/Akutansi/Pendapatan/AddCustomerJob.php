<?php

namespace App\Jobs\Akutansi\Pendapatan;

use App\Models\data_payer;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class AddCustomerJob extends Job implements SelfHandling
{
   public $req;
 
    public function __construct(array $req){
       $this->req = $req;
    }

   
    public function handle(){
       $cus=data_payer::create([
               
                'nm_payer'  => $this->req['nm_payer'],
                'nm_last'   => $this->req['nm_last'],
                'alamat'    => $this->req['alamat'],
                'telpon'    => $this->req['telpon'],
                'fax'   => $this->req['fax'],
                'status'    => 1,
                'id_karyawan'   =>  \Me::data()->id_karyawan,
                'email' => $this->req['email'],
        ]);
       $format= 'PL-';
       $cus->kode= $format .\Format::code($cus->id_payer);
       $cus->save();
       \Loguser::create('Menambahkan data Pelanggan dengan kode ' .$cus->kode);
       return $cus;
    }
}

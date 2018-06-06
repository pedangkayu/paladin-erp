<?php

namespace App\Jobs\RefCoa;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\ref_coa;
use App\Models\data_jurnal;
use App\Models\data_voucer_jurnal;

class CreateCoaLedgerJob extends Job implements SelfHandling
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

        $me = \Me::data()->id_karyawan;

       $coa = ref_coa::create([
            'parent_id' => $this->req['parend_id'],
            'kode' => $this->req['kode'],
            'nm_coa' => $this->req['nm_coa'],
            'type' => $this->req['type'],
            'grup'  =>$this->req['grup'],
            'keterangan' => $this->req['keterangan'],
            'coa_kategori' => $this->req['coa_kategori'],
            'saldo_awal' => $this->req['saldo_awal'],
            'status' => 1,
            'balance' =>$this->req['balance'],
            'cash'=>$this->req['cash'],
        ]);


       if($this->req['saldo_awal'] > 0):

            $voucer = data_voucer_jurnal::create([
                'keterangan' => 'Saldo Awal',
                'status' => 1,
                'tanggal' => date('Y-m-d')
            ]);


            $debit = $coa->type == 1 ? $this->req['saldo_awal'] : 0;
            $kredit = $coa->type == 2 ? $this->req['saldo_awal'] : 0;

            data_jurnal::create([
                'id_faktur' => 0,
                'id_coa' => $coa->id_coa,
                'tanggal' => date('Y-m-d'),
                'deskripsi' => 'Saldo Awal',
                'id_payment_methode' => 0,
                'id_karyawan' => $me,
                'debit' => $debit,
                'kredit' => $kredit,
                
                'tipe_jurnal' => 2, // 1:faktur|2:saldo_awal|3 > opsi lain
                'id_option' => $coa->id_coa, // id yang bersangkutan dengan jurnal
                'link_slug' => '/coa/editleadger/' . $coa->id_coa,
                'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
            ]);
        endif;

    }
}

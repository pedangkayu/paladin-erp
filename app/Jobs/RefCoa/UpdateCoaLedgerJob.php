<?php

namespace App\Jobs\RefCoa;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\ref_coa;
use App\Models\data_jurnal;
use App\Models\data_log_coa;
use App\Models\data_voucer_jurnal;

class UpdateCoaLedgerJob extends Job implements SelfHandling
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
    public function handle() {
        
        $me = \Me::data()->id_karyawan;
        $coa = ref_coa::find($this->req['id_coa']);

        $saldo_awal = $coa->saldo_awal;
        
        if($this->req['saldo_awal'] != $saldo_awal)
            data_log_coa::create([
                'id_coa' => $coa->id_coa,
                // 'parent_id' => $coa->parent_id,
                // 'grup' => $coa->grup,
                // 'type' => $coa->type,
                'kode' => $coa->kode,
                'nm_coa' => $coa->nm_coa,
                // 'balance' => $coa->balance,
                'saldo_awal' => $coa->saldo_awal,
                // 'cash' => $coa->cash,
                // 'status' => $coa->status,
                // 'keterangan' => $coa->keterangan,
                // 'coa_kategori' => $coa->coa_kategori,
                // 'no_coa' => $coa->no_coa,
                'id_karyawan' => $me
            ]); 

        $coa->update([
            'parent_id' => $this->req['parend_id'],
            'kode' => $this->req['kode'],
            'nm_coa' => $this->req['nm_coa'],
            'type' => $this->req['type'],
            'grup'  =>$this->req['grup'],
            'keterangan' => $this->req['keterangan'],
            'status' => 1,
            'balance' =>$this->req['balance'],
            'coa_kategori' =>$this->req['coa_kategori'],
            'saldo_awal' => $this->req['saldo_awal'],
            'cash'=>$this->req['cash'],
        ]);

        if($this->req['saldo_awal'] != $saldo_awal):

            $ju = data_jurnal::firstOrCreate([
                'id_faktur' => 0,
                'id_coa' => $coa->id_coa,
                'deskripsi' => 'Saldo Awal',
                'id_payment_methode' => 0,
                'tipe_jurnal' => 2, // 1:faktur|2:saldo_awal|3 > opsi lain
                'id_option' => $coa->id_coa, // id yang bersangkutan dengan jurnal
                'link_slug' => '/coa/editleadger/' . $coa->id_coa
            ]);


            $debit = $coa->type == 1 ? $this->req['saldo_awal'] : 0;
            $kredit = $coa->type == 2 ? $this->req['saldo_awal'] : 0;
            
            if(empty($ju->id_voucer_jurnal)){
                $voucer = data_voucer_jurnal::create([
                    'keterangan' => 'Saldo Awal',
                    'status' => 1,
                    'tanggal' => date('Y-m-d')
                ]);


                $ju->update([
                    'tanggal' => date('Y-m-d'),
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'id_karyawan' => $me,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);
            }else{
                $ju->update([
                    'tanggal' => date('Y-m-d'),
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'id_karyawan' => $me
                ]);
            }

            
        endif;

        return $this->req;
    }
}
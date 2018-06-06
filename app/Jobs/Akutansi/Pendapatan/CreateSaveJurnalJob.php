<?php

namespace App\Jobs\Akutansi\Pendapatan;

use App\Models\data_jurnal;
use App\Models\data_faktur;
use App\Models\ref_coa;


use App\Models\data_payer;

use App\Models\data_config_coa_pendapatan as Config;
use App\Models\data_voucer_jurnal;


use App\Models\data_piutang_custommer;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateSaveJurnalJob extends Job implements SelfHandling
{
    public $req;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $req) {
        $this->req = $req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){

        $me = \Me::data()->id_karyawan;
        $status = 0;
        //dd($this->req);

        try{

            \DB::begintransaction();
            
            if(empty($this->req['total']))
                throw new \Exception("Total tidak boleh kosong", 1);
                

            if($this->req['total'] > $this->req['total_old'])
                throw new \Exception("Maaf jumlah yang anda bayarkan telalu besar, sisa pembayaran anda adalah RP." . number_format($this->req['total_old'],0,',',','), 1);
            
            $faktur = data_faktur::find($this->req['id_faktur']);

            // Input data jurnal dai header faktur
            $voucer = data_voucer_jurnal::create([
                'keterangan' => 'pembayaran faktur #' . $faktur->nomor_faktur,
                'status' => 1,
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal']))
            ]);

            // Piutang Usaha
            $coa = ref_coa::find($this->req['id_coa']);
            data_jurnal::create([
                'id_faktur' => $faktur->id_faktur,
                'id_coa' =>  $this->req['id_coa'],
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'deskripsi' => $coa->nm_coa,
                'id_payment_methode' => 0,
                'debit' => $this->req['total'],
                'kredit' => 0,
                'id_karyawan' => $me,
                'tipe_jurnal' => 31,
                'link_slug' => '/fakturpendapatan/edit/' . $faktur->id_faktur,
                'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
            ]);

            $customer = data_payer::find($faktur->id_payer);
            // Piutang Usaha
            data_jurnal::create([
                'id_faktur' => $faktur->id_faktur,
                'id_coa' =>  $this->req['coa_piutang'],
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'deskripsi' => 'Piutang usaha ' . $customer->nm_payer,
                'id_payment_methode' => 0,
                'debit' => 0,
                'kredit' => $this->req['total'],
                'id_karyawan' => $me,
                'tipe_jurnal' => 31,
                'link_slug' => '/fakturpendapatan/edit/' . $faktur->id_faktur,
                'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
            ]);


            $sisa = $faktur->total - ($faktur->amount_due + $this->req['total']);
            $faktur->amount_due = ($faktur->amount_due + $this->req['total']);

            $p = data_piutang_custommer::where('id_payer', $faktur->id_payer)->where('id_faktur', $faktur->id_faktur)
            ->update([
                'total' => $sisa,
                'status' => $sisa == 0 ? 2 : 1
            ]);


            if($sisa == 0)
                $status = 2;
            else
                $status = 1;

            $faktur->status = $status;


            $faktur->save();
            \DB::commit();
            
            return [
                'label' => 'success',
                'err' => 'Payment berhasil dilakukan'
            ];

        }catch(\Exception $e){
            \DB::rollback();
            
            return [
                'label' => 'danger',
                'err' => $e->getMessage()
            ];
        }

    }
}

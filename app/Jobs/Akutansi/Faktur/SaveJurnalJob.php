<?php

namespace App\Jobs\Akutansi\Faktur;

use App\Models\data_jurnal;
use App\Models\data_faktur;
use App\Models\ref_coa;

use App\Models\data_hutang_vendor;
use App\Models\data_voucer_jurnal;
use App\Models\data_vendor;
use App\Models\data_config_coa_pembelian as Config;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class SaveJurnalJob extends Job implements SelfHandling {

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

        try{

            \DB::begintransaction();

             $faktur = data_faktur::find($this->req['id_faktur']);
            $status = 1;
             if($this->req['total'] == $this->req['total_old']){
                $faktur->status = 2;
                $status = 2;
             }else{
                 $faktur->status = 1;
             }

             $faktur->amount_due = $faktur->amount_due + $this->req['total'];
             $faktur->save();


            // Input data jurnal dai header faktur
            $voucer = data_voucer_jurnal::create([
                'keterangan' => 'Pembayaran Faktur #' . $faktur->nomor_faktur,
                'status' => 1,
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal']))
            ]);

            $vendor = data_vendor::find($faktur->id_vendor);

            // Pembayaran Hutang
            data_jurnal::create([
                'id_faktur' => $faktur->id_faktur,
                'id_coa' =>  $this->req['perkiraan'],
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'deskripsi' => $this->req['deskripsi'],
                'id_payment_methode' => $this->req['id_payment_methode'],
                'kredit' => 0,
                'debit' => $this->req['total'],
                'id_karyawan' => $me,
                'tipe_jurnal' => 1,
                'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur,
                'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
            ]);


            // Pembayaran Cash
            data_jurnal::create([
                'id_faktur' => $faktur->id_faktur,
                'id_coa' =>  $this->req['id_coa'],
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'deskripsi' => $this->req['deskripsi'],
                'id_payment_methode' => $this->req['id_payment_methode'],
                'kredit' => $this->req['total'],
                'debit' => 0,
                'id_karyawan' => $me,
                'tipe_jurnal' => 1,
                'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur,
                'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
            ]);


            $hutang = data_hutang_vendor::where('id_faktur', $faktur->id_faktur)->first();
            $sisa = $hutang->total -  $this->req['total'];
            $hutang->status = $status;
            $hutang->total = $sisa;
            $hutang->save();



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

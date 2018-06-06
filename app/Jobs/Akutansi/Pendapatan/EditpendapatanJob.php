<?php

namespace App\Jobs\Akutansi\Pendapatan;

use App\Models\data_faktur;
use App\Models\data_faktur_item;

use App\Models\data_payer;

use App\Models\data_config_coa_pendapatan as Config;
use App\Models\data_jurnal;
use App\Models\data_voucer_jurnal;

use App\Models\data_piutang_custommer;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class EditpendapatanJob extends Job implements SelfHandling
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
    
    public function handle() {
        //dd($this->req);
        $me = \Me::data()->id_karyawan;
        $config =  Config::active()->first();

        try{
            \DB::begintransaction();

            $faktur = data_faktur::find($this->req['id']);
            $status = $faktur->status;

            if($status == 2 && $faktur->total > $this->req['grandtotal'])
                throw new \Exception("Maaf, perubahan yang anda lakukan mengakibatkan jumlah pembayaran berkurang...", 1);
                

            // Piutang Usaha
            data_jurnal::where('id_faktur', $faktur->id_faktur)
                ->where('tipe_jurnal', 29)
                ->update([
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'debit' => $this->req['grandtotal'],
                    'kredit' => 0
                ]);

            //  PPH
            data_jurnal::where('id_faktur', $faktur->id_faktur)
                ->where('tipe_jurnal', 4)
                ->update([
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'debit' => 0,
                    'kredit' => $this->req['total_ppn'],
                    'id_karyawan' => $me
                ]);

            // adjustment
            data_jurnal::where('id_faktur', $faktur->id_faktur)
                ->where('tipe_jurnal', 3)
                ->update([
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'debit' => 0,
                    'kredit' => $this->req['adjustment']
                ]);


             // Input data jurnal dai header faktur
            if(count($this->req['old']) < count($this->req['id_barang'])){

                $voucer = data_voucer_jurnal::create([
                    'keterangan' => 'Perubahan Faktur #' . $faktur->nomor_faktur,
                    'status' => 1,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal']))
                ]);

            }

            foreach ($this->req['deskripsi'] as $i => $deskripsi) {
               
                if(empty($this->req['old'][$i])){
                    $itemm = data_faktur_item::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_item' => $this->req['id_barang'][$i],
                        'deskripsi' => $this->req['deskripsi'][$i],
                        'qty' => $this->req['qty'][$i],
                        'harga' => $this->req['harga'][$i],
                        'diskon' => $this->req['diskons'][$i],
                        'total' => $this->req['total'][$i],
                        'id_po' => 0,
                        'id_satuan' => $this->req['id_satuan'][$i],
                    ]);
                    // Pendapatan lainnya
                    data_jurnal::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_coa' =>  $config->coa_pendapatan_lainnya,
                        'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                        'deskripsi' => $this->req['deskripsi'][$i] . ' jumlah' . $this->req['qty'][$i],
                        'id_payment_methode' => 0,
                        'debit' => 0,
                        'kredit' => $this->req['total'][$i],
                        'id_karyawan' => $me,
                        'tipe_jurnal' => 30,
                        'id_option' => $itemm->id_faktur_item,
                        'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur,
                        'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                    ]);
                }else{

                    $item = data_faktur_item::find($this->req['id_faktur_item'][$i])
                        ->update([
                            'qty'       => $this->req['qty'][$i],
                            'harga'     => $this->req['harga'][$i],
                            'diskon'    => $this->req['diskons'][$i],
                            'total'     => $this->req['total'][$i]
                        ]);


                    // Pendapatan lainnya
                    data_jurnal::where('id_option', $this->req['id_faktur_item'][$i])
                        ->where('tipe_jurnal', 30)
                        ->update([
                            'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                            'debit' => 0,
                            'kredit' => $this->req['total'][$i]
                        ]);

                }

                

            }

            if($this->req['grandtotal'] > $faktur->total){
                $status = $faktur->amount_due > 0 ? 1 : 0;

                $sisa = $this->req['grandtotal'] - $faktur->total;

                // Pencatatan hutang vendor
                $ht = data_piutang_custommer::where('id_faktur', $faktur->id_faktur);
                $htg = $ht->first();
                if($htg->total == 0){
                    $ht->update([
                        'id_payer' => $faktur->id_payer,
                        'status' => 1,
                        'total' => $sisa,
                        'tgl_jatuh_tempo' => date('Y-m-d', strtotime($this->req['duodate']))
                    ]);
                }else{
                    $sa = $htg->total + $sisa;
                    $ht->update([
                        'id_payer' => $faktur->id_payer,
                        'status' => 1,
                        'total' => $sa,
                        'tgl_jatuh_tempo' => date('Y-m-d', strtotime($this->req['duodate']))
                    ]);
                }

            }

            $faktur->update([
                //'Nomor_type' => $this->req['no_po'],
                'prefix'           => $this->req['prefix'],
                'type'             => 3,
                'id_vendor'        => 0,
                'id_po'            => 0,
                'tgl_faktur'       => date('Y-m-d', strtotime($this->req['tanggal'])),
                'duodate'          => date('Y-m-d', strtotime($this->req['duodate'])),
                'id_payment_terms' => $this->req['terms'],
                'ppn'              => $this->req['ppn'],
                'adjustment'       => $this->req['adjustment'],
                'subtotal'         => $this->req['subtotal'],
                'total'            => $this->req['grandtotal'],
                'keterangan'       => $this->req['keterangan'],
                'status'           => $status
            ]);

            \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => '<center>Faktur No. #' . $faktur->nomor_faktur . ' berhasil diperbaharui</center>'
            ];

        }catch(\Exception $e){
            \DB::rollback();

            return [
                'res' => false,
                'label' => 'danger',
                'err' => $e->getMessage()
            ];

        }

    }

}

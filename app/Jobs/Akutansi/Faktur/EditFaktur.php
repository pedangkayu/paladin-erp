<?php

namespace App\Jobs\Akutansi\Faktur;

use App\Models\data_faktur;
use App\Models\data_faktur_item;
use App\Models\data_hutang_vendor;
use App\Models\data_voucer_jurnal;
use App\Models\data_vendor;
use App\Models\data_jurnal;
use App\Models\data_config_coa_pembelian as Config;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class EditFaktur extends Job implements SelfHandling {

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
        $status = 0;
        $vendor = data_vendor::find($this->req['supplier']);

        try{
            \DB::begintransaction();

            $faktur = data_faktur::find($this->req['id']);

            $grandtotal = $faktur->total;

            $faktur->update([
                'Nomor_type' => $this->req['no_po'],
                'prefix' => $this->req['prefix'],
                'type' => 1,
                'id_vendor' => $this->req['supplier'],
                'id_po' => $this->req['id_po'],
                'tgl_faktur' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'duodate' => date('Y-m-d', strtotime($this->req['duodate'])),
                'id_payment_terms' => $this->req['terms'],
                'ppn' => $this->req['ppn'],
                //'diskon' => $this->req['diskon'],
                'adjustment' => $this->req['adjustment'],
                'subtotal' => $this->req['subtotal'],
                'total' => $this->req['grandtotal'],
                'keterangan' => $this->req['keterangan'],
                'status' => 0,
            ]);


            // Input data jurnal dai header faktur
            if(count($this->req['old']) < count($this->req['id_barang'])){

                $voucer = data_voucer_jurnal::create([
                    'keterangan' => 'Perubahan Faktur #' . $faktur->nomor_faktur,
                    'status' => 1,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal']))
                ]);

            }
            


            //  PPH
            if($this->req['ppn'] > 0)
                data_jurnal::where('id_faktur', $faktur->id_faktur)->where('tipe_jurnal', 4)->update([
                    'id_faktur' => $faktur->id_faktur,
                    'id_coa' =>  $config->coa_ppn,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'deskripsi' => 'PPN ' . $this->req['ppn'] . '%',
                    'id_payment_methode' => 0,
                    'debit' => $this->req['total_ppn'],
                    'kredit' => 0,
                    'id_karyawan' => $me,
                    'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur
                ]);

            // adjustment
            if($this->req['adjustment'] > 0)
                data_jurnal::where('id_faktur', $faktur->id_faktur)->where('tipe_jurnal', 3)->update([
                    'id_faktur' => $faktur->id_faktur,
                    'id_coa' =>  $config->coa_adjustment,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'deskripsi' => 'Adjustment',
                    'id_payment_methode' => 0,
                    'debit' => $this->req['adjustment'],
                    'kredit' => 0,
                    'id_karyawan' => $me,
                    'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur
                ]);

            
            if($this->req['grandtotal'] > $grandtotal){
                $status = 1;
                $sisa = $this->req['grandtotal'] - $grandtotal;
                // Hutang Usaha
                data_jurnal::where('id_faktur', $faktur->id_faktur)->where('tipe_jurnal', 6)->update([
                    'id_faktur' => $faktur->id_faktur,
                    'id_coa' =>  $config->coa_jumlah_sebelum_dibayar,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'deskripsi' => 'Hutang Pembelian ke ' . $vendor->nm_vendor,
                    'id_payment_methode' => 0,
                    'kredit' => $this->req['grandtotal'],
                    'debit' => 0,
                    'id_karyawan' => $me,
                    'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur
                ]);


                // Pencatatan hutang vendor
                $ht = data_hutang_vendor::where('id_faktur', $faktur->id_faktur);
                $htg = $ht->first();
                if($htg->total == 0){
                    $ht->update([
                        'id_vendor' => $this->req['supplier'],
                        'status' => 1,
                        'total' => $sisa,
                        'tgl_jatuh_tempo' => date('Y-m-d', strtotime($this->req['duodate']))
                    ]);
                }else{
                    $sa = $htg->total + $sisa;
                    $ht->update([
                        'id_vendor' => $this->req['supplier'],
                        'status' => 1,
                        'total' => $sa,
                        'tgl_jatuh_tempo' => date('Y-m-d', strtotime($this->req['duodate']))
                    ]);
                }
            }


            foreach ($this->req['id_barang'] as $i => $id) {
                if(!empty($this->req['old'][$i])){
                    $item = data_faktur_item::find($this->req['id_faktur_item'][$i]);
                    $item->update([
                        'deskripsi' => $this->req['deskripsi'][$i],
                        'qty' => $this->req['qty'][$i],
                        'harga' => $this->req['harga'][$i],
                        'diskon' => $this->req['diskons'][$i],
                        'total' => $this->req['total'][$i],
                        'id_po' => $this->req['id_po'],
                        'id_satuan' => $this->req['id_satuan'][$i],
                    ]);



                    // Input Jurnal
                    $deskripsi = $id > 0 ? $this->req['nm_barang'][$i] : $this->req['deskripsi'][$i];
                    $coa = $id > 0 ? $this->req['id_coa'][$i] :  $config->coa_penambahan_item;
                    data_jurnal::where('id_faktur', $faktur->id_faktur)
                        ->where('id_option', $this->req['id_faktur_item'][$i])
                        ->update([
                            'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                            'deskripsi' => $deskripsi,
                            'id_payment_methode' => 0,
                            'debit' => $this->req['total'][$i],
                            'kredit' => 0,
                            'id_karyawan' => $me,
                            'tipe_jurnal' => 1,
                            'id_option' => $item->id_faktur_item,
                            'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur
                        ]);


                }else{

                    $item = data_faktur_item::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_item' => $id,
                        'deskripsi' => $this->req['deskripsi'][$i],
                        'qty' => $this->req['qty'][$i],
                        'harga' => $this->req['harga'][$i],
                        'diskon' => $this->req['diskons'][$i],
                        'total' => $this->req['total'][$i],
                        'id_po' => $this->req['id_po'],
                        'id_satuan' => $this->req['id_satuan'][$i],
                    ]);


                    // Input Jurnal
                    $deskripsi = $id > 0 ? $this->req['nm_barang'][$i] : $this->req['deskripsi'][$i];
                    $coa = $id > 0 ? $this->req['id_coa'][$i] :  $config->coa_penambahan_item;
                    data_jurnal::create([
                        'id_faktur' => $faktur->id_faktur,
                        'id_coa' => $coa,
                        'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                        'deskripsi' => $deskripsi,
                        'id_payment_methode' => 0,
                        'debit' => $this->req['total'][$i],
                        'kredit' => 0,
                        'id_karyawan' => $me,
                        'tipe_jurnal' => 1,
                        'id_option' => $item->id_faktur_item,
                        'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur,
                        'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                    ]);


                }

            }

            $faktur->status = $status;
            $faktur->save();

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

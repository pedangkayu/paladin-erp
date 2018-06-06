<?php

namespace App\Jobs\Akutansi\Pendapatan;

use App\Models\data_faktur;
use App\Models\data_faktur_item;
use App\Models\data_piutang_custommer;

use App\Models\data_payer;

use App\Models\data_config_coa_pendapatan as Config;
use App\Models\data_jurnal;
use App\Models\data_voucer_jurnal;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateFakturPendapatanJob extends Job implements SelfHandling
{
    public $req;

     public function __construct(array $req)
    {
        $this->req = $req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){

        //dd($this->req);

        $me = \Me::data()->id_karyawan;
        $config =  Config::active()->first();

        try{
            \DB::begintransaction();

            $faktur = data_faktur::create([
                'nomor_type'       => 0,
                'prefix'           => $this->req['prefix'],
                'type'             => 3,
                'id_vendor'        => 0,
                'id_po'            => 0,
                'id_payer'         => $this->req['customer'],
                'tgl_faktur'       => date('Y-m-d', strtotime($this->req['tanggal'])),
                'duodate'          => date('Y-m-d', strtotime($this->req['duodate'])),
                'id_payment_terms' => $this->req['terms'],
                'ppn'              => $this->req['ppn'],
                'diskon'           => $this->req['diskon'],
                'adjustment'       => $this->req['adjustment'],
                'subtotal'         => $this->req['subtotal'],
                'total'            => $this->req['grandtotal'],
                'keterangan'       => $this->req['keterangan'],
                'status'           => 0,
                'id_karyawan'      => $me,
            ]);


            /* Kode Faktur */
            $format='INV/'. date('dmY') .'/' .'RSOS/'.'';
            $no = $format . \Format::code($faktur->id_faktur);
            $faktur->nomor_faktur = $no;
            $faktur->save();

            // Input data jurnal dai header faktur
            $voucer = data_voucer_jurnal::create([
                'keterangan' => 'Penjualan barang lain #' . $no,
                'status' => 1,
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal']))
            ]);


            $customer = data_payer::find($this->req['customer']);
            // Piutang Usaha
            data_jurnal::create([
                'id_faktur' => $faktur->id_faktur,
                'id_coa' =>  $config->coa_piutang,
                'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                'deskripsi' => 'Piutang usaha ' . $customer->nm_payer,
                'id_payment_methode' => 0,
                'debit' => $this->req['grandtotal'],
                'kredit' => 0,
                'id_karyawan' => $me,
                'tipe_jurnal' => 29,
                'link_slug' => '/fakturpendapatan/edit/' . $faktur->id_faktur,
                'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
            ]);



            //  PPH
            if($this->req['total_ppn'] > 0)
                data_jurnal::create([
                    'id_faktur' => $faktur->id_faktur,
                    'id_coa' =>  $config->coa_ppn,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'deskripsi' => 'PPN ' . $this->req['ppn'] . '%',
                    'id_payment_methode' => 0,
                    'debit' => 0,
                    'kredit' => $this->req['total_ppn'],
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 4,
                    'link_slug' => '/fakturpendapatan/edit/' . $faktur->id_faktur,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);

            // adjustment
            if($this->req['adjustment'] > 0)
                data_jurnal::create([
                    'id_faktur' => $faktur->id_faktur,
                    'id_coa' =>  $config->coa_adjustment,
                    'tanggal' => date('Y-m-d', strtotime($this->req['tanggal'])),
                    'deskripsi' => 'Adjustment',
                    'id_payment_methode' => 0,
                    'debit' => 0,
                    'kredit' => $this->req['adjustment'],
                    'id_karyawan' => $me,
                    'tipe_jurnal' => 3,
                    'link_slug' => '/fakturpendapatan/edit/' . $faktur->id_faktur,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);


            data_piutang_custommer::create([
                'id_faktur' => $faktur->id_faktur,
                'id_payer' => $customer->id_payer,
                'status' => 1,
                'total' => $this->req['grandtotal'],
                'tgl_jatuh_tempo' => date('Y-m-d', strtotime($this->req['duodate']))
            ]);


            foreach ($this->req['id_barang'] as $i => $id) {
                $item = data_faktur_item::create([
                    'id_faktur' => $faktur->id_faktur,
                    'id_item' => $id,
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
                    'id_option' => $item->id_faktur_item,
                    'link_slug' => '/fakturpembelian/edit/' . $faktur->id_faktur,
                    'id_voucer_jurnal' => $voucer->id_voucer_jurnal,
                ]);


            }

            

            \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => '<center>Faktur Pendapatan berhasil dibuat dengan No. #' . $no . '</center>'
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
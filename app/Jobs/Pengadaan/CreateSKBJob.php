<?php

namespace App\Jobs\Pengadaan;

use App\Models\data_spb;
use App\Models\data_skb;
use App\Models\data_batch;
use App\Models\data_barang;
use App\Models\data_spb_item;
use App\Models\data_skb_item;
use App\Models\data_log_batch;
use App\Models\data_log_barang;
use App\Models\data_item_gudang;
use App\Models\Views\view_count_skb;


use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateSKBJob extends Job implements SelfHandling {

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
        //dd($this->req);
        $spb = data_spb::find($this->req['id_spb']);
        $status = [];
        $null = [];
        $res = [];
        try{

            $me = \Me::data()->id_karyawan;

            \DB::begintransaction();

            foreach($this->req['id_barang'] as $i => $id){
                if(($this->req['sisa'][$i] - $this->req['qty'][$i]) < 0 || $this->req['qty'][$i] < $this->req['qty_req'][$i])
                    $status[] = 1;
            }
            
            $skb = data_skb::create([
                'id_spb' => $this->req['id_spb'],
                'id_petugas' => \Me::data()->id_karyawan,
                'id_departemen' => $spb->id_departemen,
                'keterangan' => $this->req['keterangan'],
                'tipe' => $this->req['tipe']
            ]);
            
            // Log Mutasi
            foreach($this->req['id_barang'] as $i => $id){
                $qty = ( $this->req['sisa'][$i] - $this->req['qty'][$i] ) < 1 ? ($this->req['sisa'][$i] - $this->req['qty'][$i]) + $this->req['qty'][$i] : $this->req['qty'][$i];

                $convert = \Format::convertSatuan($id, $this->req['id_satuan'][$i], $this->req['id_satuan_barang'][$i]);

                if($qty > 0):
                    $sisa = $this->req['qty_req'][$i] - $qty;
                    // SKB Item
                    data_skb_item::create([
                        'id_spb_item' => $this->req['id_items'][$i],
                        'id_skb' => $skb->id_skb,
                        'id_spb' => $this->req['id_spb'],
                        'id_item' => $id,
                        'id_gudang' => $this->req['id_gudang'][$i],
                        'qty' => $qty,
                        'qty_lg' => ( $qty / $convert),
                        'keterangan' => $this->req['kets'][$i],
                        'id_satuan' => $this->req['id_satuan'][$i],
                        'sisa' => $sisa
                    ]);
                    // Out
                    data_log_barang::create([
                        'id_barang' => $id,
                        'qty' => $qty,
                        'keterangan' => 'Req. SPB No. ' . $spb->no_spb,
                        'id_gudang' => 0,
                        'kondisi' => 2, /* OUT */
                        'tipe' => 1, /* SKB */
                        'id_parent' => $skb->id_skb,
                        'id_karyawan' => $me
                    ]);

                    // Log stok ke sub gudang
                    if(!empty($this->req['id_gudang'][$i])){
                        data_log_barang::create([
                            'id_barang' => $id,
                            'qty' => $qty,
                            'keterangan' => 'Req. SPB No. ' . $spb->no_spb,
                            'id_gudang' => $this->req['id_gudang'][$i],
                            'kondisi' => 1, /* OUT */
                            'tipe' => 1, /* SKB */
                            'id_parent' => $skb->id_skb,
                            'id_karyawan' => $me
                        ]);

                        // stok gudang kecil
                        $item_gudang = data_item_gudang::firstOrCreate([
                            'id_barang' => $id,
                            'id_gudang' => $this->req['id_gudang'][$i]
                        ]);
                        $item_gudang->in = $item_gudang->in + $qty;
                        $item_gudang->keterangan = 'Req. SPB No. ' . $spb->no_spb;
                        $item_gudang->save();
                    }
                    
                    // Update Status
                    $skb_item = data_spb_item::find($this->req['id_items'][$i]);
                    if( ( $this->req['sisa'][$i] - $this->req['qty'][$i] ) >= 0 ){
                        
                        if($this->req['qty'][$i] >= $this->req['qty_req'][$i]){
                            $skb_item->status = 2;
                        }else{
                            /////////////////////////// Penjumlahan AAA //////////////////////
                            $q = $skb_item->qty;
                            $skb_item->qty = $q - $qty;

                            $sisa = $this->req['qty_lg'][$i] - ($qty / $convert);
                            $skb_item->qty_lg = $sisa;
                            /////////////////////////// End Penjumlahan AAA //////////////////////
                        }

                    }else{
                        /////////////////////////// Penjumlahan AAA //////////////////////
                        $q = $skb_item->qty;
                        $skb_item->qty = $q - $qty;

                        $sisa = $this->req['qty_lg'][$i] - ($qty / $convert);
                        $skb_item->qty_lg = $sisa;
                        /////////////////////////// End Penjumlahan AAA //////////////////////
                    }
                    $skb_item->save();
                    
                    // Stok Gudang utama
                    $barang = data_barang::find($id);
                    $barang->out = $barang->out + $qty;
                    $barang->save();
                    
                    // Jika stok tidak ditemukan
                    $null[] = 1;


                    /* BATCH */
                    $batchs = data_batch::transaksi($id)->get();
                    if(count($batchs) > 0){
                        $total_req = $qty;
                        foreach($batchs as $batch){
                            $sisa_batch = ($batch->in - $batch->out);
                            if($total_req >= 0){

                                $res_total = $total_req > $sisa_batch ? $sisa_batch : $total_req;
                                $res[] = $res_total;
                                /* Log Batch */
                                data_log_batch::create([
                                    'id_batch' => $batch->id_batch,
                                    'id_barang' => $id,
                                    'qty_in' => $res_total,
                                    'qty_out' => 0,
                                    'keterangan' => 'Req. SPB No. ' . $spb->no_spb,
                                    'id_gudang' => $this->req['id_gudang'][$i],
                                    'tipe' => 1,
                                    'id_parent' => $skb->id_skb,
                                    'id_karyawan' => $me
                                ]);

                                /* Pengurangan Bach */
                                $ubatch = data_batch::find($batch->id_batch);
                                $ubatch->out = $ubatch->out + $res_total;
                                /* Update Status */
                                if(($ubatch->in - ($ubatch->out + $res_total)) < 1)
                                    $ubatch->status = 2;

                                $ubatch->save();

                            }

                            $total_req -= $sisa_batch;
                        }
                    }
                    /* END BATCH */

                endif;
                
            }
            

            if(date('Y') > 2015):
                // KODE
                $kode = view_count_skb::where('tahun', date('Y'))->first();
                $urut = empty($kode->jumlah) ? 1 : $kode->jumlah;
                $skb->no_skb = 'SKB/' . date('Y') . '/' . \Format::code($urut);
                $skb->save();
            else:
                // KODE
                $kode = view_count_skb::where('tahun', date('Y'))->first();
                $urut = empty($kode->jumlah) ? 1 : $kode->jumlah;
                $skb->no_skb = 'SKB-' . \Format::code($urut);
                $skb->save();
            endif;

            if(count($status) > 0)
                $spb->status = 2;
            else
                 $spb->status = 3;
            $spb->save();

            //dd($status);

            // Jika stok tidak ditemukan
            if(count($null) < 1){
                throw new \Exception("Maaf, Saat ini stok tidak mencukupi!", 1);
                
            }

            
            \Loguser::create('Membuat Surat Keluar Barang No. ' . $skb->no_skb);

            \DB::commit();
            return [
                'label' => 'success',
                'result' => true,
                'id' => $skb->id_skb,
                'err' => 'PMB/PMO berhasil di proses dengan No. ' . $skb->no_skb . ' <a href="' . url('/skb/view/' . $skb->id_skb ) . '" class="btn btn-warning btn-mini pull-right">Lihat detail</a>'
            ];


        }catch(\Exception $e){
            \DB::rollback();
            return [
                'label' => 'danger',
                'result' => false,
                'err' => $e->getMessage()
            ];
        }

    }
}


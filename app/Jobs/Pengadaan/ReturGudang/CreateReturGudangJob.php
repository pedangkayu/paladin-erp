<?php

namespace App\Jobs\Pengadaan\ReturGudang;

use App\Models\data_retur;
use App\Models\data_barang;
use App\Models\data_log_barang;
use App\Models\data_retur_item;
use App\Models\data_item_gudang;
use App\Models\data_skb_item;
use App\Models\data_skb;
use App\Models\Views\view_count_data_retur_internal;

use App\Models\data_batch;
use App\Models\data_log_batch;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateReturGudangJob extends Job implements SelfHandling {

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
        
        try{
            //dd($this->req);
            \DB::begintransaction();

                $me = \Me::data()->id_karyawan;
                $gd = \Me::subgudang();

                if(array_sum($this->req['qty']) == 0)
                    throw new \Exception("Qty tidak boleh semuanya kosong!", 1);
                    

                $retur = data_retur::create([
                    'tipe' => 1,
                    'id_gudang_asal' => $gd->id_gudang,
                    'id_karyawan' => $me
                ]);

                foreach($this->req['id_barang'] as $i => $id_barang){

                    if(!empty($this->req['qty'][$i])):

                        data_retur_item::create([
                            'id_retur' => $retur->id_retur,
                            'id_barang' => $id_barang,
                            'id_satuan' => $this->req['id_satuan'][$i],
                            'qty' => $this->req['qty'][$i],
                            'qty_lg' => $this->req['qty'][$i],
                        ]);

                        // Log Stok
                        data_log_barang::create([
                            'id_barang' => $id_barang,
                            'qty' => $this->req['qty'][$i],
                            'id_gudang' => 0,
                            'kondisi' => 1,
                            'tipe' => 4,
                            'id_parent' => $retur->id_retur,
                            'id_karyawan' => $me
                        ]);

                        // Log Stok
                        data_log_barang::create([
                            'id_barang' => $id_barang,
                            'qty' => $this->req['qty'][$i],
                            'id_gudang' => $this->req['id_gudang'][$i],
                            'kondisi' => 2,
                            'tipe' => 4,
                            'id_parent' => $retur->id_retur,
                            'id_karyawan' => $me
                        ]);

                        // pengutangan dari data skb
                        $skb_item = data_skb_item::find($this->req['id_skb_item'][$i]);
                        $skb_item->qty = $skb_item->qty - $this->req['qty'][$i];
                        $skb_item->status = ($skb_item->qty - $this->req['qty'][$i]) < 1 ? 0 : 1;
                        $skb_item->save();

                        // Update stok Gudang besat
                        $barang = data_barang::find($id_barang);
                        $barang->in = $barang->in + $this->req['qty'][$i];
                        $barang->save();

                        // Update Stok sub gudang
                        $gudang = data_item_gudang::where('id_barang', $id_barang)
                            ->where('id_gudang', $this->req['id_gudang'][$i])
                            ->first();
                        
                        //dd($gudang);

                        $gd = data_item_gudang::find($gudang->id_item_gudang);
                        $gd->out = $gudang->out + $this->req['qty'][$i];
                        $gd->save();


                        /* BATCH */
                        $batchs = data_batch::transaksi($id_barang)->get();
                        if(count($batchs) > 0){
                            $total_req = $this->req['qty'][$i];
                            foreach($batchs as $batch){
                                $sisa_batch = ($batch->in - $batch->out);
                                if($total_req >= 0){

                                    $res_total = $total_req > $sisa_batch ? $sisa_batch : $total_req;
                                    $res[] = $res_total;
                                    /* Log Batch */
                                    data_log_batch::create([
                                        'id_batch' => $batch->id_batch,
                                        'id_barang' => $id_barang,
                                        'qty_in' => 0,
                                        'qty_out' => $res_total,
                                        'keterangan' => 'Retur Gudang',
                                        'id_gudang' => $this->req['id_gudang'][$i],
                                        'tipe' => 2,
                                        'id_parent' => $retur->id_retur,
                                        'id_karyawan' => $me
                                    ]);

                                    /* Pengurangan Bach */
                                    $ubatch = data_batch::find($batch->id_batch);
                                    $ubatch->in = $ubatch->in + $res_total;
                                    /* Update Status */
                                    if((($ubatch->in + $res_total) - $ubatch->out) > 0)
                                        $ubatch->status = 1;

                                    $ubatch->save();

                                }

                                $total_req -= $sisa_batch;
                            }
                        }
                        /* END BATCH */

                    endif;
                }  



                $data = view_count_data_retur_internal::where('tahun', date('Y'))->first();
                $urut = empty($data->jumlah) ? 1 : $data->jumlah;
                $retur->no_retur = 'IN-RTN/' . date('Y') . '/' . \Format::code($urut);
                $retur->save();

                if(array_sum($this->req['qty']) >= $this->req['total']){
                    data_skb::find($this->req['id_skb'])->update([
                        'status' => 0
                    ]);
                }
               
                \Loguser::create('Membuat Retur Gudang dengan No. ' . $retur->no_retur);

            \DB::commit();
                
            return [
                'result' => true,
                'label' => 'success',
                'err' => 'Return Gudang berhasil dibuat dengan No. ' . $retur->no_retur
            ];

        }catch(\Exception $e){
            \DB::rollback();            
            return [
                'result' => false,
                'label' => 'danger',
                'err' => $e->getMessage()
            ];
        }

    }
}
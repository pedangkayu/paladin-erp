<?php

namespace App\Jobs\Pengadaan;

use App\Models\data_mutasi_spb;
use App\Models\data_mutasi_spb_item;
use App\Models\data_mutasi_skb;
use App\Models\data_mutasi_skb_item;
use App\Models\data_log_barang;
use App\Models\data_item_gudang;

use App\Models\Views\view_count_smb;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateSmbJob extends Job implements SelfHandling
{
    public $req;

    public function __construct(array $req) {
        $this->req = $req;
    }

    public function handle()
    {
  // dd($this->req);
      $spbm = data_mutasi_spb::find($this->req['id_mutasi_spb']);
        $status = [];
        $null = [];
        $res = [];

            try{

            $me = \Me::data()->id_karyawan;

            \DB::begintransaction();
                 $smb=data_mutasi_skb::create([
                    'id_mutasi_spb' =>$this->req['id_mutasi_spb'],
                    'id_petugas' => \Me::data()->id_karyawan,
                    'id_departemen' =>$spbm->id_departemen,
                    'id_unit_tujuan'=>$this->req['id_unit_tujuan'],
                    'id_unit_asal'  =>$this->req['id_unit_asal'],
                    'keterangan'    =>$this->req['keterangan'],
                        ])   ;

                 foreach ($this->req['id_barang'] as $i =>$id) {
                   $is_cek = data_item_gudang::all();
                   if($this->req['id_unit_item'][$i]==$this->req['id_unit_asal']){
                    $qty = ($this->req['qty_acc'][$i]);
                         if($qty > 0):
                         data_mutasi_skb_item::create([
                               'id_mutasi_spb_item' =>$this->req['id_mutasi_spb_item'][$i],
                               'id_mutasi_skb'      =>$smb->id_mutasi_skb,
                               'id_mutasi_spb'      =>$this->req['id_mutasi_spb'],
                               'id_item'            =>$id,
                               'id_gudang'          =>$this->req['id_unit_item'][$i],
                               'qty'                =>$this->req['qty_acc'][$i], //qty di acc
                                'qty_awal'           =>$this->req['qty_awal'][$i],
                               'id_satuan'          =>$this->req['id_satuan'][$i],
                               'status'             =>1,
                               'sisa'               =>$this->req['sisa'][$i],
                            ]);
                         // mutasi barang
                           data_log_barang::create([
                            'id_barang' => $id,
                            'qty' => $this->req['qty_acc'][$i],
                            'keterangan' => 'Req. SMB No. ' . $spbm->no_mutasi_spb,
                            'id_gudang' => $this->req['id_unit_tujuan'],
                            'kondisi' => 2, /* OUT */
                            'tipe' => 8, /* SKB */
                            'qty' => $this->req['qty_acc'][$i],
                            'id_parent' => $smb->id_mutasi_skb,
                            'id_karyawan' => $me,
                        ]); 
                             $item_gudang = data_item_gudang::find($this->req['id_item_gudang'][$i]);
                              $item_gudang->out = $item_gudang->out + $this->req['qty_acc'][$i];
                              $item_gudang->save();
                               
                          // Log stok ke sub gudang
                        if(!empty($this->req['id_unit_item'][$i])){
                            data_log_barang::create([
                                'id_barang' => $id,
                                'qty' => $this->req['qty_acc'][$i],
                               'keterangan' => 'Terima. SMB No. ' . $spbm->no_mutasi_spb,
                                'id_gudang' => $this->req['id_unit_item'][$i],
                                'kondisi' => 1, /* IN */
                                'tipe' => 8, /* SKB */
                                'id_parent' => $smb->id_mutasi_skb,
                                'id_karyawan' => $me
                            ]);

                            // stok gudang kecil
                            $item_g = data_item_gudang::firstOrCreate([
                                'id_barang' => $id,
                                'id_gudang' => $this->req['id_unit_item'][$i]
                            ]);
                            $item_g->in = $item_g->in + $this->req['qty_acc'][$i];
                            $item_g->save();
                        }  

                        endif;
                      }

                    $skb_item = data_mutasi_spb_item::find($this->req['id_mutasi_spb_item'][$i]);
                    $skb_item->status = 2;
                    $skb_item->qty=$this->req['qty_acc'][$i];
                     $skb_item->save();
                 }
                    $kode = view_count_smb::where('tahun', date('Y'))->first();
                    $urut = empty($kode->jumlah) ? 1 : $kode->jumlah;
                    $smb->no_mutasi_skb = 'SMB/' . date('Y') . '/' . \Format::code($urut);
                    $smb->save();

                $spbm->status =5;
                $spbm->save();

                 \Loguser::create('Membuat Surat Mutasi BarangNO. ' . $smb->no_mutasi_skb);

                  \DB::commit();
            return [
                'label' => 'success',
                'result' => true,
                'id' => $smb->id_mutasi_skb,
                'err'  =>'Permohonan Mutasi Barang Berhasil di proses dengan No.'.$smb->no_mutasi_skb.''
                
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

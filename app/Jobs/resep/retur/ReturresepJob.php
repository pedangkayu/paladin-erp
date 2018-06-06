<?php

namespace App\Jobs\resep\retur;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_resep;
use App\Models\data_resep_item;
use App\Models\data_retur_resep;
use App\Models\data_retur_resep_item;
use App\Models\views\view_retur_resep;
use App\Models\data_log_barang;
use App\Models\data_item_gudang;

class ReturresepJob extends Job implements SelfHandling
{
    public $req;
    
    public function __construct(array $req)
    {
        $this->req= $req;
    }

   
    public function handle()
    {
        // dd($this->req);
        $me = \Me::data()->id_karyawan;
        try{
             \DB::begintransaction();
                 $resep = data_resep::find($this->req['id_resep']);
                     $retur = data_retur_resep::create([
                        'id_resep'       =>$resep->id_resep,
                        // 'no_retur_resep' =>$this->reid_gudangq[''],
                        'id_gudang'      =>$this->req['id_gudang'],
                        'tanggal_retur'  => date('Y-m-d', strtotime($this->req['tgl_retur'])),
                        'id_karyawan'    =>$me ,
                        'id_acc_retur'   =>0,
                        // 'alasan'         =>$this->req[''],
                        'id_pembeli'     =>$resep->id_pasien, 
                        'status'         =>1, 
                    ]);
                            foreach($this->req['id_resep_item']as $a => $b){

                              if(!empty($this->req['qty_retur'][$a])){
                                 $this->req['sisa'][$a]=(($this->req['jumlah_out'][$a]) - ($this->req['qty_retur'][$a]));
                                   $paten=data_resep_item::find($this->req['id_resep_item'][$a]);
                                   $paten->update([
                                     'id_resep'        => $resep->id_resep,
                                     'id_barang'       =>$this->req['id_barang'][$a],
                                     'qty'             =>$this->req['sisa'][$a],
                                     'qty_retur'       =>$this->req['qty_retur'][$a],
                                     'id_satuan'       =>$this->req['id_satuan'][$a],
                                     'qty_awal'        =>$this->req['jumlah_out'][$a],
                                     'id_item_gudang'  =>$this->req['id_item_gudang'][$a],
                                     'harga_jual'      =>$this->req['harga_jual'][$a],
                                     'keterangan'      =>$this->req['keterangan'][$a],
                                    ]);

                                   $returitem=data_retur_resep_item::create([
                                        'id_retur_resep' =>$retur->id_retur_resep,
                                        'id_resep_item'  =>$paten->id_resep_item,
                                        'id_barang'      =>$this->req['id_barang'][$a],
                                        'id_satuan'      =>$this->req['id_satuan'][$a],
                                        'qty_awal'       =>$this->req['jumlah_out'][$a],
                                        'qty_akhir'      =>$this->req['sisa'][$a],
                                        'qty_retur'      =>$this->req['qty_retur'][$a],
                                        'id_item_gudang' =>$this->req['id_item_gudang'][$a],
                                        'status'         =>1
                                    ]);

                                   $kondisi = $resep->status_resep==1 ? 1 : 2;
                                   if($kondisi==1){

                                        data_log_barang::create([
                                            'id_barang' => $this->req['id_barang'][$a],
                                            'qty' =>$this->req['qty_retur'][$a],
                                            'keterangan' => 'retur barang ',
                                            'id_gudang' =>$this->req['id_gudang'],
                                            'kondisi' => 4,
                                            'tipe' => 9,
                                            'id_parent' => $retur->id_retur_resep,
                                            'id_karyawan' => $me,
                                            ]);
                                                $barang = data_item_gudang::find($this->req['id_item_gudang'][$a]);
                                                 $barang->in = $barang->in + $this->req['qty_retur'][$a];
                                                $barang->save();
                                     }
                                    
                                    
                                }
                         }
                            $data = view_retur_resep::where('tahun', date('Y'))->first();
                            $urut = empty($data->jumlah) ? 1 : $data->jumlah;
                            $retur->no_retur_resep = 'SRO-' . \Format::code($urut);
                            $retur->save();
                              \Loguser::create('Melakukan Retur Obat. ' . $retur->no_retur_resep);
             \DB::commit();
             return [
                    'res' =>true,
                    'label'=>'success',
                    'err' =>' Retur Barang Berhasil No# ' . $retur->no_retur_resep,
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

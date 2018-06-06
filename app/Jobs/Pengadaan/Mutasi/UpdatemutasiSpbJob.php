<?php

namespace App\Jobs\Pengadaan\Mutasi;

use App\Models\data_mutasi_spb;
use App\Models\data_mutasi_spb_item;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class UpdatemutasiSpbJob extends Job implements SelfHandling
{
    public $req;

    public function __construct(array $req){
        $this->req = $req;
    }


    public function handle()
    {
        //dd($this->req);
         try{
            \DB::begintransaction();

         $mutasi=data_mutasi_spb::find($this->req['id_mutasi_spb'])
            ->update([
                    'id_unit_tujuan' =>$this->req['id_gudang_tujuan'],
                    'id_unit_asal'   =>$this->req['id_unit'],
                    'keterangan'     =>$this->req['ket'],
                    'status'         =>1,
                ]);
         foreach($this->req['id_mutasi_spb_item'] as $i => $id){
                if(!empty($this->req['qty'][$i])){
                  $aa=data_mutasi_spb_item::find($this->req['id_mutasi_spb_item'][$i])->update([
                        'id_item'        => $this->req['id_barang'][$i],
                        'qty_awal'       => $this->req['qty'][$i], //jumlah yng diminta
                        'id_unit' =>$this->req['id_gudang'][$i],
                        'status'         => 1,
                        'id_satuan'      => $this->req['id_satuan'][$i],
                    ]);
                }
                    
            }
        if(!empty($this->req['qty_item']) && ($this->req['id_barang_item'])){
            foreach($this->req['id_barang_item'] as $a => $d){
                       $k= data_mutasi_spb_item::create([
                        'id_mutasi_spb'  => $this->req['id_mutasi_spb'],
                        'id_item'        => $d,
                        'qty_awal'       => $this->req['qty_item'][$a], //jumlah yng diminta
                        'id_unit' =>$this->req['id_unit'],
                        'status'         => 1,
                        'id_satuan'      => $this->req['id_satuan_item'][$a],
                    ]);
                }
                    
            }
              \DB::commit();
                            return [
                                'res' => true,
                                'label' => 'success',
                                'err' => 'perbaruan data berhasil. '
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

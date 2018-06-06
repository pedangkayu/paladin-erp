<?php

namespace App\Jobs\Resep;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_resep;
use App\Models\data_resep_item;
use App\Models\data_resep_campur;


class UpdateResepJob extends Job implements SelfHandling
{
    public $req;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $req)
    {
        $this->req = $req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
       // dd($this->req);

        try{
            \DB::begintransaction();

        $resep = data_resep::find($this->req['id_resep']);
        $resep->update([
                'status_resep'      =>0
                ]);
          if(!empty($this->req['id_resep_item']) && ($this->req['id_resep_item']) > 0){
                foreach ($this->req['id_resep_item'] as $a => $b) {
                   $paten=data_resep_item::find($this->req['id_resep_item'][$a]);
                   $paten->update([
                     'id_resep'        => $resep->id_resep,
                     'id_barang'       =>$this->req['id_barang'][$a],
                     'total'           =>$this->req['total'][$a],
                     'qty'             => $this->req['jumlah_out'][$a],
                     'id_satuan'       => $this->req['id_satuan'][$a],
                     'id_resep_aturan' => $this->req['id_resep_aturan'][$a],
                     'id_item_gudang'  => $this->req['id_item_gudang'][$a],
                     'harga_jual'      => $this->req['harga_jual'][$a],
                     'keterangan'      => $this->req['keterangan'][$a],
                    ]);
                    ////campur
                   if(!empty($this->req['id_barang_c'][$b]) && count($this->req['id_barang_c'][$b]) >0){
                        foreach ($this->req['id_barang_c'][$b] as $obt =>$bar) {
                            $cam=data_resep_campur::create([
                                'qty'              => $this->req['jumlah_out_c'][$b][$obt],
                                'id_resep_item'    => $paten->id_resep_item,
                                'id_barang'        => $bar,
                                'harga_jual'       =>$this->req['harga_jual_c'][$b][$obt],
                                'id_resep'         => 0,
                                'id_satuan_campur' =>$this->req['id_satuan_c'][$b][$obt],
                                'id_item_gudang'   => $this->req['id_item_gudang_c'][$b][$obt],
                             ]);
                            # code...
                        }
                   }

                }
     
             }
                ////obat paten
                if(!empty($this->req['id_barang_p']) && count($this->req['id_barang_p']) > 0){
                    foreach ($this->req['id_barang_p'] as $i => $id) {
                            data_resep_item::create([
                                    'id_resep'          => $resep->id_resep,
                                    'id_barang'         =>$id,
                                    'qty'               => $this->req['jumlah_out_p'][$i],
                                    'id_satuan'         => $this->req['id_satuan_p'][$i],
                                    'id_resep_aturan'   => $this->req['id_resep_aturan_p'][$i],
                                    'id_item_gudang'    =>  $this->req['id_item_gudang_P'][$i],
                                    'harga_jual'        => $this->req['harga_jual_p'][$i],
                                    // 'total'             =>$this->req['total'][$i],
                                    'status_item_resep' => 1,
                                    'id_treatment_item' =>0,
                                    'dihapus_pada'      =>0,
                                    'id_klasifikasi'    =>0,
                                    'tipe'              =>0,
                                    'status'            =>3,

                                    'status_obat'       =>1,

                            ]);
                    }
                }
                    if(!empty($this->req['id_resep_campur'])):
                        foreach ($this->req['id_resep_campur'] as $c => $d) {
                            data_resep_campur::find($this->req['id_resep_campur'][$c])->update([
                                'qty'              =>$this->req['akhir_campur'][$c],
                                'id_resep_item'    =>$this->req['id_resep_item_campur'][$c],
                                'id_barang'        =>$this->req['id_barang_campur'][$c],
                                'harga_jual'       =>$this->req['jual'][$c],
                                'id_satuan_campur' =>$this->req['id_satuan_campur'][$c],
                                'id_item_gudang'   =>$this->req['item_gud'][$c],
                                ]);
                            # code...
                        }
                    endif;


              \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => '<center>Peroses Resep Obat selesai</center>'
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

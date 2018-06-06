<?php

namespace App\Jobs\Treatment;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

use App\Models\data_treatment_item;
use App\Models\data_treatment_dokter;
use App\Models\data_treatment;
use App\Models\data_resep_item;
use App\Models\data_item_gudang;
use App\Models\data_log_barang;
use App\Models\data_log_stockout;


class UpdatetrTransaksijasaJob extends Job implements SelfHandling
{
    public $req;

    public function __construct(array $req){
        $this->req = $req;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
 //dd($this->req);
         $me = \Me::data()->id_karyawan;
          $gudang= \Me::subgudang()->id_gudang;
          
          try{
            \DB::begintransaction();

               if(!empty($this->req['id_treatment_dokter']) && ($this->req['id_treatment_dokter']) > 0){
                foreach ($this->req['id_treatment_dokter'] as $kkkk => $idii) {
                  data_treatment_dokter::find($this->req['id_treatment_dokter'][$kkkk])->update([
                   'id_dokter' =>$this->req['id_dokter'][$kkkk],
                            ]);
                      }
                  }
                 // ====================id_bhp============================//
            if(!empty($this->req['id_resep_item'])):
              foreach ($this->req['id_resep_item'] as $d => $a):

                  if(!empty($this->req['id_resep_item'][$d]) && ($this->req['qty'][$d] > $this->req['kurang'][$d])){
                      //qty lebih besar dari kurang
                     $this->req['sisa'][$d]=(($this->req['qty'][$d]) - ($this->req['kurang'][$d]));

                   }else{
                      /// lbh kecil
                     $this->req['sisa'][$d]=(($this->req['kurang'][$d])-($this->req['qty'][$d]));
                   }

                $this->req['total'][$d] =(($this->req['kurang'][$d]) * ($this->req['harga_jual'][$d]));
                     //=======menghitung grandtotal bhp
                     // ===============cek yang qty=0 tidak d proses=========================//
                             if(!empty ($this->req['id_resep_item'][$d]) && ($this->req['kurang'] [$d] > 0)){
                                data_resep_item::find($this->req['id_resep_item'][$d])->update([
                                  'id_barang'         =>$this->req['id_barang'][$d],
                                  'id_item_gudang'    =>$this->req['id_item_gudang'][$d],
                                  'harga_jual'        =>$this->req['harga_jual'][$d],
                                  'id_satuan'         =>$this->req['id_satuan'][$d],
                                  'total'             =>$this->req['total'][$d],
                                  'id_treatment_item' =>$this->req['id_treatment_itembhp'][$d],
                                  'qty'               =>  $this->req['kurang'][$d],
                                ]);
                            
                              }else{
                                 data_resep_item::whereId_resep_item($this->req['id_resep_item'][$d])->delete();
                                 // $kosong = data_resep_item::find($this->req['id_resep_item'][$d])->delete();
                                     // hpus data bhp dari data_resep_item jika qty==0
                              }
                // =============================================================================== =============
                            if(!empty($this->req['id_gudang'][$d]) &&  ($this->req['reuse'][$d]) < 1): ///====aaaaa
                                   if(!empty($this->req['id_gudang'][$d]) && ($this->req['qty'][$d] > $this->req['kurang'][$d])){
                                          data_log_barang::create([
                                              'id_barang' => $this->req['id_barang'][$d],
                                              'qty' => $this->req['kurang'][$d],
                                              'keterangan' => 'Refund BHP oleh pasien sebanyak. '.$this->req['sisa'][$d]. '--qty awal--'.$this->req['qty'][$d],
                                              'id_gudang' => $this->req['id_gudang'][$d],
                                              'kondisi' => 4, /* refound*/
                                              'tipe' => 7,  //Resep Pasien
                                              'id_parent' => $this->req['id_treatment'],
                                              'id_karyawan' => $me,
                                          ]);

                                          $item_gudang = data_item_gudang::find($this->req['id_item_gudang'][$d]);
                                          $item_gudang->in = $item_gudang->in + $this->req['sisa'][$d];
                                          $item_gudang->save();

                                      }elseif(!empty($this->req['id_gudang'][$d]) && ($this->req['qty'][$d] < $this->req['kurang'][$d])){
                                         data_log_barang::create([
                                              'id_barang' => $this->req['id_barang'][$d],
                                              'qty' => $this->req['kurang'][$d],
                                              'keterangan' => 'tambah qty BHP oleh pasien sebanyak. '.$this->req['sisa'][$d]. '--qty awal--'.$this->req['qty'][$d],
                                              'id_gudang' => $this->req['id_gudang'][$d],
                                              'kondisi' => 4, /* tambah BHP*/
                                              'tipe' => 7,  //Resep Pasien
                                              'id_parent' => $this->req['id_treatment'],
                                              'id_karyawan' => $me,
                                          ]);

                                          $item_gudang = data_item_gudang::find($this->req['id_item_gudang'][$d]);
                                          $item_gudang->out = $item_gudang->out + $this->req['sisa'][$d];
                                          $item_gudang->save();
                                      }
                                  endif; ///========aaaaa====

                      endforeach ;
                     endif;

           // =======atas================//
            if(!empty($this->req['id_treatment_item']) && ($this->req['id_treatment_item']) > 0){
                 foreach ($this->req['id_treatment_item'] as $is => $sd) {
                     $item = data_treatment_item::find($this->req['id_treatment_item'][$is]);
                     $item->update([
                    
                    'tarif_dasar'  => $this->req['tarif_das'][$is],
                    'id_treatment' => $this->req['id_treatment'],
                    'id_service'   =>$this->req['id_service1'][$is],
                    'service_kode'  =>$this->req['service_kod'][$is],

                    ]);
                    
                  // ==================== batas update============================//
                   //   ========================untuk nambah jasa=================//
                 if(!empty($this->req['id_barang_item'][$sd]) && count($this->req['id_barang_item'][$sd]) > 0):
                    foreach ($this->req['id_barang_item'][$sd] as $ti => $barang): 
                        data_resep_item::create([
                          'id_treatment_item' =>$item->id_treatment_item,
                            'id_barang'         =>$barang,
                            'id_item_gudang'    =>$this->req['id_item_gudang_item'][$sd][$ti],
                            'harga_jual'        =>$this->req['harga_jualitem'][$sd][$ti],
                            'id_klasifikasi'    =>0,
                            'id_satuan'         =>$this->req['id_satuanitem'][$sd][$ti],
                            'tipe'              =>$this->req['tipeitem'][$sd][$ti],
                            'id_resep_aturan'   =>0,
                            'status_resep_item' =>1,
                            'status_obat'       =>3,
                            'reuse'             =>$this->req['pakek'][$sd][$ti],
                            'dihapus_pada'      =>0,
                            'keterangan'        =>0,
                            'status'            =>3,
                            ///'total'             =>$this->req['total'][$id][$t],
                            'qty'               => $this->req['jumlah_out_item'][$sd][$ti],

                        ]);
                        if(!empty($this->req['id_item_gudang_item'][$sd][$ti]) && ($this->req['pakek'][$sd][$ti]) < 1){
                          //============jika bhp reuse tidak motong stok==============///
                            $item_gud = data_item_gudang::find($this->req['id_item_gudang_item'][$sd][$ti]);
                            $item_gud->out = $item_gud->out + $this->req['jumlah_out_item'][$sd][$ti];
                            $item_gud->save();
                        }
                  

                        if(!empty($this->req['id_barang_item'][$sd][$ti])){
                            data_log_barang::create([
                                'id_barang'   => $this->req['id_barang_item'][$sd][$ti],
                                'qty'         => $this->req['jumlah_out_item'][$sd][$ti],
                                'keterangan'  => 'Treatment. ',
                                'id_gudang'   => $gudang,
                                'kondisi'     => 1, /* OUT */
                                'tipe'        => 7,  //BHP
                                'id_parent'   => $this->req['id_treatment'],
                                'id_karyawan' => $me
                            ]);
                          }
                  endforeach; // end item bhp 
                endif; 


                if(!empty($this->req['service_kodei'][$sd]) ){
                   foreach ($this->req['service_kodei'][$sd] as $n => $m) {
                        $tambah = data_treatment_item::create([
                        'id_treatment' => $this->req['id_treatment'],
                        'id_service'   =>$this->req['id_service'][$sd][$n],
                        'tipe'         => $this->req['tipeseri'][$sd][$n],
                        'service_kode'  =>$m,
                        'status'       => $this->req['statusi'][$sd][$n],
                        'tarif_dasar'  => $this->req['tarif_dasari'][$sd][$n],

                        ]);
                    
                        if(!empty($this->req['id_dr'][$m])  ):
                          foreach ($this->req['id_dr'][$m] as $ka => $ada):
                            data_treatment_dokter::create([
                           'id_treatment_item' =>$tambah->id_treatment_item,
                            'id_dokter' =>$ada,
                            'jabatan' =>$this->req['jabatani'][$m][$ka],
                              ]);
                          endforeach ;
                       endif;
                
                
                  
                 // insert dalam tabke log barang
                 }  
               
              }

        }
      }
      // tambah paket
                  // i==a id=ab 
              if(!empty($this->req['service_kode_paket']) && count($this->req['service_kode_paket']) > 0){
                  foreach ($this->req['service_kode_paket'] as $a => $ab) {
                      $item_paket = data_treatment_item::create([
                      'id_treatment' => $this->req['id_treatment'],
                      'id_service'   =>$this->req['id_service_paket'][$a],
                      'tipe'         => $this->req['tipeser_paket'][$a],
                      'service_kode'  =>$ab,
                      'status'       => $this->req['status_paket'][$a],
                      'tarif_dasar'  => $this->req['tarif_dasar_paket'][$a],

                      ]);
                    
                          if(!empty($this->req['id_dr_paket'][$ab])): //k=c ad=ac
                                foreach ($this->req['id_dr_paket'][$ab] as $c => $ac):
                                  data_treatment_dokter::create([
                                    'id_dokter' =>$ac,
                                 'id_treatment_item' =>$item_paket->id_treatment_item,
                                  
                                  'jabatan' =>$this->req['jabatan_paket'][$ab][$c],
                                    ]);
                                endforeach ;
                         endif; //$t=cd
                   if(!empty($this->req['jumlah_out_paket'][$ab]) && ($this->req['jumlah_out_paket'][$ab]) > 0):
                        foreach ($this->req['jumlah_out_paket'][$ab] as $cd => $jumlah_out ): 
                            $obt_paket=data_resep_item::create([
                                'id_barang'         =>$this->req['id_barang_item_paket'][$ab][$cd],
                                'id_item_gudang'    =>$this->req['id_item_gudang_paket'][$ab][$cd],
                                'harga_jual'        =>$this->req['harga_jual_paket'][$ab][$cd],
                                'id_klasifikasi'    =>0,
                                'id_satuan'         =>$this->req['id_satuan_paket'][$ab][$cd],
                                'tipe'              =>$this->req['tipe_paket'][$ab][$cd],
                                'id_resep_aturan'   =>0,
                                'status_resep_item' =>1,
                                'status_obat'       =>3,
                                'dihapus_pada'      =>0,
                                'keterangan'        =>0,
                                'status'            =>3,
                                'flat'            =>$this->req['flat'],
                                'reuse'             =>$this->req['pakek_paket'][$ab][$cd],
                                'total'             =>0,
                                'id_treatment_item' =>$item_paket->id_treatment_item,
                                'qty'               => $jumlah_out,
                            ]);


                            if( ($this->req['jumlah_out_paket'][$ab][$cd]) > ($this->req['stok_paket'][$ab][$cd])  && ($this->req['pakek_paket'][$ab][$cd]) < 1){

                              $this->req['stok_akhir'][$ab][$cd]=(($this->req['stok_paket'][$ab][$cd]) - ($this->req['jumlah_out_paket'][$ab][$cd])); 

                                  if(($this->req['stok_paket'][$ab][$cd]) < 1){

                                      $this->req['hutang_now'][$ab][$cd]=(($this->req['stok_paket'][$ab][$cd]) - $this->req['stok_akhir'][$ab][$cd]);

                                  }else{
                                    
                                     $this->req['hutang_now'][$ab][$cd]=(($this->req['jumlah_out'][$ab][$cd]) - ($this->req['stok'][$ab][$cd]));
                                  }
                                        data_log_stockout::create([
                                          'id_barang'      =>$this->req['id_barang_item_paket'][$ab][$cd],
                                          'id_gudang'      =>$this->req['unit_jasa'],
                                          'id_item_gudang' =>$this->req['id_item_gudang_paket'][$ab][$cd],
                                          'id_treatment_item'  =>$item_paket->id_treatment_item,
                                          'id_resep_item'   =>$obt_paket->id_resep_item,
                                          'req_qty'        =>$this->req['jumlah_out_paket'][$ab][$cd],
                                          'stok'           =>$this->req['stok_akhir'][$ab][$cd],
                                          'hutang'         =>$this->req['hutang_now'][$ab][$cd],
                                          'id_karyawan'    => $me,
                                          'status'        =>1,
                                          ]);                
                            }else{
                               
                            }

                            if(!empty($this->req['id_item_gudang_paket'][$ab][$cd]) && ($this->req['pakek_paket'][$ab][$cd]) < 1){
                                   $item_gudang = data_item_gudang::find($this->req['id_item_gudang_paket'][$ab][$cd]);
                                  $item_gudang->out = $item_gudang->out + $this->req['jumlah_out_paket'][$ab][$cd];
                                  $item_gudang->save();
                           
                            }

                            if(!empty($this->req['id_barang_item_paket'][$ab][$cd])){
                                  data_log_barang::create([
                                      'id_barang'   => $this->req['id_barang_item_paket'][$ab][$cd],
                                      'qty'         => $this->req['jumlah_out_paket'][$ab][$cd],
                                      'keterangan'  => 'Treatment. ',
                                      'id_gudang'   => $this->req['unit_jasa'],
                                      'kondisi'     => 1, /* OUT */
                                      'tipe'        => 7,  //BHP
                                      'id_parent'   => $this->req['id_treatment'],
                                      'id_karyawan' => $me
                                  ]);

                             }
                      endforeach; // end item bhp 
                   endif;
                 // insert dalam tabke log barang
                  }  
               
              }
// ==========bts atas===============
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

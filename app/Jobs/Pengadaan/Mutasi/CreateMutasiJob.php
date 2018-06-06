<?php

namespace App\Jobs\Pengadaan\Mutasi;

//MOdel
use App\Models\data_mutasi_spb;
use App\Models\data_mutasi_spb_item;
use App\Models\Views\view_count_mutasi_item;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateMutasiJob extends Job implements SelfHandling
{
   public $req;

    public function __construct(array $req)
    {
        $this->req = $req;
    }

    public function handle()
    {
        // dd($this->req);
        try{
         
             \DB::begintransaction();
            $mutasi=data_mutasi_spb::create([
                    'id_departemen'  =>\Me::data()->id_departemen,
                    'id_pemohon'     => \Me::data()->id_karyawan,
                    'id_acc'         =>0,
                    'keterangan'     =>$this->req['ket'],
                    'deadline'       =>date('Y-m-d', strtotime($this->req['deadline'])),
                    'status'         =>1,
                    'id_unit_tujuan' =>$this->req['id_gudang_tujuan'],
                    'id_unit_asal'   =>$this->req['id_unit'],

                ]);
            
            
            foreach($this->req['id_barang'] as $i => $id){
                if(!empty($this->req['qty'][$i])){
                        data_mutasi_spb_item::create([
                        'id_mutasi_spb'  => $mutasi->id_mutasi_spb,
                        'id_item'        => $id,
                        'qty_awal'       => $this->req['qty'][$i], //jumlah yng diminta
                        'id_unit' =>$this->req['id_unit'],
                        'status'         => 1,
                        'id_satuan'      => $this->req['id_satuan'][$i],
                    ]);
                }
                    
            }

                   $kode = view_count_mutasi_item::where('tahun', date('Y'))->first();
                    $urut = empty($kode->jumlah) ? 1 : $kode->jumlah;
                    $mutasi->no_mutasi_spb = 'PMBU/' . date('Y') . '/' . \Format::code($urut);
                    $mutasi->save();
                // KODE

       \DB::commit();

            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Permohonan Mutasi Barang berhasil terkirim dengan Nomor ' . $mutasi->no_mutasi_spb
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

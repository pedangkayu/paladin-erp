<?php

namespace App\Jobs\resep;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\data_resep;
use App\Models\data_resep_item;
use App\Models\data_item_gudang;
use App\Models\data_log_barang;

class AmbilObatJob extends Job implements SelfHandling
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

    public function handle()
    {
          // dd($this->req['id_barang']);
         $gudang= \Me::subgudang()->id_gudang;
         $me = \Me::data()->id_karyawan;
        try{
            \DB::begintransaction();

            $resep = data_resep::find($this->req['id_resep']);
                $resep->update([
                        'status_resep'      =>1 //di ambil semua
                        ]);

                  // Pencatatan ke log dilakukan pada saat terjadi pengurangan stok, atrinya dilakukan pada biling
                // =============================================================================================

                foreach ($this->req['id_barang'] as $i => $d) {
                if(!empty($this->req['id_barang'][$i])){
                        data_log_barang::create([
                            'id_barang' => $this->req['id_barang'][$i],
                            'qty' => $this->req['jumlah_out'][$i],
                            'keterangan' => 'Resep Obat oleh. ',
                            'id_gudang' => $gudang,
                            'kondisi' => 1, /* OUT */
                            'tipe' => 6,  //Resep Pasien
                            'id_parent' => $resep->id_resep,
                            'id_karyawan' => $me
                        ]);

                        $item_gudang = data_item_gudang::find($this->req['id_item_gudang'][$i]);
                        $item_gudang->out = $item_gudang->out + $this->req['jumlah_out'][$i];
                        $item_gudang->save();
                    }
                }
                 \DB::commit();
                    return [
                        'res' => true,
                        'label' => 'success',
                        'err' => 'Obat berhasil Di ambil. '
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

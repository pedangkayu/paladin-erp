<?php

namespace App\Jobs\Treatment;
// Model
use App\Models\data_treatment;
use App\Models\data_treatment_item;
use App\Models\data_resep_item;
use App\Models\data_log_barang;
use App\Models\Views\view_count_treatment;
use App\Models\Views\view_count_log_pasien;
use App\Models\data_log_pasien;
use App\Models\data_item_gudang;
// Models

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class InsertJasaTreatmentJob extends Job implements SelfHandling
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
         dd($this->req);

          $me = \Me::data()->id_karyawan;
        try{
            \DB::begintransaction();
        
        // perhtikan kode  jika  ada angka 1 di atas dan d bawah ada 1 jg brrt itu pembuka dan penutup
            $tindakan = data_treatment::create([ //1
                'id_pasien' => $this->req['id_pasien'],
                'id_dokter' => $this->req['id_karyawan'],
                'tgl_input' => date('Y-m-d h:i:s', strtotime($this->req['tgl_input'])),
                'tgl_pemeriksa' => date('Y-m-d h:i:s', strtotime($this->req['tgl_pemeriksa'])),
                'id_pembuat' => $me,
                'catatan' =>1,
            ]); //1

             $data = view_count_treatment::where('tahun', date('Y'))->first();
              $urut = empty($data->jumlah) ? 1 : $data->jumlah;
              $tindakan->nomor_treatment = 'TR-' . date('Y') . '-' . \Format::code($urut);
              $tindakan->save();

            $nomor =data_log_pasien::firstOrcreate([ //2
            'id_pasien' => $this->req['id_pasien'],
            'status' =>1,
             ]); //2

              if(!empty($this->req['id_service']) && count($this->req['id_service']) > 0): //3
                 foreach ($this->req['id_service'] as $i => $id) {
                    $item = data_treatment_item::create([
                    'id_treatment' => $tindakan->id_treatment,
                    'id_service' =>$this->req['id_service'][$i],
                    'id_paket' => 0,
                    'status' => 2,
                    ]);
                 }
                endif; //3
             if(!empty($this->req['id_barang']) && count($this->req['id_barang']) > 0): //5
                 foreach ($this->req['id_barang'] as $a => $ia) { //4
                      $bhp = data_resep_item::create([
                        'id_barang' =>$ia,
                        'id_item_gudang'=>$this->req['id_item_gudang'][$a],
                        'harga_jual'=>$this->req['harga_jual'][$a],
                        'id_klasifikasi'=>0,
                        'id_satuan'=>$this->req['id_satuan'][$a],
                        'id_resep_aturan'=>0,
                        'id_paket'=>0,
                        'status_resep_item'=>1,
                        'status_obat'=>3,
                        'id_treatment_item'=>0,
                        'id_treatment'=>$tindakan->id_treatment,
                        'qty' => $this->req['jumlah_out'][$a],
                    ]);


                 if(!empty($this->req['id_gudang'][$a])){ //6
                    data_log_barang::create([
                        'id_barang' => $ia,
                        'qty' => $this->req['jumlah_out'][$a],
                        'keterangan' => 'Treatment. ',
                        'id_gudang' => $this->req['id_gudang'][$a],
                        'kondisi' => 1, /* OUT */
                        'tipe' => 7,  //BHP
                        'id_parent' => $tindakan->id_treatment,
                        'id_karyawan' => $me
                    ]);

                      // ini untuk mengurangi stok BHP di sub gudang 
                    $item_gudang = data_item_gudang::find($this->req['id_item_gudang'][$a]);
                    $item_gudang->out = $item_gudang->out + $this->req['jumlah_out'][$a];
                    $item_gudang->save();
                }//  6 untuk id_gudang

                } //4
            endif; //5

                //       // Penyimpanan Paket disni
          if(!empty($this->req['id_paket_tre']) && count($this->req['id_paket_tre']) > 0): //9
              foreach($this->req['id_paket_tre'] as $id): //8

                foreach ($this->req['id_service_tre'][$id] as $p => $id_service ): //7
                    $statusser = empty($this->req['status_ser'][$id][$p]) ? 0 : 1;
                    $item = data_treatment_item::create([
                    'id_treatment' => $tindakan->id_treatment,
                    'id_paket' => $id,
                    'id_service' =>$id_service,
                    'status' => $statusser,
                    ]);
                endforeach;//7

             foreach($this->req['id_barang_tre'][$id] as $u => $id_barang_tre): //10

               $status = empty($this->req['status_tre'][$id][$u]) ? 0 : 1;
                 data_resep_item::create([
                      'id_barang' =>$id_barang_tre,
                      'id_item_gudang'=>$this->req['id_item_gudang_tre'][$id][$u],
                      'harga_jual'=>0,
                      'id_klasifikasi'=>0,
                      'id_satuan'=>$this->req['id_satuan_tre'][$id][$u],
                      'id_resep_aturan'=>0,
                      'status_resep_item'=>1,
                      'status_obat'=>3,
                      'status'=> $status,
                      'id_treatment' =>$tindakan->id_treatment,
                      'id_treatment_item'=>0,
                      'id_paket'=>$id,
                      'qty' => $this->req['qty_tre'][$id][$u],
                  ]);

                // if(!empty($this->req['id_gudang_tre'][$u])){ //6
                    data_log_barang::create([
                        'id_barang' => $id_barang_tre,
                        'qty' => $this->req['qty_tre'][$id][$u],
                        'keterangan' => 'Treatment. ',
                        'id_gudang' => $this->req['id_gudang_tre'][$id][$u],
                        'kondisi' => 1, /* OUT */
                        'tipe' => 7,  //BHP
                        'id_parent' => $tindakan->id_treatment,
                        'id_karyawan' => $me
                    ]);

                      // ini untuk mengurangi stok BHP di sub gudang 
                    $item_gudang = data_item_gudang::find($this->req['id_item_gudang_tre'][$id][$u]);
                    $item_gudang->out = $item_gudang->out + $this->req['qty_tre'][$id][$u];
                    $item_gudang->save();
                // }//  6 untuk id_gudang

                 endforeach; //  10 =ini untuk perulangan id_barang_tre

                endforeach; // 8 =in untuk perulangan yang atasnya lagi
                 endif;  //9

            if(date('Y') > 2016):
              $q=view_count_log_pasien::where('tahun', date('Y'))->first();
              $urut1=empty($q->jumlah) ? 1 : $q->jumlah;
              $nomor->nomor_antrian= 'AN' .\Format::code($urut1);
              $nomor->save();
              else:
                $q=view_count_log_pasien::where('tahun', date('Y'))->first();
                $urut1=empty($q->jumlah) ? 1 : $q->jumlah;
                $nomor->nomor_antrian= 'AN' .\Format::code($urut1);
                $nomor->save();
              endif;

            \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Pembuatan Data Treatment Berhasil. '
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


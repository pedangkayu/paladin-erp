<?php
namespace App\Jobs\Treatment;

use App\Models\Views\view_count_treatment;
use App\Models\data_treatment;
use App\Models\data_treatment_item;
use App\Models\data_treatment_resep;
use App\Models\Views\view_count_log_pasien;
use App\Models\data_log_pasien;
use App\Models\data_resep_item;
use App\Models\data_item_gudang;
use App\Models\data_log_barang;
use App\Models\data_treatment_dokter;
use App\Models\data_log_stockout;

use App\Jobs\Job;
use MMSQL;
use Illuminate\Contracts\Bus\SelfHandling;
class CreateTreatmentJob extends Job implements SelfHandling
{

 public $req;

    public function __construct(array $req)
    {
        $this->req = $req;
    }
    public function handle()
        {
      //dd($this->req);

        $me = \Me::data()->id_karyawan;
       // $gudang= \Me::subgudang()->id_gudang;
        try{
            \DB::begintransaction();

            $tindakan = data_treatment::create([
                'id_pasien'     => $this->req['id_pasien'],
                'tgl_input'     => date('Y-m-d h:i:s', strtotime($this->req['tgl_input'])),
                'tgl_pemeriksa' => date('Y-m-d h:i:s', strtotime($this->req['tgl_pemeriksa'])),
                'id_pembuat'    => $me,
                'id_unit'       =>$this->req['unit_jasa'], //id_gudangnya / unutk membaca jasanya dari mana
                'id_unit_item'  =>$this->req['unit'], //ini untuk prametere obatnya pakek dari gudang yang mana
                'id_paket'      =>$this->req['id_paket'],
                'kelas'        =>$this->req['id_kelas'],
                // 'grand_total' =>$this->req['grand_total'],
                'catatan'       =>1,
                'keterangan'  =>$this->req['keterangan'],
                'status'        =>0,
            ]);
            $nomor          =data_log_pasien::create([
            'id_pasien'     => $this->req['id_pasien'],
            'id_layanan'    =>$tindakan->id_treatment,
            'tipe'          =>$this->req['tipetreatment'],
            'no_antrian_hc' =>$this->req['no_antrian'],
            'waktu_transaksi' =>date('Y-m-d h:i:s', strtotime($this->req['tgl_pemeriksa'])),
            'nama_pasien'   =>$this->req['NAMA_PASIEN'],
            'id_kelas'      =>$this->req['id_kelas'],
            'status'        =>1,
             ]);

            $data = view_count_treatment::where('tahun', date('Y'))->first();
            $urut = empty($data->jumlah) ? 1 : $data->jumlah;
            $tindakan->nomor_treatment = 'TR-' . date('Y') . '-' . \Format::code($urut);
            $tindakan->save();


              if(!empty($this->req['service_kode']) && count($this->req['service_kode']) > 0){
                  foreach ($this->req['service_kode'] as $i => $id) {
                      $item = data_treatment_item::create([
                      'id_treatment' => $tindakan->id_treatment,
                      'id_service'   =>$this->req['id_service'][$i],
                      'tipe'         => $this->req['tipeser'][$i],
                      'service_kode'  =>$id,
                      'status'       => $this->req['status'][$i],
                      'tarif_dasar'  => $this->req['tarif_dasar'][$i],

                      ]);

                          if(!empty($this->req['id_dr'][$id])):
                                foreach ($this->req['id_dr'][$id] as $k => $ad):
                                  data_treatment_dokter::create([
                                    'id_dokter' =>$ad,
                                 'id_treatment_item' =>$item->id_treatment_item,

                                  'jabatan' =>$this->req['jabatan'][$id][$k],
                                    ]);
                                endforeach ;
                         endif;
                   if(!empty($this->req['jumlah_out'][$id]) && ($this->req['jumlah_out'][$id]) > 0):
                        foreach ($this->req['jumlah_out'][$id] as $t => $jumlah_out ):
                            $obt=data_resep_item::create([
                                'id_barang'         =>$this->req['id_barang_item'][$id][$t],
                                'id_item_gudang'    =>$this->req['id_item_gudang'][$id][$t],
                                'harga_jual'        =>$this->req['harga_jual'][$id][$t],
                                'id_klasifikasi'    =>0,
                                'id_satuan'         =>$this->req['id_satuan'][$id][$t],
                                'tipe'              =>$this->req['tipe'][$id][$t],
                                'id_resep_aturan'   =>0,
                                'status_resep_item' =>1,
                                'status_obat'       =>3,
                                'dihapus_pada'      =>0,
                                'keterangan'        =>0,
                                'status'            =>3,
                                'flat'            =>$this->req['flat'],
                                'reuse'             =>$this->req['pakek'][$id][$t],
                                'total'             =>$this->req['total'][$id][$t],
                                'id_treatment_item' =>$item->id_treatment_item,
                                'qty'               => $jumlah_out,
                            ]);


                            if( ($this->req['jumlah_out'][$id][$t]) > ($this->req['stok'][$id][$t])  && ($this->req['pakek'][$id][$t]) < 1){

                              $this->req['stok_akhir'][$id][$t]=(($this->req['stok'][$id][$t]) - ($this->req['jumlah_out'][$id][$t]));

                                  if(($this->req['stok'][$id][$t]) < 1){

                                      $this->req['hutang_now'][$id][$t]=(($this->req['stok'][$id][$t]) - $this->req['stok_akhir'][$id][$t]);

                                  }else{

                                     $this->req['hutang_now'][$id][$t]=(($this->req['jumlah_out'][$id][$t]) - ($this->req['stok'][$id][$t]));
                                  }
                                        data_log_stockout::create([
                                          'id_barang'      =>$this->req['id_barang_item'][$id][$t],
                                          'id_gudang'      =>$this->req['unit_jasa'],
                                          'id_item_gudang' =>$this->req['id_item_gudang'][$id][$t],
                                          'id_treatment_item'  =>$item->id_treatment_item,
                                          'id_resep_item'   =>$obt->id_resep_item,
                                          'req_qty'        =>$this->req['jumlah_out'][$id][$t],
                                          'stok'           =>$this->req['stok_akhir'][$id][$t],
                                          'hutang'         =>$this->req['hutang_now'][$id][$t],
                                          'id_karyawan'    => $me,
                                          'status'        =>1,
                                          ]);
                            }else{

                            }

                            if(!empty($this->req['id_item_gudang'][$id][$t]) && ($this->req['pakek'][$id][$t]) < 1){
                                   $item_gudang = data_item_gudang::find($this->req['id_item_gudang'][$id][$t]);
                                  $item_gudang->out = $item_gudang->out + $this->req['jumlah_out'][$id][$t];
                                  $item_gudang->save();

                            }

                            if(!empty($this->req['id_barang_item'][$id][$t])){
                                  data_log_barang::create([
                                      'id_barang'   => $this->req['id_barang_item'][$id][$t],
                                      'qty'         => $this->req['jumlah_out'][$id][$t],
                                      'keterangan'  => 'Treatment. ',
                                      'id_gudang'   => $this->req['unit_jasa'],
                                      'kondisi'     => 1, /* OUT */
                                      'tipe'        => 7,  //BHP
                                      'id_parent'   => $tindakan->id_treatment,
                                      'id_karyawan' => $me
                                  ]);

                             }
                      endforeach; // end item bhp
                   endif;
                 // insert dalam tabke log barang
                  }

              }
              if(date('Y') > 2015):
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
            'err' => ' Berhasil membuat data treatment , Silahkan Pilih Langkah Selanjutnya <a href="' . url('/authhc/logout') . '" class="btn btn-danger   btn-xs"><b>selesai</b></a>'
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

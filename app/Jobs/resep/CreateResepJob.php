<?php
namespace App\Jobs\Resep;

use App\Models\data_resep_item;
use App\Models\data_resep;
use App\Models\data_log_barang;
use App\Models\data_item_gudang;
use App\Models\Views\view_count_resep;
use App\Models\Views\view_count_log_pasien;
use App\Models\data_resep_campur;
use App\Models\data_log_pasien;
use App\Models\data_pasien;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
class CreateResepJob extends Job implements SelfHandling
{

 public $req;

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
        $me = \Me::data()->id_karyawan;
        $gudang= \Me::subgudang()->id_gudang;
        // $gub = \Me::subgudang()->id_item_gudang;v

        try{
            \DB::begintransaction();
            if($this->req['id_pasien'] == '' ):
              $a=data_pasien::create([
                'id_perpenjamin'                =>0,
                'id_layanan_rs'                 =>0,
                'id_pgw'                        =>$me,
                'nama_pasien'                   =>$this->req['NAMA_PASIEN'],
                'noktp_pasien'                  =>0,
                'noasuransi_pasien'             =>0,
                'jk_pasien'                     =>0,
                'status_nikah_pasien'      =>0,
                'tgllahir_pasien'          =>0,     
                'tempatlahir_pasien'       =>$this->req['alamat_pasien'],   
                'agama_pasien'             =>0,   
                'warga_negara_pasien'      =>0,    
                'pendidikan_pasien'        =>0,    
                'alamat_pasien'            =>0,  
                'kodepos_pasien'           =>0,    
                'kecamatan_pasien'         =>0,    
                'kelurahan_pasien'         =>0,    
                'kota_pasien'              =>0,    
                'telp_asal'                =>0,   
                'alamat_disurabaya_pasien' =>0, 
                'kodepos_surabaya'         =>0,     
                'telp_pasien'              =>0, 
                'hp_pasien'                =>0, 
                 'tipe'                          =>2,
                ]);
               $pasien=$a->id_pasien_hc;
            else:
              // echo"anda sukses";
            $pasien= $this->req['ID_PASIEN'];
            endif;
                $grand=(($this->req['grand_total']) + ($this->req['grand_totalcampur']));
                $resep = data_resep::create([
                       'id_pasien'     =>$pasien,
                       'id_karyawan'   => $this->req['id_karyawan'],
                       'kategori'      =>$this->req['kategori'],
                       'grand_total'         =>$grand,
                       'tgl_input'     => date('Y-m-d', strtotime($this->req['tanggal'])),
                       'tgl_pemeriksa' => date('Y-m-d', strtotime($this->req['tanggal_p'])),
                       'id_pembuat'    => $me,
                       'id_gudang'     =>$gudang,
                       'status_resep'  =>0,
                       'status'        =>1,
                       'catatan'       =>0,
                ]);
                if(!empty($this->req['id_barang']) && count($this->req['id_barang']) > 0){
                foreach ($this->req['id_barang'] as $i => $id) {
                    data_resep_item::create([
                            'id_resep'          => $resep->id_resep,
                            'id_barang'         =>$id,
                            'qty'               =>$this->req['jumlah_out'][$i],
                            'id_satuan'         =>$this->req['id_satuan'][$i],
                            'id_resep_aturan'   =>$this->req['id_resep_aturan'][$i],
                            'id_item_gudang'    =>$this->req['id_item_gudang'][$i],
                            'harga_jual'        =>$this->req['harga_jual'][$i],
                            'total'             =>$this->req['total'][$i],
                            'status_item_resep' =>1,
                            'id_treatment_item' =>0,
                            'dihapus_pada'      =>0,
                            'id_klasifikasi'    =>0,
                            'tipe'              =>0,
                            'status'            =>3,
                            'keterangan'        =>$this->req['keterangan'][$i],
                            'status_obat'       =>1,
                            'qty_retur'         =>0,
                            'status_retur'      =>0,

                    ]);
                  }
                  $format='NR';
                  $no =$format .($a->id_pasien);
                  $a->id_pasien_hc = $no;
                  $a->save();
              
                // Pencatatan ke log dilakukan pada saat terjadi pengurangan stok, atrinya dilakukan pada biling
                // =============================================================================================
               // if(!empty($this->req['id_gudang'][$i])){
               //      data_log_barang::create([
               //          'id_barang' => $id,
               //          'qty' => $this->req['jumlah_out'][$i],
               //          'keterangan' => 'Resep Obat. ',
               //          'id_gudang' => $this->req['id_gudang'][$i],
               //          'kondisi' => 1, /* OUT */
               //          'tipe' => 6,  //Resep Pasien
               //          'id_parent' => $resep->id_resep,
               //          'id_karyawan' => $me
               //      ]);

               //      // $item_gudang = data_item_gudang::find($this->req['id_item_gudang'][$i]);
               //      // $item_gudang->out = $item_gudang->out + $this->req['jumlah_out'][$i];
               //      // $item_gudang->save();
               //  }

            }

            if(!empty($this->req['campur']) && count($this->req['campur']) > 0){
                foreach($this->req['campur'] as $index => $val):
                    $item = data_resep_item::create([
                         'id_resep'          => $resep->id_resep,
                         'id_barang'         => 0,
                         'nama_campur'       => $this->req['campur'][$index],
                         'qty'               => 0,
                         'id_satuan'         => 0,
                         'id_resep_aturan'   => $this->req['id_resep_aturan_campur'][$index],
                         'id_item_gudang'    =>  0,
                         'harga_jual'        =>0,
                         'status_item_resep' => 1,
                         'status_obat'       =>2,
                         'id_treatment_item'  =>0,
                         'dihapus_pada'        =>0,
                         'id_klasifikasi'       =>0,
                         'tipe'               =>0,
                        'status'              =>3,
                         'keterangan'        => $this->req['ket_campur'][$index],
                    ]);

                    foreach($this->req['id_item_gudang_campur'][$index] as $i => $id_item_gudang_campur){

                        data_resep_campur::create([
                            'qty'              => $this->req['jumlah_out_campur'][$index][$i],
                            'id_resep_item'    => $item->id_resep_item,
                            'id_barang'        => $this->req['id_barang_campur'][$index][$i],
                            'harga_jual'       =>$this->req['harga_jual_campur'][$index][$i],
                            'id_resep'         => 0,
                            'id_satuan_campur' =>$this->req['id_satuan_campur'][$index][$i],
                            'id_item_gudang'   => $id_item_gudang_campur
                        ]);

                    }

                endforeach;
                
            }
              $nomor =data_log_pasien::create([
                    'id_pasien' => $this->req['ID_PASIEN'],
                    'id_layanan'    =>$resep->id_resep,
                    'nama_pasien' =>$this->req['NAMA_PASIEN'],
                    'tipe'  =>1,
                    'status'    =>1,
                    'no_antrian_hc' =>0,
                  ]);

            
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

            $data = view_count_resep::where('tahun', date('Y'))->first();
            $urut = empty($data->jumlah) ? 1 : $data->jumlah;
            $resep->nomor_resep = 'RSP-' . \Format::code($urut);
            $resep->save();
            
            \DB::commit();
            return [
                'res' => true,
                'label' => 'success',
                'err' => 'Pembuatan Resep Obat Berhasi. ' .$resep->nomor_resep
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

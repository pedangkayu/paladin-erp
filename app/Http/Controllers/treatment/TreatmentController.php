<?php

namespace App\Http\Controllers\treatment;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Jobs\Treatment\InsertTreatmentMysqlJob;
use App\Jobs\Treatment\CreateTreatmentJob;

use App\Jobs\Antrian\InsertAntrianJob;

use App\Jobs\resep\InsertPasienMysqlJob;
use App\Jobs\treatment\InsertRadiologiJob;
use App\Jobs\treatment\InsertRawatInapJob;
use App\Jobs\treatment\PindahKelasJob;
use App\Jobs\Treatment\InsertJadwaloperasiJob;
use App\Jobs\Treatment\InsertAntrinNonpoliJob;
use App\Jobs\treatment\UpdatetrTransaksijasaJob;

use App\Models\ref_service;
use App\Models\data_item_gudang;
use App\Models\data_karyawan;
use App\Models\data_treatment;
use App\Models\data_treatment_item;
use App\Models\data_treatment_resep;
use App\MOdels\ref_service_detail;
use App\Models\data_pasien;

use App\Models\ref_service_tindakan;
use App\Models\ref_service_item;
use App\Models\ref_kelas;
use App\Models\data_log_pasien;
use App\Models\ref_service_grup;
use App\Models\ref_service_kode;
use App\Models\data_resep_item;
use App\Models\data_log_stockout;
use App\Models\data_transfer;
use App\Models\ref_layanan;
use App\Models\ref_gudang;
use Mssql;

class TreatmentController extends Controller
{
  public function getIndex(){
     $gudang= \Me::subgudang()->id_gudang;
     $cek=ref_gudang::all();
    $item=data_treatment::bytreatment()->paginate(10);
      $status=[
            0=>'Baru',
            1=>'Proses',
            2=>'Selesai',
            ];

    return view('Pelayanan.treatment.listtindakan', [
     'items' => $item,
     'status' =>$status,
     'cek'    =>$cek,
     ]);
  }
  public function getHc(){
    $gudang= \Me::subgudang()->id_gudang;
    $item=data_treatment::bytreatment()->paginate(10);

      $status=[
            0=>'Baru',
            1=>'Proses',
            2=>'Selesai',
            ];
    return view('Pelayanan.treatment.HC.Hc_list', [
     'items' => $item,
     'status' =>$status,
     ]);

  }
    public function getViewhc($id_pasien = '', $id_layanan_rs='',$antrian=''){
      // dd($id_layanan_rs);
       $test=data_transfer::join('ref_gudang', 'ref_gudang.id_gudang', '=','data_transfer.id_gudang_item')
                                  ->join('ref_layanan', 'ref_layanan.id_layanan', '=', 'data_transfer.id_layanan')
                                  ->where('data_transfer.id_layanan',$id_layanan_rs)->where('no_antrian',$antrian)->first();
                                  // dd($test->id_layanan_sim);
    $item=data_treatment::byview()->where('data_treatment.id_pasien',$id_pasien)->where('data_treatment.id_unit',$test->id_gudang_jasa)->paginate(10);
    $data['id_pasien'] = $id_pasien;
      $status=[
            0=>'Baru',
            1=>'Proses',
            2=>'Selesai',
            ];
    return view('Pelayanan.treatment.HC.view_hc', [
     'items' => $item,
     'status' =>$status,
     'data' =>$data,
     'test' =>$test,
     ]);

  }
  public function getAlltreatmenthc(Request $req){
        // dd($req->all());
        if($req->ajax()){
            $result = [];
            $items = data_treatment::byview($req->id_gudang_jasa, $req->status, $req->id_pasien_hc,$req->all())
                  ->where('data_treatment.id_unit','LIKE', '%' . $req['id_gudang_jasa'] . '%')
                  ->where('data_treatment.id_pasien','LIKE', '%' . $req['id_pasien_hc'] . '%')
                  ->paginate($req->limit);
            $out = '';
            $total = $items->total();
             $status=[
                  0=>'Baru',
                  1=>'Proses',
                  2=>'Selesai',
              ];
           
            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                   
                   if($item->status ==2){
                        $edit = '
                           
                        ';
                    }else{
                        $edit = ' <a href="'.url('/treatment/updatehc/'. $item->id_treatment).'" >Edit</a>';
                    }
                    $out .= '
                        <tr class="tr_' . $item->id_treatment . '">
                            <td>' . $no . '</td>
                            <td width="20%">
                                <div>' .  $item->nomor_treatment. '</div>
                                <div class="link text-muted">
                                    <small>
                                        [
                                        <a href="'. url('/treatment/detailhc/'.$item->id_treatment). '">Lihat</a>
                                        |'.$edit.'
                                        ]
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>' . $item->id_pasien. '</div>
                                <div class="text-muted"><small>Pasien : ' . $item->nama_pasien. '</small></div>
                            </td>
                            <td>'.$item->nama_pasien.' </td>
                            <td>
                                <div>' . \Format::indoDate2($item->created_at) . '</div>
                                <div class="text-muted"><small>' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small></div>
                            </td>
                            <td>' . $status[$item->status] . '</td>
                         
                        
                        </tr>
                    ';

                    $no++;
                }
            else:
                $out = '
                    <tr>
                    <td colspan="7">Tidak ditemukan</td>
                    </tr>
                ';
            endif;

            $result['data'] = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/treatment');
        }

    }
     public function getAlltreatment(Request $req){
        // dd($req->id_gudang);
        if($req->ajax()){
            $result = [];
            $items = data_treatment::bytreatment($req->nomor_treatment, $req->id_gudang,$req->status, $req->id_pasien_hc,$req->all())->paginate($req->limit);
            $out = '';
            $total = $items->total();
             $status=[
                  0=>'Baru',
                  1=>'Proses',
                  2=>'Selesai',
              ];
           
            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                    if( \Me::subgudang()->id_gudang < 1):
                      $uni='<td>'.$item->unit.' </td>';
                    else:
                      $uni='';
                    endif;
                   if($item->status ==2){
                        $edit = '
                           
                        ';
                    }else{
                        $edit = ' <a href="'.url('/treatment/updatehc/'. $item->id_treatment).'" >Edit</a>';
                    }
                    $out .= '
                        <tr class="tr_' . $item->id_treatment . '">
                            <td>' . $no . '</td>
                            <td width="20%">
                                <div> ' .  $item->nomor_treatment. '</div>
                                <div class="link text-muted">
                                    <small>
                                        [
                                        <a href="'. url('/treatment/detail/'.$item->id_treatment). '">Lihat</a>
                                        |'.$edit.'
                                        ]
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>' . $item->id_pasien. '</div>
                                <div class="text-muted"><small>Pasien : ' . $item->nama_pasien. '</small></div>
                            </td>
                            <td>'.$item->nama_pasien.' </td>
                            '.$uni.'
                            <td>
                                <div>' . \Format::indoDate2($item->created_at) . '</div>
                                <div class="text-muted"><small>' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small></div>
                            </td>
                            <td>' . $status[$item->status] . '</td>
                         
                        
                        </tr>
                    ';

                    $no++;
                }
            else:
                $out = '
                    <tr>
                    <td colspan="7">Tidak ditemukan</td>
                    </tr>
                ';
            endif;

            $result['data'] = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/treatment');
        }

    }

    public function getCreate(Request $req )
    {
          $me = \Me::data()->id_karyawan;
             
              $test=data_transfer::join('ref_gudang', 'ref_gudang.id_gudang', '=','data_transfer.id_gudang_item')
                                  ->join('ref_layanan', 'ref_layanan.id_layanan', '=', 'data_transfer.id_layanan')
                                    ->join('ref_gudang as A','A.id_gudang','=', 'data_transfer.id_gudang_jasa')
                                  
                                  ->where('data_transfer.id_layanan',$req->id_layanan_rs)->where('no_antrian',$req->antrian)->select('data_transfer.*','ref_gudang.nm_gudang', 'A.nm_gudang as gudang_jasa')->first();
                // $gud=ref_gudang::where('id_layanan',$req->id_layanan_rs)->where('id_gudang',$test->id_gudang)->first();
              // dd($test);
            if(count($test)==0){
                          
                          return redirect('/treatment/error')->withNotif([
                             'label' => 'danger',
                            'err' => ' Mohon Maaf Anda Sedang Login Dengan Akun Yang berbeda Silahkan Klik Keluar  <a href="' . url('/authhc/logout') . '" class="btn btn-danger   btn-xs"><b>Keluar</b></a>'
                   
                          ]);
              }else{

              if($test->tabel_antrian==1):
                   $pa = \MSSQL::tbl("ANTRIAN")
                           ->leftJoin('PASIEN', 'PASIEN.ID_PASIEN', '=', 'ANTRIAN.ID_PASIEN')
                           ->leftJoin('PEGAWAI', 'PEGAWAI.ID_PGW', '=', 'ANTRIAN.MAS_ID_PGW')
                           ->where('ANTRIAN.MAS_ID_PGW', $req->id_dokter)
                           ->where('ANTRIAN.ID_PASIEN', $req->id_pasien)
                           ->where('NO_ANTRIAN', $req->id_antrian)
                          ->where('ANTRIAN.TANGGAL_JADWAL', $req->thn_antrian.'-'.$req->bln_antrian.'-'.$req->tgl_antrian.' 00:00:00.000')
                           ->select('ANTRIAN.*','ANTRIAN.MAS_ID_PGW AS mas_id_pgw','PEGAWAI.NAMA_PGW AS dokter','ANTRIAN.ID_LAYANAN_RS AS id_layanan_rs',
                               'PASIEN.*')->first(); 
                           //insert data
                           $antrian = $this->dispatch(new InsertAntrianJob($pa));
              elseif($test->tabel_antrian==2):
                 $pa= \MSSQL::tbl('PERIKSA_PENUNJANG_MEDIS')
                            ->leftJoin('PASIEN', 'PASIEN.ID_PASIEN', '=', 'PERIKSA_PENUNJANG_MEDIS.ID_PASIEN')
                            ->leftJoin('PEGAWAI', 'PEGAWAI.ID_PGW', '=', 'PERIKSA_PENUNJANG_MEDIS.ID_PGW')
                            ->where('PERIKSA_PENUNJANG_MEDIS.TGL_PERIKSA_PM', $req->thn_antrian.'-'.$req->bln_antrian.'-'.$req->tgl_antrian.' 00:00:00.000')
                            ->where("PERIKSA_PENUNJANG_MEDIS.ID_PASIEN", $req->id_pasien)
                            ->where("PERIKSA_PENUNJANG_MEDIS.ID_PEMERIKSAAN_PM",$req->id_antrian)
                            ->where("PERIKSA_PENUNJANG_MEDIS.ID_PGW", $req->id_dokter)
                            ->select(
                                'PERIKSA_PENUNJANG_MEDIS.*',
                                'PERIKSA_PENUNJANG_MEDIS.ID_PASIEN',
                                'PERIKSA_PENUNJANG_MEDIS.ID_PEMERIKSAAN_PM AS NO_ANTRIAN', 'PERIKSA_PENUNJANG_MEDIS.ID_LAYANAN_RS AS id_layanan_rs',
                                'PASIEN.*',
                                'PERIKSA_PENUNJANG_MEDIS.ID_PGW AS mas_id_pgw',
                                'PEGAWAI.NAMA_PGW as dokter'
                            // )->get();
                            )->first();
                             // dd($pa);
                             $antrian=$this->dispatch(new InsertRadiologiJob($pa));
              elseif($test->tabel_antrian==3):
                $pa= \MSSQL::tbl('PELAYANAN_RAWAT_INAP')
                                  ->leftJoin('PASIEN', 'PASIEN.ID_PASIEN', '=', 'PELAYANAN_RAWAT_INAP.ID_PASIEN')
                                   ->where('PELAYANAN_RAWAT_INAP.ID_PASIEN', $req->id_pasien)
                                   ->where('PELAYANAN_RAWAT_INAP.ID_ANTRIAN_RWT_INAP',$req->id_antrian)
                                   //->where('PELAYANAN_RAWAT_INAP.RENCANA_KRS', $req->thn_antrian.'-'.$req->bln_antrian.'-'.$req->tgl_antrian.' 00:00:00.000')
                                  
                                   ->select('PELAYANAN_RAWAT_INAP.*',
                                    'PELAYANAN_RAWAT_INAP.ID_PGW AS mas_id_pgw',
                                     'PELAYANAN_RAWAT_INAP.ID_ANTRIAN_RWT_INAP AS NO_ANTRIAN','PELAYANAN_RAWAT_INAP.ID_LAYANAN_RS As id_layanan_rs',
                                    'PASIEN.*' )->first();
                                  $antrian=$this->dispatch(new InsertRawatInapJob($pa));
                                  // dd($pa);
              elseif($test->tabel_antrian==4):
                $pa= \MSSQL::tbl('JADWAL_OPERASI')   
                                    ->leftJoin('PASIEN', 'PASIEN.ID_PASIEN', '=','JADWAL_OPERASI.ID_PASIEN')
                                    ->where('JADWAL_OPERASI.ID_JADWAL_OPERASI', $req->id_antrian)
                                    ->where('JADWAL_OPERASI.ID_PASIEN',$req->id_pasien)
                                    ->where('JADWAL_OPERASI.TANGGAL_OPERASI',$req->thn_antrian.'-'.$req->bln_antrian.'-'.$req->tgl_antrian.' 00:00:00.000')
                                    ->select('JADWAL_OPERASI.*' ,'JADWAL_OPERASI.ID_JADWAL_OPERASI AS NO_ANTRIAN','JADWAL_OPERASI.TANGGAL_OPERASI AS id_jadwal_hc',
                                            'JADWAL_OPERASI.ID_LAYANAN_RS AS id_layanan_rs',
                                            'PASIEN.*')->first();
                                    //dd($pa);
                                    $antrian=$this->dispatch(new InsertJadwaloperasiJob($pa));
              elseif($test->tabel_antrian==5):
                 $pa= \MSSQL::tbl('ANTRIAN_NON_POLI')
                                      ->leftJoin('PASIEN', 'PASIEN.ID_PASIEN', '=', 'ANTRIAN_NON_POLI.ID_PASIEN')
                                      ->where('ANTRIAN_NON_POLI.NO_ANTRIAN_NON_POLI', $req->id_antrian)
                                      ->where('ANTRIAN_NON_POLI.ID_PASIEN',$req->id_pasien)
                                      ->where('ANTRIAN_NON_POLI.TGL_NON_POLI',$req->thn_antrian.'-'.$req->bln_antrian.'-'.$req->tgl_antrian.' 00:00:00.000')
                                      ->select('ANTRIAN_NON_POLI.*', 'ANTRIAN_NON_POLI.NO_ANTRIAN_NON_POLI AS NO_ANTRIAN','ANTRIAN_NON_POLI.ID_LAYANAN_RS AS id_layanan_rs',
                                                'ANTRIAN_NON_POLI.ID_PGW AS mas_id_pgw',
                                                'PASIEN.*')->first();
                                        // dd($pa);
                                       $antrian=$this->dispatch(new InsertAntrinNonpoliJob($pa));
              endif;
            
              }
              $m= data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
              if(($test->id_gudang==3)||($test->id_gudang==5)):

                   $kelas= ref_kelas::whereIn('ref_kelas.id_kelas',[1])->get();
                else:
                  $kelas= ref_kelas::all();

               endif;
             
               //$pa='';
               $grup=ref_service_grup::whereIn('ref_service_grup.id_grup',[22,23,24])->get();
                    if(count($pa)==0){
                          
                          return redirect('/treatment/error')->withNotif([
                             'label' => 'danger',
                            'err' => ' Mohon Maaf Anda Sedang Login Dengan Akun Yang berbeda Silahkan Klik Keluar  <a href="' . url('/authhc/logout') . '" class="btn btn-danger   btn-xs"><b>Keluar</b></a>'
                   
                          ]);
                    }else{
                          return view('Pelayanan.Treatment.index', [
                              'dokter'  =>$m,
                              'kelas'   =>$kelas,
                              'pa'      =>$pa,
                              'grup'    =>$grup,
                              'antrian' =>$antrian,
                              'test'    =>$test,
                              'gudang'  =>$test->id_gudang,
                             
                          ]);
                    }
          }
    public function getError(){
      return view('Pelayanan.Treatment.error');
    }
          
    public function getDetail($id){
      $data=data_treatment::hid($id);
      if($data->count() == 0)
          return redirect('/treatment');
      $data=data_treatment::hid($id)->first(); 
      $tindakan=data_treatment_item::tindakan($id)->orderby('tipe', 'desc')->get();
            $tipe=[
                  1=> 'tindakan',
                  2 => 'Jasa'
                  ];
            $jabatan =[
                  0=> '',
                  1=> 'DPJP',
                  2=> 'OPERATOR/ANGGOTA'
            ];
     $bahan=data_resep_item::bahan($id)->get();
          return view('Pelayanan.treatment.detail',[

                  'data'     =>$data,
                  'tindakan' =>$tindakan,
                  'tipe'     =>$tipe,
                  'bahan'    =>$bahan,
                  'jabatan'  =>$jabatan
                  ]);

    }
    public function getDetailhc($id){
      $data=data_treatment::hid($id);
      if($data->count() == 0)
          return redirect('/treatment');
      $data=data_treatment::hid($id)->first(); 
      $tindakan=data_treatment_item::tindakan($id)->orderby('tipe', 'desc')->get();
       $test=data_transfer::join('ref_gudang', 'ref_gudang.id_gudang', '=','data_transfer.id_gudang_item')
                                  ->join('ref_gudang as A','A.id_gudang','=', 'data_transfer.id_gudang_jasa')
                                  ->where('data_transfer.id_gudang_jasa',$data->id_unit)->where('id_gudang_item',$data->id_unit_item)
                                  ->select('data_transfer.*','ref_gudang.nm_gudang', 'A.nm_gudang as gudang_jasa')->first();
            $tipe=[
                  1=> 'tindakan',
                  2 => 'Jasa'
                  ];
            $jabatan =[
                  0=> '',
                  1=> 'DPJP',
                  2=> 'OPERATOR/ANGGOTA'
            ];
     $bahan=data_resep_item::bahan($id)->get();
          return view('Pelayanan.treatment.HC.detai',[

                  'data'     =>$data,
                  'tindakan' =>$tindakan,
                  'tipe'     =>$tipe,
                  'bahan'    =>$bahan,
                  'jabatan'  =>$jabatan,
                  'test'    =>$test
                  ]);

    }
    public function getUpdate($id){

        $data=data_treatment::hid($id);

        if($data->count() == 0)
          return redirect('/treatment');

        $data=data_treatment::hid($id)->first();
        $tindakan=data_treatment_item::tindakan($id)->get();
        $jumlah=data_treatment_item::tindakan($id)->whereIn('tipe',[2])->get();
        $dokter=data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,3,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();

              $tipe    = [
                  1        => 'tindakan',
                  2        => 'Jasa'
              ];
              $jabatan = [
                  0        => '',
                  1        => 'DPJP',
                  2        => 'OPERATOR/ANGGOTA'
              ];
        return view('Pelayanan.treatment.update',[
                'tindakan' =>$tindakan,
                'tipe'     =>$tipe,
                'data'     =>$data,
                'dokter'   =>$dokter,
                'jabatan'  =>$jabatan,
                'jumlah'  =>$jumlah

          ]);

    }
    public function getUpdatehc($id){
       $data=data_treatment::hc($id);

        if($data->count() == 0)
          return redirect('/treatment');
        $data=data_treatment::hc($id)->first();
        $tindakan=data_treatment_item::tindakan($id)->get();
        $jumlah=data_treatment_item::tindakan($id)->whereIn('tipe',[2])->get();
        $dokter=data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,3,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
        $test=data_transfer::join('ref_gudang', 'ref_gudang.id_gudang', '=','data_transfer.id_gudang_item')
                                  ->join('ref_gudang as A','A.id_gudang','=', 'data_transfer.id_gudang_jasa')
                                  ->where('data_transfer.id_gudang_jasa',$data->id_unit)->where('id_gudang_item',$data->id_unit_item)
                                  ->select('data_transfer.*','ref_gudang.nm_gudang', 'A.nm_gudang as gudang_jasa')->first();
              $tipe    = [
                  1        => 'tindakan',
                  2        => 'Jasa'
              ];
              $jabatan = [
                  0        => '',
                  1        => 'DPJP',
                  2        => 'OPERATOR/ANGGOTA'
              ];
        return view('Pelayanan.treatment.HC.update',[
                'tindakan' =>$tindakan,
                'tipe'     =>$tipe,
                'data'     =>$data,
                'dokter'   =>$dokter,
                'jabatan'  =>$jabatan,
                'jumlah'  =>$jumlah,
                'test'    =>$test                

          ]);
    }
    public function postUpdate(Request $req){
      
      //dd($req->all());
       $arr = $this->dispatch(new UpdatetrTransaksijasaJob($req->all()));
       if($arr['res'])
          return redirect('/treatment/hc')->withNotif([
                  'label' => $arr['label'],
                  'err' => $arr['err']
              ]);
      else
          return redirect('/treatment/hc')->withNotif([
                  'label' => $arr['label'],
                  'err' => $arr['err']
              ]);
    }
        public function getLoadupdatebhp(Request $req){
            if($req->ajax()){
                $res = [];
                $out = '';
                $items = data_item_gudang::treatment($req->all())->paginate(5);
                //dd($items);
                $total = $items->total();
                if($total > 0):
                    foreach($items as $item){
                        $akhir =$item->in - $item->out ;
                        $btn_aktif = ($akhir>0) ? '' : 'disabled';
                        $out .= '
                            <tr class="update-' . $item->id_item_gudang . '">
                                <td>' . $item->kode . '</td>
                                <td>' . $item->nm_barang . ' <small class="pull-right hide itemupdate-loading-' . $item->id_item_gudang. '">Memuat...</small></td>
                                <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itemupdate-loading-loading-' . $item->id_item_gudang . '">Memuat...</small></td>
                                <td class="text-right"><button class="btn-sm btn-danger btn-itemupdate-' . $item->id_item_gudang . '" onclick="add_updatebhp(' . $item->id_item_gudang . ');" '.$btn_aktif.'><i class="fa fa-plus"></i></button></td>
                            </tr>
                        ';
                    }
                else:
                    $out = '
                        <tr>
                            <td colspan="3">Tidak ditemukan</td>
                        </tr>
                    ';
                endif;

                $res['total'] = $total;
                $res['content'] = $out;
                $res['pagin'] = $items->render();

                return json_encode($res);
            }
        }
    public function getAdditemupdate(Request $req){
       $me = \Me::subgudang()->id_gudang;

      if($req->ajax()){

          $res = [];
          $out='';
              $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                  ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                  // ->where('data_item_gudang.id_gudang', $me)
                   ->whereIn('data_barang.status',[1])
                  ->where('data_item_gudang.id_item_gudang', $req->id)
                      ->select(
                       'data_item_gudang.*',
                       'data_barang.id_satuan',
                       'data_barang.kode',
                        'data_barang.nm_barang',
                        'data_barang.harga_jual',
                        'data_item_gudang.in AS masuk',
                       'data_item_gudang.out AS keluar',
                      'ref_satuan.nm_satuan'
                                           )
                  ->first();
           $akhir=  $item->masuk - $item->keluar;
          $warna = ($akhir>0) ? '<span class="label label-success">' : '<span class="label label-important">';
          $res['item'] = $item;
           $res['content'] = $out;
           $res['akhir']=$akhir;
          // dd($res);
          return json_encode($res);
          }
      }
    public function getLoadjasaupdate(Request $req){
        if($req->ajax()){
              $res = [];
              $out = '';
          $items=ref_service_detail::cari($req->all())->paginate(5); 
          $total = $items->total();
          if($total > 0):
              foreach ($items as $item) {
                $out .='
                      <tr class="jasaupdate-'.$item->id_service_detail.'">
                       <input type="hidden" name="id_treatment_item" value="0">
                         <td>' . $item->nm_service . ' <small class="pull-right hide jasaupdate-loading-' . $item->id_service_detail . '">Memuat...</small></td>
                          <td class="text-right"><button title="Pilih Jasa" class="btn btn-danger btn-small btn-jasaupdate-' . $item->id_service_detail . '" onclick="add_jasaupdate(' . $item->id_service_detail . ');"><i class="fa fa-plus"></i></button></td>
                      </tr>
                ';
              }else:
                $out .='
                      <tr>
                          <td colspan="4">Tidak Ditemukan</td>
                      </tr>
                ';
                endif;
            $res['total'] = $total;
            $res['content'] = $out;
            $res['pagin'] = $items->render();
            return json_encode($res);
          }
      }
        public function getAddjasaupdate(Request $req){
          $me = \Me::subgudang()->id_gudang;
          if($req->ajax()){
              $res = [];
              $out = '';
             // dd($req->all());
              $dokter=data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
              $item=ref_service_detail::leftJoin('ref_service_kode', 'ref_service_kode.service_kode' ,'=' ,'ref_service_detail.id_service_kode')
                                ->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=' ,'ref_service_detail.id_unit')
                                ->whereIn('ref_service_kode.type',[2])
                                ->where('ref_service_detail.id_unit', $me)
                                ->where('ref_service_detail.id_service_detail', $req->id)
                               ->select('ref_service_detail.*','ref_service_kode.*')->first();
             
             if($item->kebutuhan==1):
                    $out.='
                        <td>
                          <select class="form-control" id="id_dr" required name="id_dr['.$item->service_kode.'][]">
                            <option value="">Pilih Dokter</option>';
                              foreach($dokter as $ke):
                                $out .= '<option value="'.$ke->id_karyawan.'">'.$ke->nm_depan.'&nbsp;'.$ke->nm_belakang.'</option>';
                               endforeach;
                    $out .= '
                        </select>
                      </td>
                        <td>
                          <select class="form-control" id="jabatan" required name="jabatani['.$item->service_kode.'][]">
                            <option value="">Pilih Jabatan Dokter </option>
                            <option selected="selected" value="1">DPJP</option>
                            <option value="2">Anggota/OPERATOR</option>
                          </select>
                        </td>
                        <td>
                          <button type="button" class="btn btn-danger btn-sm "  onclick="tambah_dokter(' .$item->service_kode .',1);">Tambah Dokter</button>
                        </td>
                        <td><button title="Hapus" type="button" class="btn btn-danger btn-jasa-up"><i class="fa fa-trash"></i></button></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="contn-' .$item->service_kode . '"></td>
                    </tr>
                        ';
                     
                else:
                    $out.='
                   <td colspan="2" class="text-right"><button title="Hapus" type="button" class="btn btn-danger btn-jasa-up"><i class="fa fa-trash"></i></button></td>
                      <input type="hidden" name="id_dr['.$item->service_kode.'][]" value="0">
                      <input type="hidden" name="jabatani['.$item->service_kode.'][]" value="0">
                   
                      ';
                endif;
              
                $res['item'] = $item;
                 $res['dok'] = $out;
              return json_encode($res);
                }
        }

      public function getTambahdokter(Request $req)
          {
           if($req->ajax()){
                $res = [];
                 $dokters = data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
                  $out = '';
                  $out .='<option value="">Pilih Dokter</option>';
                 foreach($dokters as $dokter):
                    $out .= '<option value="'.$dokter->id_karyawan.'">'.$dokter->nm_depan.' &nbsp;'.$dokter->nm_belakang.'</option>';
                   endforeach;
                  $res['dokter'] = $out;
                  return json_encode($res);
            }
          }
       public function postHapus(Request $req){
        if($req->ajax()){
           data_treatment_item::find($req->id)->delete();

          return json_encode([
            'result' => true
            ]);
               
        }
    }
    public function postRefound(Request $req){
      if($req->ajax()){
        data_treatment_item::find($req->id)->update([
          'status' =>2
          ]);
         return json_encode([
            'result' => true
            ]);
      }
    }
    public function postBatalrefound(Request $req){
      if($req->ajax()){
        data_treatment_item::find($req->id)->update([
          'status' =>1
          ]);
         return json_encode([
            'result' => true
            ]);
      }
    }
    public function getPindah($id){
        $data=data_treatment::kelas($id);
        if($data->count()==0)
            return redirect('/treatment');
        $data=data_treatment::kelas($id)->first();
        $set =data_log_pasien::pindah($id)->first();
        $k=data_log_pasien::pindah($id)->get();
        $kelas=ref_kelas::all();
        return view('Pelayanan.treatment.pindah',[
            'data' =>$data,
            'set'   =>$set,
            'kelas' =>$kelas,
            'k' =>$k,
            ]);
    }

    public function postPindah(Request $req){
        if(count($req->id_layanan)==0)
            return redirect()->back()->withNotif([
                'label' =>'danger',
                'err' =>'<center>OOps !, data Layanan Kosong</center>'
                ]);
        $arr= $this->dispatch(new PindahKelasJob($req->all()));
        if($arr['res'])
                return redirect('/treatment')->withNotif([
                    'label' =>$arr['label'],
                    'err' => $arr['err']
                    ]);
        else
                return redirect()->back()->withNotif([
                    'label' =>$arr['label'],
                    'err' =>$err['err']
                    ]);
    }
    /* nambahkan dari Mssql--holil*/
    public function getAddpasien(Request $req){
        if($req->ajax()){
            $res = [];
            $out = [];
            $pa = \MSSQL::tbl("PASIEN")->where('ID_PASIEN',$req->id)->first();
            $pasien = $this->dispatch(new InsertPasienMysqlJob($pa));
               $res['pa'] = $pasien;
                // $res['pa'] = $pa;
            return json_encode($res);
        }
    }

    public function getAdditem(Request $req){
     $me = \Me::subgudang();

    if($req->ajax()){

        $res = [];
        $out='';
            $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                ->where('data_item_gudang.id_gudang', $me->id_gudang)
                // ->where('data_item_gudang.id_gudang', 26)
                 ->whereIn('data_barang.status',[1])
                ->where('data_item_gudang.id_barang', $req->id)
                    ->select(
                     'data_item_gudang.*',
                     'data_barang.id_satuan',
                     'data_barang.kode',
                      'data_barang.nm_barang',
                      'data_barang.harga_jual',
                      'data_item_gudang.in AS masuk',
                     'data_item_gudang.out AS keluar',
                    'ref_satuan.nm_satuan'
                                         )
                ->first();
        $res['item'] = $item;
         $res['content'] = $out;
        // dd($res);
        return json_encode($res);

    }
}

public function postTreatment(Request $req){

         // dd($req->jumlah_out);
  //dd($req->id_item_gudang);
    if(count($req->id_service) == 0)
        return redirect()->back()->withNotif([
            'label' => 'danger',
            'err' => '<center>OOps!, Anda Belum Tindakan </center>'
        ]);

    $arr = $this->dispatch(new CreateTreatmentJob($req->all()));
      if($arr['res'])
            
             return redirect()->back()->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
     else
        return redirect()->back()->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    }

    public function getLoadjasa(Request $req){

      if($req->ajax()){
            $res = [];
            $out = '';
        $items=ref_service_detail::cari($req->all())->paginate(5); 
        $total = $items->total();
        if($total > 0):
            foreach ($items as $item) {
               // dd($item);
              $out .='
                    <tr class="jasaku-'.$item->id_service_detail.'">
                       <td>' . $item->nm_service . ' <small class="pull-right hide jasaku-loading-' . $item->id_service_detail . '">Memuat...</small></td>
                        <td class="text-right"><button title="Pilih Jasa" class="btn btn-danger btn-small btn-jasaku-' . $item->id_service_detail . '" onclick="add_jasaku(' . $item->id_service_detail . ');"><i class="fa fa-plus"></i></button></td>
                    </tr>
              ';
            }else:
              $out .='
                    <tr>
                        <td colspan="4">Tidak Ditemukan</td>
                    </tr>
              ';
              endif;
          $res['total'] = $total;
          $res['content'] = $out;
          $res['pagin'] = $items->render();
          return json_encode($res);
        }
    }
      public function getAddjasaku(Request $req){
        $me = \Me::subgudang()->id_gudang;
      if($req->ajax()){
          $res = [];
          $out = '';
            $dokter=data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
          $item=ref_service_detail::leftJoin('ref_service_kode', 'ref_service_kode.service_kode' ,'=' ,'ref_service_detail.id_service_kode')
                            ->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=' ,'ref_service_detail.id_unit')
                            ->whereIn('ref_service_kode.type',[2])
                            // ->where('ref_service_detail.id_unit', $me)
                            ->where('ref_service_detail.id_service_detail', $req->id)
                           ->select('ref_service_detail.*','ref_service_kode.*')->first();
          $out .='
         <tr onclick="id_hapusjasaku(' .$item->id_service_detail.');" class="item-jasaku" data-jasaku="' .$item->id_service_detail. '">
          <td>
            <input type="hidden" value="0" name="id_service[]">
            <input type="hidden" value="1" name="status[]">
            <input type="hidden" name="tipeser[]" value="2" >
            <input type="hidden" name="service_kode[]" value="'.$item->service_kode .'">
            <input type="hidden"  min="0" data-form="jumlah_out"  class="form-control" name="ju[]" value="1" />
            <input type="hidden" class="form-control" data-form="harga_jual" name="tarif_dasar[]" value="'.$item->tarif_dasar.'"/>
            <input type="text" value="'.$item->nm_service. '" name="nm_service[]" readonly="readonly" class="form-control" required>
            <input type="hidden" readonly="readonly" data-form="total" value="'. number_format($item->tarif_dasar,0,'','') .'" name="total[]" class="form-control text-right" required>
        </td>
        ';
         if($item->kebutuhan==1):
                $out.='
                    <td>
                      <select class="form-control" id="id_dr" required name="id_dr['.$item->service_kode .'][]">
                        <option value="">Pilih Dokter</option>';
                          foreach($dokter as $ke):
                            $out .= '<option value="'.$ke->id_karyawan.'">'.$ke->nm_depan.'&nbsp;'.$ke->nm_belakang.'</option>';
                           endforeach;
            if($me==11){
                $out .= '
                    </select>
                  </td>
                    <td>
                      <select class="form-control" id="jabatan" required name="jabatan['.$item->service_kode .'][]">
                      <option value="">Pilih Jabatan Dokter </option>
                        <option value="1">DPJP</option>
                        <option value="2">Anggota/OPERATOR</option>
                      </select>
                    </td>
                    ';
                  }else{
                    $out .='<input type="hidden" value="1"name="jabatan['.$item->service_kode .'][]"> ';
                  }
            else:
                $out.='
                  <input type="hidden" name="id_dr['.$item->service_kode .'][]" value="0">
                  <input type="hidden" name="jabatan['.$item->service_kode .'][]" value="0">
                  ';
            endif;
            $out .='
        </tr>
            ';
            $res['item'] = $item;
             $res['content2'] = $out;
          return json_encode($res);
            }
        }
    public function getLoadtindakanatur1(Request $req){
      if($req->ajax()){
            $res = [];
            $out = '';
            $items=ref_service::service1($req->all())->paginate(5);          
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $out .= '
                       <tr class="paket-' . $item->id_service . '">
                          <td>' . $item->nm_service . ' <small class="pull-right hide paket-loading-' . $item->id_service . '">Memuat...</small></td>
                          <td class="text-right"><button title="Pilih Tindakan" class="btn btn-danger btn-small btn-paket-' . $item->id_service . '" onclick="add_paket(' . $item->id_service . ');"><i class="fa fa-plus"></i></button></td>
                      </tr>
                    ';
                }
            else:
                $out = '
                    <tr>
                        <td colspan="4">Tidak ditemukan</td>
                    </tr>
                ';
            endif;
            $res['total'] = $total;
            $res['content'] = $out;
            $res['pagin'] = $items->render();

            return json_encode($res);
        }
    }
// di rubah
public function getAddtindakanaturan(Request $req){
  if($req->ajax()){
    $res = [];
    $out ='';
     // $tindaka = ref_service::find($req->id);
      $tindakan = ref_service::leftJoin('ref_service_grup', 'ref_service_grup.id_grup', '=', 'ref_service.id_grup')
                         ->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
                        ->where('id_service', $req->id)
                        ->select(
                                    'ref_service.*',
                                    'ref_service_grup.grup',
                                    'ref_service_kode.nm_service',
                                    'ref_service_kode.tarif_dasar')->get();
                        foreach($tindakan as $tindakan):

        $out.= '<table class="table item-tindakanaturan" onclick="id_delete(' .$tindakan->id_service . ');" data-tindakanaturan="' .$tindakan->id_service. '">
                  <tr >
                  <td>
                    <input type="hidden" value="' .$tindakan->id_service .'" name="id_service[]">
                    <input type="hidden" value="1" name="status[]">
                    <input type="hidden" name="tipeser[]" value="2" >
                     <input type="hidden"  min="0" data-form="jumlah_out"  class="form-control" name="ju[]" value="1" />
                      <input type="hidden" class="form-control" data-form="harga_jual" name="tarif_dasar[] value="'.$tindakan->tarif_dasar.'"/>
                    <input type="hidden" value="" name="id_tindakan">
                    <input type="text" value="'.$tindakan->nm_service.'" name="service[]" readonly="readonly" class="form-control" required>
                  <input type="hidden" readonly="readonly" data-form="total" value="'. number_format($tindakan->tarif_dasar,0,'','') .'"  class="form-control" required>
                  </td>
                <td><button type="button" class="btn-resep btn-white btn" onclick="reseptretment(' .$paket->service_kode .',1);">Tambh BHP<i class="fa fa-plus"></i></button><td>
                </tr>
               ';
                endforeach;
                 $items = $tindakan->serviceitem()
                        ->join('data_barang', 'data_barang.id_barang', '=', 'ref_service_item.id_barang')
                        ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                         ->whereIn('data_barang.status',[1])
                        ->select(
                            'ref_service_item.*',
                            'data_barang.kode',
                            'data_barang.nm_barang',
                            'data_barang.harga_jual',
                            'ref_satuan.nm_satuan'
                            )
                        ->get();
                          foreach($items as $serviceitem):
                    $out.= '
                    <tr >
                    <td>'.$serviceitem->kode.'</td>
                    <td>' . $serviceitem->nm_barang . '
                        <input type="hidden" name="id_barang_item['.$tindakan->id_service.'][]" value="'.$serviceitem->id_barang.'"/>
                        <input type="hidden" data-form="harga_jual" name="harga_jual['.$tindakan->id_service.'][]" value="'.$serviceitem->harga_jual.'"/>
                      </td>
                    <td>
                        <input type="hidden" name="tipe['.$tindakan->id_service.'][]" value="1" >
                        <input type="number" min="0" data-form="jumlah_out" class="form-control" name="jumlah_out['.$tindakan->id_service.'][]" value="'.$serviceitem->qty .'" />
                      </td>
                       <td width="2%"><small>'.$serviceitem->nm_satuan.'</small></td>
                          <input type="hidden" name="id_satuan['.$tindakan->id_service.'][]" value="'.$serviceitem->id_satuan.'">
                         <input type="hidden" readonly="readonly" data-form="total" value="'.$serviceitem->harga_jual.'" name="total['.$tindakan->id_service.'][]" class="form-control text-right" required>
                    </td>
                    </tr>
               
                        ';
                         endforeach;
                         $out .='
                          <tr>
                    <td colspan="4" class="item-' .$tindakan->id_service . '"></td>
                  </tr>
                  </table>';

        $res['tindakan'] = $tindakan;
       $res['content'] = $out;
            return json_encode($res);
        }
 }
      public function getLoaditemsaturan(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $items = data_item_gudang::treatment($req->all())->paginate(5);
            //dd($items);
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $akhir =$item->in - $item->out ;
                    $btn_aktif = ($akhir>0) ? '' : 'disabled';
                    $out .= '
                        <tr class="barangaturan-' . $item->id_item_gudang . '">
                            <td>' . $item->kode . '</td>
                            <td>' . $item->nm_barang . ' <small class="pull-right hide itematuran-loading-' . $item->id_item_gudang . '">Memuat...</small></td>
                            <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itematuran-loading-loading-' . $item->id_item_gudang . '">Memuat...</small></td>
                            <td class="text-right"><button class="btn-sm btn-danger btn-itematuran-' . $item->id_item_gudang . '" onclick="add_itematuran(' . $item->id_item_gudang . ');" '.$btn_aktif.'><i class="fa fa-plus"></i></button></td>
                        </tr>
                    ';
                }
            else:
                $out = '
                    <tr>
                        <td colspan="3">Tidak ditemukan</td>
                    </tr>
                ';
            endif;

            $res['total'] = $total;
            $res['content'] = $out;
            $res['pagin'] = $items->render();

            return json_encode($res);
        }
}
public function getAdditematuran(Request $req){
     // $me = \Me::subgudang()->id_gudang;

    if($req->ajax()){

        $res = [];
        $out='';
            $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                // ->where('data_item_gudang.id_gudang', $me)
                ->where('data_item_gudang.id_item_gudang',$req->id)
                // ->where('data_item_gudang.id_barang', $req->id)
                ->whereIn('data_barang.status',[1])
                    ->select(
                     'data_item_gudang.*',
                     'data_barang.id_satuan',
                     'data_barang.kode',
                      'data_barang.nm_barang',
                      'data_barang.harga_jual',
                      'data_item_gudang.in AS masuk',
                     'data_item_gudang.out AS keluar',
                    'ref_satuan.nm_satuan'
                                         )
                ->first();
          $akhir=  $item->masuk - $item->keluar;
          $warna = ($akhir>0) ? '<span class="label label-success">' : '<span class="label label-important">';

        $res['item'] = $item;
         $res['content'] = $out;
        $res['akhir']= $akhir;
        $res['warna']=$warna;
        // dd($res);
        return json_encode($res);
        }
    }
    public function getLoadpaket(Request $req ){
      if($req->ajax()){
          $res = [];
          $out = '';
          $items = ref_service::paket($req->all())->paginate(5);
          //dd($items);

          $total = $items->total();
          if($total > 0):
              foreach($items as $item){
                  $out .= '
                      <tr class="paket-' . $item->id_service . '">
                          <td>' . $item->nm_service . ' <small class="pull-right hide paket-loading-' . $item->id_service . '">Memuat...</small></td>
                          <td class="text-right"><button title="Pilih Tindakan" class="btn btn-danger btn-small btn-paket-' . $item->id_service . '" onclick="add_paket(' . $item->id_service . ');"><i class="fa fa-plus"></i></button></td>
                      </tr>
                  ';
              }
          else:
              $out = '
                  <tr>
                      <td colspan="3">Tidak ditemukan</td>
                  </tr>
              ';
          endif;
          $res['total'] = $total;
          $res['content'] = $out;
          $res['pagin'] = $items->render();

          return json_encode($res);
        }
      }
   public function getAddpaket(Request $req){
    $me = \Me::subgudang()->id_gudang;
    if($req->ajax()){
      $res= [];
      $out ='';
      // dd($req->all());
      $dokter=data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,3,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
      $paket= ref_service::leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
                ->where('id_service', $req->id)
                ->first();
      $ittindakan=ref_service::leftJoin('ref_service_grup', 'ref_service_grup.id_grup', '=', 'ref_service.id_grup')
                              ->leftJoin('ref_service_detail', 'ref_service_detail.id_service_detail', '=', 'ref_service.id_service_detail')
                               ->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service_detail.id_service_kode')
                              ->where('parend_id', $req->id)
                              ->whereIn('ref_service.status',[1])
                              ->select(
                                    'ref_service.*',
                                    'ref_service_detail.*',
                                    'ref_service_grup.grup',
                                    'ref_service_kode.nm_service', 'ref_service_kode.persen_rs','ref_service_kode.persen_dr','ref_service_kode.service_kode','ref_service_kode.keterangan','ref_service_kode.tarif_dasar')->get();
                         $out .= '
              <table class="table item-paket" onclick="id_hapuspaket(' .$paket->id_service . ');" data-paket="' .$paket->id_service. '">
                <tbody class="content-paket">

                <tr style="border-botom:solid 2px #333;">
                    <td><h4>'.$paket->nm_service.'</h4>
                      <input type="hidden" name="id_service[]" value="'.$paket->id_service.'">
                       <input type="hidden" name="service_kode[]" value="'.$paket->service_kode.'">
                      <input type="hidden" name="tipeser[]" value="1" >
                      <input type="hidden" value="1" name="status[]">
                      <input type="hidden"  min="0"/>
                      <input type="hidden" class="form-control" />
                      <input type="hidden" name="tarif_dasar[]" value="0">
                       <input type="hidden" readonly="readonly"  value="'. number_format($paket->tarif_dasar,0,'','') .'" name="total[]" class="form-control" required>
                    </td>
                   <td></td>
                  <td class="text-right"><button type="button" class="btn-resep btn-danger btn-sm" onclick="reseptretment(' .$paket->service_kode .',1);">Tambh BHP</button><td>
                  <td>
                  <button title="Hapus paket ini" type="button" class="btn btn-danger btn-pakethapus"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  </td>
                </tr>
                </tbody>
                ';
         foreach($ittindakan as $tindakan):
             $out.= '
                  <tr >
                  <td>
                    <input type="hidden" value="' .$tindakan->id_service .'" name="id_service[]">
                    <input type="hidden" value="1" name="status[]">
                    <input type="hidden" name="tipeser[]" value="2" >
                     <input type="hidden" name="service_kode[]" value="'.$tindakan->service_kode.'">
                     <input type="hidden"  class="form-control" value="'.$tindakan->persen_dr.'"/>
                      <input type="hidden"  class="form-control" value="'.$tindakan->persen_rs.'"/>
                    <input type="hidden"  min="0"   class="form-control" name="ju[]" value="1" />
                    <input type="hidden" class="form-control"  name="tarif_dasar[]" value="'.$tindakan->tarif_dasar.'"/>
                    <input type="text" value="'.$tindakan->nm_service.'" name="nm_service[]" readonly="readonly" class="form-control" required>
                  </td>
                    ';
            if($tindakan->kebutuhan==1):
                $out.='
                    <td>
                      <select class="form-control" id="id_dr" required name="id_dr['.$tindakan->service_kode.'][]">
                        <option value="">Pilih Dokter</option>';
                          foreach($dokter as $ke):
                            $out .= '<option value="'.$ke->id_karyawan.'">'.$ke->nm_depan.'&nbsp;'.$ke->nm_belakang.'</option>';
                           endforeach;
               
                $out .= '
                    </select>
                  </td>
                    <td>
                      <select class="form-control" id="jabatan" required name="jabatan['.$tindakan->service_kode.'][]">
                      <option value="">Pilih Jabatan Dokter </option>
                        <option selected="selected" value="1">DPJP</option>
                        <option value="2">Anggota/OPERATOR</option>
                      </select>
                    </td>
                    ';
                     $out.='
                    <input type="hidden" name="tarif['.$tindakan->service_kode.'][]" value="0">
                  </td>
                  
                    <td>
                      <input type="hidden" readonly="readonly" value="'. number_format($tindakan->tarif_dasar,0,'','') .'" name="total[]" class="form-control text-right" required>
                      <button type="button" class="btn btn-danger btn-sm "  onclick="add_dokter(' .$tindakan->service_kode.',1);">Tambah Dokter</button>
                    </td>
                </tr>
                  <tr>
                      <td colspan="4" class="contn-' .$tindakan->service_kode.'"></td>
                  </tr>
               ';

            else:
                $out.='
                  <input type="hidden" name="id_dr['.$tindakan->service_kode.'][]" value="0">
                  <input type="hidden" name="jabatan['.$tindakan->service_kode.'][]" value="0">
                  ';
            endif;
               
                 endforeach;
                 $items = $paket->serviceitem()
                        ->join('data_barang', 'data_barang.id_barang', '=', 'ref_service_item.id_barang')
                        ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                        ->leftJoin('data_item_gudang', 'data_item_gudang.id_barang', '=', 'data_barang.id_barang')
                        ->whereIn('ref_service_item.status',[1])
                        ->where('data_item_gudang.id_gudang',$paket->id_unit)
                         ->whereIn('data_barang.status',[1])
                        ->select(
                            'ref_service_item.*',
                            'data_barang.kode',
                            'data_barang.nm_barang',
                            'data_barang.harga_jual',
                            'ref_satuan.nm_satuan',
                            'data_item_gudang.in',
                            'data_item_gudang.out',
                            // 'data_barang.reuse',
                            'data_item_gudang.id_gudang',
                            'data_item_gudang.id_item_gudang'
                            )
                        ->get();
                        $out .='
                            <tr>
                              <td>Kode</td><td>Barang</td><td>qty/satuan</td><td>Reuse </td><td>Stok</td><td>Aksi<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="jika ada BHP yang tidak digunakan silahkan hapus dengan tombol merah di bawah ini"><i class="glyphicon glyphicon-question-sign"></i></a></td>
                            </tr>
                        ';
                          foreach($items as $serviceitem):
                            $akhir=  $serviceitem->in - $serviceitem->out;
                           $warna = ($akhir>0) ? '<span class="label label-success">' : '<span class="label label-important">';

                    $out.= '
                    <tr class="baris_bhp">
                    <td>'.$serviceitem->kode.' '.$serviceitem->id_gudang.'</td>
                    <td>' . $serviceitem->nm_barang . '
                    <input type="hidden" name="id_item_gudang['.$paket->service_kode.'][]" value="'.$serviceitem->id_item_gudang.'">
                        <input type="hidden" name="id_barang_item['.$paket->service_kode.'][]" value="'.$serviceitem->id_barang.'"/>
                        <input type="hidden" name="harga_jual['.$paket->service_kode.'][]" value="'.$serviceitem->harga_jual.'"/>
                      </td>
                     <td class="col-sm-3">
                       <input type="hidden" name="tipe['.$paket->service_kode.'][]" value="1" >
                    <div class="input-group input-group-sm">
                        <input type="number"   min="1" onchange="changeqty(this.value, ' .$akhir. ');"  value="'.$serviceitem->qty .'"  name="jumlah_out['.$paket->service_kode.'][]"  class="form-control text-left"  required />
                        <span class="input-group-addon ">'.$serviceitem->nm_satuan.'</span>
                        <input type="hidden" name="id_satuan['.$paket->service_kode.'][]" value="'.$serviceitem->id_satuan.'">
                    </div>
                        <input type="hidden" readonly="readonly" value="'. number_format($serviceitem->harga_jual,0,'','') .'" name="total['.$paket->service_kode.'][]" class="form-control text-right" required>
                    </td>
                    <td>
                        <input type="radio"  name="pakek['.$paket->service_kode.'][]'.$serviceitem->id_service_item.'"  value="1">Ya
                       <input type="radio"  name="pakek['.$paket->service_kode.'][]'.$serviceitem->id_service_item.'"  checked="checked" value="0">Tidak<br>
                    </td>
                    <td>'.$warna.''.$akhir.'&nbsp; '.$serviceitem->nm_satuan.'</spin></td>
                       <input type="hidden" name="stok['.$paket->service_kode.'][]" value="'.$akhir.'">
                    <td><button  type="button" title="Hapus BHP  '.$serviceitem->nm_barang.' ini jika tidak di pakai" class="btn btn-danger btn-hapusbhp34"><i class="fa fa-trash"></i></button></td>
                    </tr>
                        ';
                      endforeach;
                      $out .='
                    <tr>
                      <td colspan="10" class="item-' .$paket->service_kode. '"></td>
                  </tr>';
             $res['paket'] = $paket;
            $res['content'] = $out;
            return json_encode($res);
        }
 }

 public function getAdddokter(Request $req)
  {
   if($req->ajax()){
        $res = [];
         $dokters = data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
          $out = '';
          $out .='<option value="">Pilih Dokter</option>';
         foreach($dokters as $dokter):
            $out .= '<option value="'.$dokter->id_karyawan.'">'.$dokter->nm_depan.'&nbsp;'.$dokter->nm_belakang.'</option>';
           endforeach;
          $res['dokter'] = $out;
          return json_encode($res);
    }
  }
  public function getLoadbhp(Request $req  ){
    if($req->ajax()){
        $res = [];
        $out = '';
        $items = data_item_gudang::treatment($req->all())->paginate(5);
        $total = $items->total();
        if($total > 0):
          
            foreach($items as $item){
               $akhir =$item->in - $item->out ;
                $btn_aktif = ($akhir>0) ? '' : 'disabled';
                $out .= '<tr class="barang1-' . $item->id_barang . '">
                        <td>' . $item->kode . '</td>
                        <td>' . $item->nm_barang . ' <small class="pull-right hide itembarang1-loading-' . $item->id_barang . '">Memuat...</small></td>
                        <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itembarang1-loading-loading-' . $item->id_barang . '">Memuat...</small></td>
                        <td class="text-right"><button class="btn btn-white btn-small btn-itembarang1-' . $item->id_barang . '" onclick="add_bhp(' . $item->id_barang . ');" '.$btn_aktif.'><i class="fa fa-plus"></i></button></td>
                    </tr>';
                }
            else:
                $out = '
                    <tr>
                        <td colspan="3">Tidak ditemukan</td>
                    </tr>
                ';
            endif;
        $res['total'] = $total;
        $res['out'] = $out;
        $res['content'] = $out;
        $res['pagin'] = $items->render();
        //  //dd($res);
        return json_encode($res);
        }
}
public function getAddbhp(Request $req){
     $me = \Me::subgudang()->id_gudang;
    if($req->ajax()){
        $res = [];
        $out='';
            $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                // ->where('data_item_gudang.id_gudang', $me)
                 ->whereIn('data_barang.status',[1])
                // ->where('data_item_gudang.id_gudang', 26)
                ->where('data_item_gudang.id_barang', $req->id)
                    ->select(
                     'data_item_gudang.*',
                     'data_barang.id_satuan',
                     'data_barang.kode',
                      'data_barang.nm_barang',
                      'data_barang.harga_jual',
                      'data_item_gudang.in AS masuk',
                     'data_item_gudang.out AS keluar',
                    'ref_satuan.nm_satuan'
                                         )
                ->first();
        $res['item'] = $item;
         $res['content'] = $out;
        // dd($res);
        return json_encode($res);

        }
    }
 
public function getLoadpaketupdate(Request $req ){
      if($req->ajax()){
          $res = [];
          $out = '';
          $items = ref_service::paket($req->all())->paginate(5);
          //dd($items);

          $total = $items->total();
          if($total > 0):
              foreach($items as $item){
                  $out .= '
                      <tr class="paket-' . $item->id_service . '">
                          <td>' . $item->nm_service . ' <small class="pull-right hide paket-loading-' . $item->id_service . '">Memuat...</small></td>
                          <td class="text-right"><button title="Pilih Tindakan" class="btn btn-danger btn-small btn-paket-' . $item->id_service . '" onclick="add_paketupdate(' . $item->id_service . ');"><i class="fa fa-plus"></i></button></td>
                      </tr>
                  ';
              }
          else:
              $out = '
                  <tr>
                      <td colspan="3">Tidak ditemukan</td>
                  </tr>
              ';
          endif;
          $res['total'] = $total;
          $res['content'] = $out;
          $res['pagin'] = $items->render();

          return json_encode($res);
        }
      }
      public function getAddpaketupdate(Request $req){
    $me = \Me::subgudang()->id_gudang;
    if($req->ajax()){
      $res= [];
      $out ='';
      // dd($req->all());
      $dokter=data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,3,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
      $paket= ref_service::leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
                ->where('id_service', $req->id)
                ->first();
      $ittindakan=ref_service::leftJoin('ref_service_grup', 'ref_service_grup.id_grup', '=', 'ref_service.id_grup')
                              ->leftJoin('ref_service_detail', 'ref_service_detail.id_service_detail', '=', 'ref_service.id_service_detail')
                               ->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service_detail.id_service_kode')
                              ->where('parend_id', $req->id)
                              ->whereIn('ref_service.status',[1])
                              ->select(
                                    'ref_service.*',
                                    'ref_service_detail.*',
                                    'ref_service_grup.grup',
                                    'ref_service_kode.nm_service', 'ref_service_kode.persen_rs','ref_service_kode.persen_dr','ref_service_kode.service_kode','ref_service_kode.keterangan','ref_service_kode.tarif_dasar')->get();
                         $out .= '
              <table class="table item-paket-update" onclick="id_hapuspaket(' .$paket->id_service . ');" data-paket="' .$paket->id_service. '">
                <tbody class="content-paket">

                <tr style="border-botom:solid 2px #333;">
                    <td><h4>'.$paket->nm_service.'</h4>
                      <input type="hidden" name="id_service_paket[]" value="'.$paket->id_service.'">
                       <input type="hidden" name="service_kode_paket[]" value="'.$paket->service_kode.'">
                      <input type="hidden" name="tipeser_paket[]" value="1" >
                      <input type="hidden" value="1" name="status_paket[]">
                      <input type="hidden" name="tarif_dasar_paket[]" value="0">
                       <input type="hidden" readonly="readonly"  value="'. number_format($paket->tarif_dasar,0,'','') .'" name="total_paket[]" class="form-control" required>
                    </td>
                   <td></td>
                  <td class="text-right"><button type="button" class="btn-resep btn-danger btn-sm" onclick="addbhppaket(' .$paket->service_kode .',1);">Tambh BHP</button><td>
                  <td>
                  <button title="Hapus paket ini" type="button" class="btn btn-danger btn-paket-update"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  </td>
                </tr>
                </tbody>
                ';
         foreach($ittindakan as $tindakan):
             $out.= '
                  <tr >
                  <td>
                    <input type="hidden" value="' .$tindakan->id_service .'" name="id_service_paket[]">
                    <input type="hidden" value="1" name="status_paket[]">
                    <input type="hidden" name="tipeser_paket[]" value="2" >
                     <input type="hidden" name="service_kode_paket[]" value="'.$tindakan->service_kode.'">
                     <input type="hidden"  class="form-control" value="'.$tindakan->persen_dr.'"/>
                      <input type="hidden"  class="form-control" value="'.$tindakan->persen_rs.'"/>
                    <input type="hidden"  min="0"   class="form-control" name="ju[]" value="1" />
                    <input type="hidden" class="form-control"  name="tarif_dasar_paket[]" value="'.$tindakan->tarif_dasar.'"/>
                    <input type="text" value="'.$tindakan->nm_service.'" name="nm_service[]" readonly="readonly" class="form-control" required>
                  </td>
                    ';
            if($tindakan->kebutuhan==1):
                $out.='
                    <td>
                      <select class="form-control" id="id_dr_paket" required name="id_dr_paket['.$tindakan->service_kode.'][]">
                        <option value="">Pilih Dokter</option>';
                          foreach($dokter as $ke):
                            $out .= '<option value="'.$ke->id_karyawan.'">'.$ke->nm_depan.'&nbsp;'.$ke->nm_belakang.'</option>';
                           endforeach;
               
                $out .= '
                    </select>
                  </td>
                    <td>
                      <select class="form-control" id="jabatan" required name="jabatan_paket['.$tindakan->service_kode.'][]">
                      <option value="">Pilih Jabatan Dokter </option>
                        <option selected="selected" value="1">DPJP</option>
                        <option value="2">Anggota/OPERATOR</option>
                      </select>
                    </td>
                    ';
                     $out.='
                    <input type="hidden" name="tarif_paket['.$tindakan->service_kode.'][]" value="0">
                  </td>
                  
                    <td>
                      <input type="hidden" readonly="readonly" value="'. number_format($tindakan->tarif_dasar,0,'','') .'" name="tota_paketl[]" class="form-control text-right" required>
                      <button type="button" class="btn btn-danger btn-sm "  onclick="add_dokter(' .$tindakan->service_kode.',1);">Tambah Dokter</button>
                    </td>
                </tr>
                  <tr>
                      <td colspan="4" class="contn-' .$tindakan->service_kode.'"></td>
                  </tr>
               ';

            else:
                $out.='
                  <input type="hidden" name="id_dr_paket['.$tindakan->service_kode.'][]" value="0">
                  <input type="hidden" name="jabatan_paket['.$tindakan->service_kode.'][]" value="0">
                  ';
            endif;
               
                 endforeach;
                 $items = $paket->serviceitem()
                        ->join('data_barang', 'data_barang.id_barang', '=', 'ref_service_item.id_barang')
                        ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                        ->leftJoin('data_item_gudang', 'data_item_gudang.id_barang', '=', 'data_barang.id_barang')
                        ->whereIn('ref_service_item.status',[1])
                        ->where('data_item_gudang.id_gudang',$paket->id_unit)
                         ->whereIn('data_barang.status',[1])
                        ->select(
                            'ref_service_item.*',
                            'data_barang.kode',
                            'data_barang.nm_barang',
                            'data_barang.harga_jual',
                            'ref_satuan.nm_satuan',
                            'data_item_gudang.in',
                            'data_item_gudang.out',
                            // 'data_barang.reuse',
                            'data_item_gudang.id_gudang',
                            'data_item_gudang.id_item_gudang'
                            )
                        ->get();
                        $out .='
                            <tr>
                              <td>Kode</td><td>Barang</td><td>qty/satuan</td><td>Reuse </td><td>Stok</td><td>Aksi<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="jika ada BHP yang tidak digunakan silahkan hapus dengan tombol merah di bawah ini"><i class="glyphicon glyphicon-question-sign"></i></a></td>
                            </tr>
                        ';
                          foreach($items as $serviceitem):
                            $akhir=  $serviceitem->in - $serviceitem->out;
                           $warna = ($akhir>0) ? '<span class="label label-success">' : '<span class="label label-important">';

                    $out.= '
                    <tr class="baris_bhp">
                    <td>'.$serviceitem->kode.' '.$serviceitem->id_gudang.'</td>
                    <td>' . $serviceitem->nm_barang . '
                    <input type="hidden" name="id_item_gudang_paket['.$paket->service_kode.'][]" value="'.$serviceitem->id_item_gudang.'">
                        <input type="hidden" name="id_barang_item_paket['.$paket->service_kode.'][]" value="'.$serviceitem->id_barang.'"/>
                        <input type="hidden" name="harga_jual_paket['.$paket->service_kode.'][]" value="'.$serviceitem->harga_jual.'"/>
                      </td>
                     <td class="col-sm-3">
                       <input type="hidden" name="tipe_paket['.$paket->service_kode.'][]" value="1" >
                    <div class="input-group input-group-sm">
                        <input type="number"   min="1" onchange="changeqty(this.value, ' .$akhir. ');"  value="'.$serviceitem->qty .'"  name="jumlah_out_paket['.$paket->service_kode.'][]"  class="form-control text-left"  required />
                        <span class="input-group-addon ">'.$serviceitem->nm_satuan.'</span>
                        <input type="hidden" name="id_satuan_paket['.$paket->service_kode.'][]" value="'.$serviceitem->id_satuan.'">
                    </div>
                        <input type="hidden" readonly="readonly" value="'. number_format($serviceitem->harga_jual,0,'','') .'" name="total_paket['.$paket->service_kode.'][]" class="form-control text-right" required>
                    </td>
                    <td>
                        <input type="radio"  name="pakek_paket['.$paket->service_kode.'][]'.$serviceitem->id_service_item.'"  value="1">Ya
                       <input type="radio"  name="pakek_paket['.$paket->service_kode.'][]'.$serviceitem->id_service_item.'"  checked="checked" value="0">Tidak<br>
                    </td>
                    <td>'.$warna.''.$akhir.'&nbsp; '.$serviceitem->nm_satuan.'</spin></td>
                       <input type="hidden" name="stok_paket['.$paket->service_kode.'][]" value="'.$akhir.'">
                    <td><button  type="button" title="Hapus BHP  '.$serviceitem->nm_barang.' ini jika tidak di pakai" class="btn btn-danger btn-hapusbhp34"><i class="fa fa-trash"></i></button></td>
                    </tr>
                        ';
                      endforeach;
                      $out .='
                    <tr>
                      <td colspan="10" class="itembhp-' .$paket->service_kode. '"></td>
                  </tr>';
             $res['paket'] = $paket;
            $res['content'] = $out;
            return json_encode($res);
        }
 }
      public function getLoadbhppaket(Request $req){
            if($req->ajax()){
                $res = [];
                $out = '';
                $items = data_item_gudang::treatment($req->all())->paginate(5);
                //dd($items);
                $total = $items->total();
                if($total > 0):
                    foreach($items as $item){
                        $akhir =$item->in - $item->out ;
                        $btn_aktif = ($akhir>0) ? '' : 'disabled';
                        $out .= '
                            <tr class="update-' . $item->id_item_gudang . '">
                                <td>' . $item->kode . '</td>
                                <td>' . $item->nm_barang . ' <small class="pull-right hide itemupdate-loading-' . $item->id_item_gudang. '">Memuat...</small></td>
                                <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itemupdate-loading-loading-' . $item->id_item_gudang . '">Memuat...</small></td>
                                <td class="text-right"><button class="btn-sm btn-danger btn-itemupdate-' . $item->id_item_gudang . '" onclick="add_bhppaket(' . $item->id_item_gudang . ');" '.$btn_aktif.'><i class="fa fa-plus"></i></button></td>
                            </tr>
                        ';
                    }
                else:
                    $out = '
                        <tr>
                            <td colspan="3">Tidak ditemukan</td>
                        </tr>
                    ';
                endif;

                $res['total'] = $total;
                $res['content'] = $out;
                $res['pagin'] = $items->render();

                return json_encode($res);
            }
        }
}

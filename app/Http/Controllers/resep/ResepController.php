<?php

namespace App\Http\Controllers\resep;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\resep\CreateResepJob;
use App\Jobs\resep\InsertPasienMysqlJob;
use App\Jobs\resep\UpdateResepJob;
use App\Jobs\resep\AmbilObatJob;
use App\Jobs\resep\retur\ReturresepJob;

use App\Models\data_barang;
use App\Models\ref_kategori;
use App\Models\ref_resep_aturan;
use App\Models\data_item_gudang;
use App\Models\data_resep;
use App\Models\dokter;
use App\Models\data_karyawan;
use App\Models\data_resep_item;
use App\Models\data_pasien;
use App\Models\data_resep_campur;
use App\Models\data_retur_resep;
use App\Models\data_retur_resep_item;
use DB;
use Mssql;

class ResepController extends Controller
{
    public function getIndex(){

        $items= data_resep::byidpasien()->paginate(10);
         $status = [
            0 => '',
            1 => 'Belum Lunas',
            2 => 'Lunas'
        ];
           return view('Pelayanan.resep.viewresep', [
            'items' => $items,
            'status' => $status
        ]);

    }
    public function getAmbil($id){
         $data = data_resep::hid($id);

        if($data->count() == 0)
            return redirect('/resep');
              $data = data_resep::hid($id)->first();
              $pasiendetail= data_resep_item::byacc($id)->get();
        $pakais = ref_resep_aturan::all();
        return view('Pelayanan.resep.ambil',[
            'data' =>$data,
            'pasiendetail'=> $pasiendetail,
            'pakais'    =>$pakais
            ]);
    }
    public function postAmbil(Request $req){

        $arr = $this->dispatch(new AmbilObatJob($req->all()));
        if($arr['res'])
            return redirect('/resep')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        else
            return redirect()->back()->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
    }
    public function getObatpaten() {
        $pakais = ref_resep_aturan::get();
        $m= data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,4,8,9,10,11])->orderBy('nm_depan', 'asc')->get();
        return view('Pelayanan.resep.buatresep', [
            'dokter'=>$m,
            'pakai'=>$pakais
        ]);
    }

    public function getLoaditems(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $items = data_item_gudang::resepitems($req->all())->paginate(5);
            //dd($items);
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $akhir =$item->in - $item->out ;
                    $out .= '
                        <tr class="barang-' . $item->id_barang . '">
                            <td>' . $item->kode . '</td>
                            <td>' . $item->nm_barang . ' <small class="pull-right hide item-loading-' . $item->id_barang . '">Memuat...</small></td>
                            <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide item-loading-' . $item->id_barang . '">Memuat...</small></td>
                            <td class="text-right"><button class="btn btn-white btn-small btn-item-' . $item->id_barang . '" onclick="add_item(' . $item->id_barang . ');"><i class="fa fa-plus"></i></button></td>
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

    // load obat campur
     public function getLoadcam(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $items = data_item_gudang::resepitems($req->all())->paginate(5);
            //dd($items);
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $akhir =$item->in - $item->out ;
                    $out .= '
                        <tr class="barangc-' . $item->id_barang . '">
                            <td>' . $item->kode . '</td>
                            <td>' . $item->nm_barang . ' <small class="pull-right hide itemc-loading-' . $item->id_barang . '">Memuat...</small></td>
                            <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itemc-loading-' . $item->id_barang . '">Memuat...</small></td>
                            <td class="text-right"><button class="btn btn-white btn-small btn-itemc-' . $item->id_barang . '" onclick="add_itemc(' . $item->id_barang . ');"><i class="fa fa-plus"></i></button></td>
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
        public function getAdditem(Request $req){
         $me = \Me::subgudang();

        if($req->ajax()){

            $res = [];

                $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                    ->where('data_item_gudang.id_gudang', $me->id_gudang)
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
            $pakais = ref_resep_aturan::get();
            $out = '';
            foreach($pakais as $pakai){
                $out .= '<option value="' . $pakai->id_resep_aturan . '">' . $pakai->resep_aturan . '</option>';
            }
            $stok=($item->masuk)-($item->keluar);
                if($stok < 4){
                    $class= 'label-important';
                }else{
                    $class='label-success';
                }

            $res['item'] = $item;
            $res['pakai'] = $out;
            $res['stok']= $stok;
            $res['class']=$class;
            // dd($res);
            return json_encode($res);

        }
    }


     public function getAdditemc(Request $req){
         $me = \Me::subgudang();
        if($req->ajax()){
            $res = [];
            $out = '';
                $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                    ->where('data_item_gudang.id_gudang', $me->id_gudang)
                    // ->where('data_item_gudang.id_gudang',26)
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
    //untk menampilkan data pasien di modal//
    public function getLoadpa(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            // $items =  \MSSQL::tbl("PASIEN")->paginate(5);
            $items = \MSSQL::tbl("PASIEN")->where('NAMA_PASIEN', 'LIKE', '%'.$req['NAMA_PASIEN'].'%')->paginate(4);
            if(!empty($req['ID_PASIEN']))
            $items= \MSSQL::tbl("PASIEN")->where('ID_PASIEN','LIKE', '%' . $req['ID_PASIEN'] . '%')->paginate(4);
           //dd($items);
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $out .= '
                        <tr class="pa-' . $item->ID_PASIEN . '">
                            <td>' . $item->ID_PASIEN . '</td>
                            <td>' . $item->NAMA_PASIEN . '<small class="pull-right  pasien-loading-'.$item->ID_PASIEN.'Memuat....</small></td>
                            <td class="text-right"><button onclick="add_itempa(\'' . $item->ID_PASIEN . '\');" class="btn btn-pasien-' . $item->ID_PASIEN . ' btn-white btn-small"><i class="fa fa-plus"></i></button></td>
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
    /* nambahkan dari Mssql--holil*/
    public function getAdditempa(Request $req){
        if($req->ajax()){
            $res = [];
            $out = [];
            $pa = \MSSQL::tbl("PASIEN")->where('ID_PASIEN',$req->id)->first();
        $pasien = $this->dispatch(new InsertPasienMysqlJob($pa));
               $res['pa'] = $pasien;
                $res['pas'] = $pa;
                 $res['tgl']= \Format::indoDate2($pa->TGLLAHIR_PASIEN);
            return json_encode($res);
        }
    }
    /*---------nambahkan dari Mysql data pasien- Holil--------*/
    public function getAdditempamy(Request $req){
        if($req->ajax()){
            $res = [];
            $out = [];
            $pa = data_pasien::where('ID_PASIEN',$req->id)->first();
            $res['pa'] = $pa;
            return json_encode($res);
        }
    }
    public function postResep(Request $req){
        if((count($req->id_barang) == 0 )&&(count($req->id_barang_campur)==0)||(count($req->id_pasien)==0))
            return redirect()->back()->withNotif([
                'label' => 'danger',
                'err' => '<center>OOps!, Anda Belum Memilih Obat </center>'
            ]);

        $arr = $this->dispatch(new CreateResepJob($req->all()));
        if($arr['res'])
            return redirect('/resep/index')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        else
            return redirect()->back()->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        }

    public function getDetailresep($id){
      $data=data_resep::hid($id);
      if($data->count() == 0)
          return redirect('/resep');
        $data=data_resep::hid($id)->first();
        $pasiendetail = data_resep_item::byid($id)->get();
        return view('Pelayanan.resep.detailresep',[
            'data' =>$data,
            'pasiendetail'=> $pasiendetail,
            ]);
    }

      public function getAccresep($id){
        $data = data_resep::hid($id);

        if($data->count() == 0)
            return redirect('/resep');
              $data = data_resep::hid($id)->first();
              $pasiendetail= data_resep_item::byacc($id)->get();
        $pakais = ref_resep_aturan::all();
        return view('Pelayanan.resep.formacc',[
            'data' =>$data,
            'pasiendetail'=> $pasiendetail,
            'pakais'    =>$pakais
            // 'campur' =>$campur
            ]);
    }

    public function postAccresep(Request $req){
        // dd($req->all());
         if(count($req->id_barang) == 0 )
            return redirect()->back()->withNotif([
                'label' => 'danger',
                'err' => '<center>OOps!, Barang Kosong </center>'
            ]);
        $total = [];
        foreach($req->jumlah_out as $jumlah_out){
            if(!empty($jumlah_out))
                $total[] = $jumlah_out;
        }
         if(count($total) < 1)
            return redirect()->back()->withNotif([
                'label' => 'warning',
                'err' => 'OOpps Tidak boleh Semuanya 0 atau Kosong !'
            ]);

        $err= $this->dispatch(new UpdateResepJob($req->all()));

        return redirect('/resep')->withNotif([
            'label' => $err['label'],
            'err' => $err['err']
            ]);

    }
    public function postDestroy(Request $req){
        if($req->ajax()){
            data_resep_item::find($req->id)->delete();
            return json_encode([
                'result' => true
            ]);
        }
    }
    public function postDestroycampur(Request $req){
        if($req->ajax()){
            data_resep_campur::find($req->id)->delete();
            return json_encode([
                'result' => true
            ]);
        }
    }
     public function postUbahstatus(Request $req){
        // dd($req->all());
        if($req->ajax()){
            data_resep::find($req->id)->update([
                'status' => 1
            ]);
            return json_encode([
                'result' => true
            ]);
        }
    }
    public function getObatcampur(){
           $m = \MSSQL::tbl("dokter")->get();
        return view('Pelayanan.resep.buatobatcampur', [
            'dokter'=>$m,
        ]);
    }
    public function getAllresep(Request $req){
       // dd($req->all());
        if($req->ajax()){
            $result = [];
            $items = data_resep::byidpasien($req->id_pasien_hc,$req->nama_pasien, $req->nomor_resep, $req->status, $req->status_resep, $req->all())->paginate($req->limit);
            $out = '';

            $total = $items->total();
            $status = [
               0 => '',
               1 => 'Belum Lunas',
               2 => 'Lunas',

           ];

            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                    if ($item->status_resep < 1):
                        $menunggu='<span class="label label-warning">Menunggu </span><br><br>';
                        $ambil='<a href="'. url('/resep/ambil/'.$item->id_resep).'" class="text-danger"><span class="label label-success">Serah Terima</span></a>';
                    else:
                        $menunggu='<span class="label label-success">Selesai </span><br>';
                        $ambil='';
                    endif;
                    $delete=$item->status_resep < 1 ?'
                     	<a href="' . url('/resep/accresep/'.$item->id_resep). '">Edit &middot; </a>': '';
                    $out .= '
                        <tr class="sr_' . $item->id_resep . '">
                            <td>' . $no . '</td>
                            <td width="20%">
                                <div> ' .  $item->nomor_resep. '</div>
                                <div class="link text-muted">
                                    <small>
                                            '. $delete . '<a href="'. url('/resep/detailresep/'.$item->id_resep). '">Lihat &middot; </a>
                                    </small>
                                </div>
                            </td>
                            <td>
                               ' . $item->id_pasien_hc. '
                                <div class="text-muted"><small>' . \Format::indoDate2($item->created_at) . '
                                ' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small></div>
                            </td>
                            <td>'.$item->nama_pasien.' </td>
                            <td>' . $item->nm_depan .''.$item->nm_belakang. '</td>
                            <td class="text-center">' .$status[$item->status] . '</td>
                            <td>'.$menunggu.' '.$ambil.' <br><br>
                            <a href="'. url('/resep/retur/'.$item->id_resep) .'" class="text-danger"><span class="label label-info">Retur Barang</span></a>
                            </td>
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

            $result['data']  = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/resep');
        }

    }

    public function getPrint($id){
    $data=data_resep::hid($id);
      if($data->count() == 0)
          return redirect('/resep');
        $data=data_resep::hid($id)->first();
        $pasiendetail = data_resep_item::byid($id)->get();
        return view('Print.Pelayanan.Resep.ResepEtiket1',[
            'data' =>$data,
            'pasiendetail'=> $pasiendetail,
            ]);
    }
    public function getPrintetiket($id){
             $data=data_resep::hid($id);
      if($data->count() == 0)
          return redirect('/resep');
        $data=data_resep::hid($id)->first();
        $pasiendetail = data_resep_item::etiket($id)->get();
        return view('Print.Pelayanan.Resep.PrintEtiket',[
            'pasiendetail'=> $pasiendetail,
            'data'      =>$data,
            ]);

    }
    /// tambhan obat campur

    public function getLoadbhp(Request $req  ){
    if($req->ajax()){
        $res = [];
        $out = '';
        $items = data_item_gudang::resepitems($req->all())->paginate(5);
        $total = $items->total();
        if($total > 0):
            foreach($items as $item){
                $akhir =$item->in - $item->out ;
                $out .= '<tr class="barangaturan-' . $item->id_barang . '">
                        <td>' . $item->kode . '</td>
                        <td>' . $item->nm_barang . ' <small class="pull-right hide itematuran-loading-' . $item->id_barang . '">Memuat...</small></td>
                        <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itematuran-loading-loading-' . $item->id_barang . '">Memuat...</small></td>
                        <td class="text-right"><button class="btn btn-white btn-small btn-itematuran-' . $item->id_barang . '" onclick="add_obtcampur(' . $item->id_barang . ');"><i class="fa fa-plus"></i></button></td>
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
         //dd($res);
        return json_encode($res);
        }
    }
     public function getAddobtcampur(Request $req){
         $me = \Me::subgudang();
        if($req->ajax()){
            $res = [];
            $out = '';
                $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                    ->where('data_item_gudang.id_gudang', $me->id_gudang)
                    // ->where('data_item_gudang.id_gudang',26)
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
     public function getRetur($id){
         $data = data_resep::hid($id);

        if($data->count() == 0)
            return redirect('/resep');
              $data = data_resep::hid($id)->first();
              $pasiendetail= data_resep_item::byacc($id)->get();
        $pakais = ref_resep_aturan::all();
        return view('Pelayanan.resep.retur.create',[
            'data' =>$data,
            'pasiendetail'=> $pasiendetail,
            'pakais'    =>$pakais
            ]);
    }
    public function postRetur(Request $req){

          $total = [];
        foreach($req->qty_retur as $qty_retur){
            if(!empty($qty_retur))
                $total[] = $qty_retur;
        }

         if(count($total) < 1)
            return redirect()->back()->withNotif([
                'label' => 'warning',
                'err' => 'OOpps Kolom Qty Retur tidak boleh semuanya 0 atau Kosong !'
            ]);

         $arr = $this->dispatch(new ReturresepJob($req->all()));
        if($arr['res'])
            return redirect('/resep/hasil')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        else
            // return redirect()->back()->withNotif([
             return redirect('/resep/hasil')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
    }
    public function getHasil(Request $req){
        $items=data_retur_resep::retur()->paginate(10);
        $status = [
            0 => '',
            1 => 'Baru',
            2 => 'Selesai'
        ];
        return view('Pelayanan.resep.retur.index',[
            'items'  =>$items,
            'status' =>$status
            ]);
    }
    public function getAllretur(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $items = data_retur_resep::retur($req->all())->paginate($req->limit);
              $status = [
                        0 => '',
                        1 => 'Baru',
                        2 => 'Selesai'
                    ];
            if($items->total() > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                    $out .= '
                        <tr>
                            <td>' . $no . '</td>
                            <td>
                                ' . $item->no_retur_resep . '
                                <div class="link">
                                    <small>[
                                            <a target="_blank" href="' . url('/resep/printresep/' . $item->id_retur_resep ) . '">Print</a>
                                        ]
                                    </small>
                                </div>
                            </td>
                            <td>'.$item->nomor_resep.'</td>
                            <td>
                                '.$item->nama_pasien.'
                                    <small class="text-muted"><br>
                                            '.$item->id_pasien_hc.'
                                    </small>
                            </td>
                            <td>' . \Format::hari($item->tanggal) . ', ' . \Format::indoDate($item->tanggal) . '</td>
                            <td>
                                '. $status[$item->status] .'
                            </td>
                        </tr>
                    ';
                    $no++;
                }
               else:
                $out = '
                    <tr>
                        <td colspan="4">Tidak ditemukan</td>
                    </tr>
               ';
               endif;

            $res['content'] = $out;
            $res['pagin'] = $items->render();

            return json_encode($res);
        }
    }
     public function getPrintresep($id){
        if(empty($id) || !is_numeric($id))
            return redirect('/resep/hasil')->withNotif([
                'label' => 'danger',
                'err' => 'Tidak ditemukan!'
            ]);

        $retur =data_retur_resep::join('data_resep', 'data_resep.id_resep', '=', 'data_retur_resep.id_resep')
                        ->join('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_retur_resep.id_gudang')
                        ->join('data_karyawan', 'data_karyawan.id_karyawan', '=', 'data_retur_resep.id_karyawan')
                        ->join('data_pasien', 'data_pasien.id_pasien', '=', 'data_retur_resep.id_pembeli')
                         ->where('id_retur_resep',$id)
                        ->select('data_retur_resep.*','data_pasien.nama_pasien', 'data_pasien.id_pasien_hc',
                                'data_resep.nomor_resep','data_karyawan.nm_depan', 'data_karyawan.nm_belakang')->first();;

        $items = data_retur_resep_item::join('data_barang','data_retur_resep_item.id_barang','=','data_barang.id_barang')
            ->join('ref_satuan','ref_satuan.id_satuan','=','data_retur_resep_item.id_satuan')
            ->where('data_retur_resep_item.id_retur_resep',$id)
            ->get();

        return view('Print.Pelayanan.resep.retur.retur', [
            'retur' => $retur,
            'items' => $items
        ]);
    }

}

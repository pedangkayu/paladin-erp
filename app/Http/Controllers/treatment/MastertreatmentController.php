<?php

namespace App\Http\Controllers\treatment;

use Illuminate\Http\Request;
use App\Jobs\treatment\InsertMasterJob;
use App\Jobs\treatment\AdditemJob;
use App\Jobs\treatment\UpdateMasterJob;
use App\Jobs\RefCoa\UpdatekodeserviceJob;
use App\Jobs\Treatment\InserPelayananJob;
use App\Jobs\Treatment\MastertindakanJob;
use App\Models\ref_service;
use App\Models\data_item_gudang;
use App\Models\ref_service_grup;
use App\Models\ref_coa;
use App\Models\ref_service_kode;
use App\Models\ref_service_item;
use App\Models\ref_service_detail;
use App\Models\ref_gudang;
use App\Models\data_barang;
use App\Models\data_karyawan;

use App\Jobs\Paket\InsertPaketJob;
use App\Jobs\Akutansi\Masterjasa\AddMasterJasaJob;
use App\Jobs\Akutansi\Masterjasa\UpdateMasterJasaJob;
use App\Jobs\Treatment\UpdateJasaJob;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MastertreatmentController extends Controller
{

     public function getIndex(){
      $me= \Me::subgudang()->id_gudang;

       $items = ref_service_kode::kod()->paginate(10);

        $grup=ref_service_grup::all();
        return view('Pelayanan.Treatment.master.ref_kode_service',[
            'items'=>$items,
            'grup' =>$grup,
              'gudangs' =>ref_gudang::all()
            ]);
    }
    public function getListgrup(Request $req){
      $data=ref_service::grup()->paginate(10);
        $status = [

               1 => 'Aktif',
               2 => 'nonaktif'

           ];
      return view('Pelayanan.treatment.master.list_grup_layanan',[
              'data' =>$data,
              'status'  =>$status,
              ]);
    }

 public function getUpdate($id){
    $jasa = ref_service::cek($id);
      if($jasa->count()==0)
        return redirect('mastertreatment/listgrup');
         $service = ref_service::cek($id)->first();

        $jasa=ref_service::leftjoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
                            ->where('ref_service.parend_id',$id)
                            ->whereIn('ref_service.status',[1])
                            ->select('ref_service.*','ref_service_kode.nm_service')->get();

        $items = ref_service_item::leftjoin('ref_service', 'ref_service.id_service' ,'=', 'ref_service_item.id_service')
                                  ->leftjoin('data_barang', 'data_barang.id_barang', '=', 'ref_service_item.id_barang')
                                  ->leftjoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'ref_service_item.id_satuan')
                                  ->where('ref_service_item.id_service', $id)
                                  ->whereIn('ref_service_item.status',[1])
                                  ->select('ref_service_item.*', 'data_barang.nm_barang', 'ref_satuan.nm_satuan', 'data_barang.kode')->get();
        $title ='Edit data Paket Tindakan';

    return view('Pelayanan.Treatment.Master.update',[
          'service' =>$service,
          'jasa'  =>$jasa,
          'items' =>$items,
          'title' =>$title,
            ]);

  }
      public function postHapus(Request $req){
        if($req->ajax()){
            ref_service::find($req->id)->update([
                 'status' => 2
                 ]);
            return json_encode([
                'result' =>true
                ]);
        }
    }
      public function postSembunyi(Request $req){
        if($req->ajax()){
           ref_service_item::find($req->id)->update([
              'status' =>2
              ]);
          return json_encode([
            'result' => true
            ]);

        }
    }
    public function getLoadbhp(Request $req){
          if($req->ajax()){
              $res = [];
              $out = '';
              $items = data_item_gudang::master($req->all())->paginate(5);
              $total = $items->total();
              if($total > 0):
                  foreach($items as $item){
                      $out .= '
                          <tr class="bhp-' . $item->id_barang . '">
                              <td>' . $item->kode . '</td>

                              <td>' . $item->nm_barang . ' <small class="pull-right hide bhp-loading-loading-' . $item->id_barang . '">Memuat...</small></td>
                              <td class="text-right"><button class="btn btn-white btn-small btn-bhp-' . $item->id_barang . '" onclick="add_bhp(' . $item->id_barang .');"><i class="fa fa-plus"></i></button></td>
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
     public function getAddbhp(Request $req){
        if($req->ajax()){

            $res = [];
            $out='';
                $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
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
                                             )->first();
                $res['item'] = $item;
                 $res['content'] = $out;
                // dd($res);
                return json_encode($res);
                }
        }
        public function getLoadupdatejasa(Request $req){
         if($req->ajax()){
            $res=[];
            $out='';
             $items=ref_service_detail::cari($req->all())->paginate(5);
            $total=$items->total();
            if($total > 0):
                foreach ($items as $item ) {
                    $out .= '
                        <tr class="jasaupdate-' .$item->id_service_detail .'">

                            <td> '. $item->nm_service .' <small class="pull-right hide jasaupdate-loading-' .$item->id_service_detail.'">Memuat......</small></td>
                            <td clasd="text-right"><button onclick="add_jasaupdate(\''.$item->id_service_detail.'\');" class="btn btn-jasaupdate-' .$item->id_service_detail.' btn-white btn-small"><i class="fa fa-plus"></i></button>  </td>
                        </tr>
                    ';
                }
                else:
                    $out= '
                          <tr>
                            <td colspan="3"> Tidak Timukan</td>
                          </tr>
                        ';
                endif;
                // dd($items);
                $res['total'] =$total;
                $res['content'] = $out;
                $res['pagin'] = $items->render();

                return json_encode($res);
          }
      }
    public function getAddjasaupdate(Request $req){
        if($req->ajax()){
            $res = [];
            $out = [];
          $pa=ref_service_detail::leftJoin('ref_service_kode', 'ref_service_kode.service_kode' ,'=' ,'ref_service_detail.id_service_kode')
                                  ->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=' ,'ref_service_detail.id_unit')
                                  ->whereIn('ref_service_kode.type',[2])
                                  ->where('ref_service_detail.id_service_detail', $req->id)
                                  ->select('ref_service_detail.*','ref_service_kode.*')->first();
            $res['pa'] = $pa;
            return json_encode($res);
        }
    }
    public function postUpdate(Request $req){

     $arr = $this->dispatch(new UpdateJasaJob($req->all()));
      if($arr['res'])
          return redirect('/mastertreatment/listgrup')->withNotif([
                  'label' => $arr['label'],
                  'err' => $arr['err']
              ]);
      else
          return redirect('/mastertreatment/listgrup')->withNotif([
                  'label' => $arr['label'],
                  'err' => $arr['err']
              ]);
  }
    public function postDetailtindakan(Request $req){
      $me= \me::subgudang()->id_gudang;
      if($req->ajax()){
        $result =[];
        $out = '';

        $service = ref_service::leftjoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
                              ->where('ref_service.id_service',$req->id)
                              ->select('ref_service_kode.nm_service')->first();

        $jasa=ref_service::leftjoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'ref_service.service_kode')
                            ->where('ref_service.parend_id',$req->id)
                            ->whereIn('ref_service.status',[1])
                            ->select('ref_service.*','ref_service_kode.nm_service')->get();

        $items = ref_service_item::leftjoin('ref_service', 'ref_service.id_service' ,'=', 'ref_service_item.id_service')
                                  ->leftjoin('data_barang', 'data_barang.id_barang', '=', 'ref_service_item.id_barang')
                                  ->leftjoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'ref_service_item.id_satuan')
                                  ->whereIn('ref_service_item.status',[1])
                                  ->where('ref_service_item.id_service', $req->id)
                                  ->select('ref_service_item.qty', 'data_barang.nm_barang', 'ref_satuan.nm_satuan', 'data_barang.kode')->get();
         $out .= '<div class="grid simple">
                        <div class="grid-body no-border">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Barang</th>
                                    <th class="text-right">Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                ';
        foreach ($items as $item ) {
          $out .='
              <tr>
                <td>'.$item->kode.'</td>
                <td>'.$item->nm_barang.'</td>
                 <td class="text-right">' . number_format($item->qty,0,',','.') . ' ' . $item->nm_satuan . '</td>
              </tr>
              ';
        }
        // if($me==11):
          $out .='
                   <tr>
                      <th>Nama Jasa</th>
                      <th></th>
                  </tr>
                     ';
        // endif;
       foreach ($jasa as $key ) {
          $out .='
                  <tr>
                    <td>'.$key->nm_service.'</td>
                    <td></td>
                  </tr>
            ';
        }
          $out .= '
                            </tbody>
                        </table>
                    </div>
                </div>';

                // dd($service);
            $result['content']  = $out;
            $result['service']  = $service->nm_service;
            return json_encode($result);
      }
    }
    public function getGrup(){
        $grup=ref_service_grup::all();
        $paren=ref_service_kode::whereIn('ref_service_kode.type',[1])->get();

        return view('Pelayanan.treatment.master.grup_layanan',[
           'paren'  =>$paren,
            'grup'  => $grup,


            ]);
    }
  public function postGrup(Request $req){
    if(count($req->service_kode) == 0 )
        return redirect()->back()->withNotif([
            'label' => 'danger',
            'err' => '<center>OOps!, Anda Belum Tindakan atau jasa </center>'
        ]);
    $arr = $this->dispatch(new InsertMasterJob($req->all()));
    if($arr['res'])
        return redirect('/mastertreatment/listgrup')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    else
        return redirect()->back()->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    }

    public function getEditkode($id)
    {
        $data=ref_service_kode::edit($id)->first();
         $type = [
            1 => 'tindakan',
            2 => 'Jasa'
        ];
            $coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_coa = \Format::select_coa($coas);
          $res['select_coa'] = $select_coa;
    $coa_pendapatan = \Format::select_coa($coas, 0, $data->coa_pendapatan);
        return view('Pelayanan.Treatment.master.editkodeservie',[
            'data' => $data,
            'select_coa' => $select_coa,
            'coa_pendapatan' =>$coa_pendapatan,
            'type'  =>$type
            ]);
    }
    public function postEditkode(Request $req){
        $this->dispatch(new UpdatekodeserviceJob($req->all()));

        return redirect('/mastertreatment')->withNotif([
            'label' =>'success',
            'err'   => 'Master Layanan Berhasil diperbaharui'
            ]);
    }
    public function getMastercoa(){
        return view('Pelayanan.Treatment.master.mastercoa');
    }

     public function getLoadtindakan(Request $req ){
      $me = \Me::subgudang()->id_gudang;
        if($req->ajax()){
            $res=[];
            $out='';

            $items=ref_service_kode::cari($req->all())->whereIn('ref_service_kode.type',[1])
                                          ->where('ref_service_kode.id_unit',$me)->paginate(5);
            $total=$items->total();
            if($total > 0):
                foreach ($items as $item ) {
                    # code...
                    $out .= '
                        <tr class="tindakanaturan-' .$item->service_kode .'">

                            <td> '. $item->nm_service .' <small class="pull-right hide tindakanaturan-loading-' .$item->service_kode.'">Memuat......</small></td>
                            <td clasd="text-right"><button onclick="add_tindakanatur(\''.$item->service_kode.'\');" class="btn btn-tindakanaturan-' .$item->service_kode.' btn-white btn-small"><i class="fa fa-plus"></i></button>  </td>
                        </tr>
                    ';
                }
                else:
                    $out= '
                          <tr>
                          <td colspan="3"> Tidak ditemukan</td>
                          </tr>
                        ';

                endif;
                // dd($items);
                $res['total'] =$total;
                $res['content'] = $out;
                $res['pagin'] = $items->render();

                return json_encode($res);
        }
    }
      public function getAddtindakan(Request $req){
      $me= \Me::subgudang()->id_gudang;
      if($req->ajax()){
          $res = [];
          $out = [];
          $pa=ref_service_kode::where('service_kode', $req->id)->first();
          // if($me==11):
            $btn= '<button type="button" class="btn-resep btn-white btn" onclick="jasa(' . $pa->service_kode . ',1); ">Tambh Jasa<i class="fa fa-plus"></i></button>';
          // else:
          //   $btn='';
          // endif;
          $res['pa'] = $pa;
          $res['btn'] =$btn;
          return json_encode($res);
      }
  }
    public function getLoadjasa(Request $req){
         if($req->ajax()){
            $res=[];
            $out='';
             $items=ref_service_detail::cari($req->all())->paginate(5);
            $total=$items->total();
            if($total > 0):
                foreach ($items as $item ) {
                    $out .= '
                        <tr class="jasa-' .$item->id_service_detail .'">

                            <td> '. $item->nm_service .' <small class="pull-right hide jasa-loading-' .$item->id_service_detail.'">Memuat......</small></td>
                            <td clasd="text-right"><button onclick="add_jasa(\''.$item->id_service_detail.'\');" class="btn btn-jasa-' .$item->id_service_detail.' btn-white btn-small"><i class="fa fa-plus"></i></button>  </td>
                        </tr>
                    ';
                }
                else:
                    $out= '
                          <tr>
                          <td colspan="3"> Tidak Timukan</td>
                          </tr>
                        ';

                endif;
                // dd($items);
                $res['total'] =$total;
                $res['content'] = $out;
                $res['pagin'] = $items->render();

                return json_encode($res);
        }
    }
       public function getAddjasa(Request $req){
        if($req->ajax()){
            $res = [];
            $out = [];
          $pa=ref_service_detail::leftJoin('ref_service_kode', 'ref_service_kode.service_kode' ,'=' ,'ref_service_detail.id_service_kode')
                                  ->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=' ,'ref_service_detail.id_unit')
                                  ->whereIn('ref_service_kode.type',[2])
                                  ->where('ref_service_detail.id_service_detail', $req->id)
                                  ->select('ref_service_detail.*','ref_service_kode.*')->first();
            $res['pa'] = $pa;
            return json_encode($res);
        }
    }
        public function getLoaditemsaturan(Request $req){
            if($req->ajax()){
                $res = [];
                $out = '';
                // $items = data_item_gudang::paket($req->all())->paginate(5);
                $items = data_barang::masterjasa($req->all())->paginate(5);
                $total = $items->total();
                if($total > 0):
                    foreach($items as $item){
                        $out .= '
                            <tr class="barangaturan-' . $item->id_barang . '">
                                <td>' . $item->kode . '</td>

                                <td>' . $item->nm_barang . ' <small class="pull-right hide itematuran-loading-loading-' . $item->id_barang . '">Memuat...</small></td>
                                <td class="text-right"><button class="btn btn-white btn-small btn-itematuran-' . $item->id_barang . '" onclick="add_itematuran(' . $item->id_barang .');"><i class="fa fa-plus"></i></button></td>
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
                // dd($res['pagin'] = $items->render());
                return json_encode($res);
            }
    }
    public function getAdditematuran(Request $req){
     $me = \Me::subgudang();

    if($req->ajax()){

        $res = [];
        $out='';
            $item= data_barang::join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                ->where('data_barang.id_barang', $req->id)
                    ->select(
                      'data_barang.*',
                      'ref_satuan.nm_satuan')->first();
            $res['item'] = $item;
             $res['content'] = $out;
            // dd($res);
            return json_encode($res);
            }
    }

    public function getPaket()
    {
       $coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_coa = \Format::select_coa($coas);
          $res['select_coa'] = $select_coa;
        return view('Pelayanan.Treatment.Master.add_paket',[
            'select_coa' => $select_coa
            ]);
    }
    public function getCoa(Request $req)
    {
       if($req->ajax()){

            $res = [];
             $coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_coa = \Format::select_coa($coas);
          $res['select_coa'] = $select_coa;

          $res['select_coa'] = $out;
            // dd($res);
            return json_encode($res);

        }
    }
    public function postPaket(Request $req)
    {

    $arr = $this->dispatch(new InsertPaketJob($req->all()));
    if($arr['res'])
        return redirect('/mastertreatment')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    else
        return redirect()->back()->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    }
   public function getAdditem(Request $req){
     $me = \Me::subgudang();

    if($req->ajax()){

        $res = [];
        $out='';
        $item=data_barang::join('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
                          ->join('ref_satuan' ,'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                          ->where('data_barang.id_barang', $req->id)
                    ->select(
                     'data_barang.*',
                    'ref_satuan.nm_satuan')->first();
        $res['item'] = $item;
         $res['content'] = $out;
        // dd($res);
        return json_encode($res);

    }
}
 public function postDestroy(Request $req){

      ref_service_kode::find($req->id)->delete();

      return json_encode([
        'result' => true
        ]);
    }

  public function getAddblankform(Request $req)
  {
  $hasil['isi'] ='
        <tr class="baris_form">
                <td><input type="text" value="" name="nm_service[]" class="form-control" required placeholder="Masukan Nama Paket"></td>
                  <input type="hidden" name="coa[]" value="0">
                  <input type="hidden" name="coa_dr[]" value="0">
                  <input type="hidden" name="coa_rs[]" class="form-control" value="0">
                  <input type="hidden"  min="0" value="0" name="persen_rs[]">
                  <input type="hidden" min="0" value="0" name="persen_dr[]" >
                  <input type="hidden" name="type[]" value="1">
                 <td><button type="button" class="btn btn-danger btn-hapus"><i class="fa fa-trash"></i></button></td>
              </tr>
                ';

    return json_encode($hasil);
  }

  //<!-- ------------ini untuk ke uangan ---------------------!>
  public function getMasterjasa(){
    $data=ref_service_kode::png()->paginate(10);
      $status = [

               1 => 'Aktif',
               2 => 'nonaktif'

           ];
    return view('Akutansi.MasterTindakan.index',[
                'data'   =>$data,
                'status' =>$status,
          ]);
  }
  public function getAlljasa(Request $req){
        if($req->Ajax()){
            $result = [];
            $items = ref_service_kode::jasa($req->nm_service, $req->all())->paginate($req->limit);
            // dd($req->all);
            $out = '';
            $total = $items->total();

        if($total > 0):
              $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
             foreach ($items as $item) {
                $out .='
                   <tr class="jasa_'.$item->service_kode.'">
                      <td >'.$no.'</td>
                      <td >
                        '.$item->nm_service.'
                          <div class="link">
                          <small>

                            <a href="'.url('mastertreatment/editjasa/'.$item->service_kode).'">Edit</a>||
                            <a href="#" onclick="detail_jasa('. $item->service_kode .');" data-toggle="modal" data-target="#detail">Lihat</a>
                            <!-- <a href="javascript:void(0);" onclick="hapus('. $item->service_kode .');" class="text-danger">Hapus</a> -->
                          </small>
                      </div>
                      </td>
                      <td>'.$item->kode.'</td>
                      <td>'. $item->rs_coa.'</td>
                      <td>'. $item->dr_coa.'</td>
                      <td>'. $item->persen_dr.' &nbsp;(%)</td>
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
        // dd($items);
             $result['items'] = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/mastertreatment/masterjasa');
        }
    }
  public function postDetailjasa(Request $req){
    if($req->ajax()){
      $result = [];
      $out = '';

      $jasa=ref_service_kode::find($req->id);
      $detail=ref_service_detail::leftJoin('ref_service_kode', 'ref_service_kode.service_kode' ,'=' ,'ref_service_detail.id_service_kode')
                                ->leftJoin('ref_gudang', 'ref_gudang.id_gudang', '=' ,'ref_service_detail.id_unit')
                                ->whereIn('ref_service_kode.type',[2])
                                 ->where('ref_service_detail.id_service_kode',$req->id)
                               ->select('ref_service_detail.*','ref_service_kode.*', 'ref_gudang.nm_gudang')->get();
                $kebutuhan =[
                            0 =>'Tidak',
                            1 =>'Iya'
                            ];
        $out .= '<div class="grid simple">
                        <div class="grid-body no-border">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Kebutuhan Dokter</th>
                                    <th class="text-right"></th>
                                </tr>
                                </thead>
                                <tbody>
                ';
        foreach ($detail as $item) {
            $out .='
              <tr>
                <td>'.$item->nm_gudang.'</td>
                <td>'.$kebutuhan[$item->kebutuhan].'</td>
              </tr>
              ';
        }
         $out .= '
                            </tbody>
                        </table>
                    </div>
                </div>';
          $result['content'] = $out;
          $result['service'] =$jasa->nm_service;
          return json_encode($result);
    }
  }
  public function getAddmaster(){
    $unit= ref_gudang::all();
      $coas = [];
    foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
      $coas[$coa->parent_id][] = $coa;
    }
    $select_coa = \Format::select_coa($coas);
   $tipe=2;
    return view('Akutansi.MasterTindakan.add_jasa',[
              'select_coa' =>$select_coa,
              'unit'  =>$unit,
              'tipe'  =>$tipe,

            ]);

  }

  public function getEditjasa($id){
    $jasa = ref_service_kode::hid($id);
      if($jasa->count()==0)
        return redirect('mastertreatment/masterjasa');
       $jasa = ref_service_kode::hid($id)->first();
       $dtil=ref_service_detail::edit($id)->get();
       ///--coa--///
    $coas = [];
    foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
      $coas[$coa->parent_id][] = $coa;
    }
    // Akun Coa Penjualan <--- aku pengen ngambil id ini dari database yang mana ya?
    $coa_penjualan = \Format::select_coa($coas, 0, $jasa->coa);
    // Akun Coa Biaya  <--- aku pengen ngambil id ini dari database yang mana ya?
    $coa_biaya = \Format::select_coa($coas, 0, $jasa->coa_dr);
    // Akun Coa Disk Penjualan <--- aku pengen ngambil id ini dari database yang mana ya? ref_sservice_kode dan ref_coa
    $coa_disk = \Format::select_coa($coas, 0, $jasa->coa_rs);
    $coa_pendapatan = \Format::select_coa($coas, 0, $jasa->coa_pendapatan);


    $tipe=1;
    $unit= ref_gudang::all();
    return view('Akutansi.MasterTindakan.edit_jasa',[
              // 'select_coa' =>$select_coa,
          'coa_penjualan' => $coa_penjualan,
          'coa_biaya' =>$coa_biaya,
          'coa_disk'  =>$coa_disk,
          'coa_pendapatan' =>$coa_pendapatan,
              'jasa'  =>$jasa,
              'dtil'  =>$dtil,
              'tipe'  =>$tipe,
              'id'  =>$id,
              'unit'  =>$unit,
            ]);

  }
  public function postEditjasa(Request $req){

   $arr = $this->dispatch(new UpdateMasterJasaJob($req->all()));
    if($arr['res'])
        return redirect('/mastertreatment/masterjasa')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    else
        return redirect('/mastertreatment/masterjasa')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
  }

  public function postMasterjasa(Request $req){
     $arr = $this->dispatch(new AddMasterJasaJob($req->all()));
    if($arr['res'])
        return redirect('/mastertreatment/masterjasa')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    else
        return redirect('/mastertreatment/masterjasa')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
  }

  public function getAddblankjasa(Request $req){
     if($req->ajax()){
      $res= [];
      $out ='';
    $unit= ref_gudang::all();
     $out .= '
            <tr class="baris_jasa">
                <td>
                    <select name="unit[]" id="unit" required="required" class="form-control">
                  <option value=""> Pilih Unit </option>';
                  foreach($unit as $term):
                     $out .= '<option value="'. $term->id_gudang .'">'. $term->nm_gudang .'</option>';
                endforeach;
     $out .= '
            </select>
                <td>
                  <select name="kebutuhan[]" required class="form-control">
                          <option value="">Pilih </option>
                          <option value="0">Tidak</option>
                          <option value="1">Butuh</option>
              </select>
                </td>
                 <td><button type="button" class="btn btn-danger btn-hapus-jasa"><i class="fa fa-trash"></i></button></td>
              </tr>
    ';
     $res['content'] = $out;
      return json_encode($res);
      }

  }


    public function postNonaktif(Request $req){
        if($req->ajax()){
            ref_service::find($req->id)->update([
                 'status' => 2
                 ]);
            return json_encode([
                'result' =>true
                ]);
        }
    }
 public function postDelete(Request $req){
    if($req->ajax()){
      data_faktur::find($req->id)->update([
        'status' => 3
      ]);

      return json_encode([
        'id' => $req->id
      ]);
    }
  }
    // public function postDetailpaket(Request $req){
    //     if($req->ajax()){
    //         $result = [];
    //         $out = '';

    //         $pak = data_paket::find($req->id);

    //         $master = data_paket_item::leftJoin('ref_service', 'ref_service.id_service', '=', 'data_paket_item.id_service')
    //             ->where('data_paket_item.id_paket', $req->id)
    //             ->select(
    //                 'data_paket_item.*',
    //                 'ref_service.nama_service',
    //                 'ref_service.kode_service',
    //                 'ref_service.tipe'
    //                 )
    //                 ->get();
    //          $btn = '
    //          <a href="'. url('/mastertreatment/additem/'.$pak->id_paket) .'" class="btn btn-primary ">Tambah Item</a>
    //           ';
    //         $out .= '<div class="grid simple">
    //                 </thead>
    //                 <tbody>
    //             ';
    //             $out .= '
    //           <tr>
    //             <div class="grid-body no-border">
    //                 <table class="table table-striped">
    //                 <thead>
    //                 <tr>
    //                     <th class="text-center">Kode</th>
    //                     <th class="text-center">Nama</th>
    //                 </tr>
    //                 </thead>
    //                     ';
    //         foreach($master as $item){

    //             $out .='
    //                 <tr>
    //                     <td class="text-center" >dvsvsdez'.$item->kode_service .'</td>
    //                     <td class="text-center">'.$item->nama_service .'</td>
    //                   </tr>
    //             ';
    //         }
    //          $out .= '
    //              <thead>
    //             <tr><td colspan="3" class="text-center"><h4><b>Jenis BHP</b></h4></td></tr>
    //             <tr>
    //                 <th class="text-center">Kode</th>
    //                 <th class="text-center">Nama</th>
    //                  <th class="text-center">Qty</th>
    //             </tr>
    //             </thead>
    //             <tbody>
    //             ';
    //         $obt = $item->paketitem()
    //                 ->join('data_barang', 'data_barang.id_barang', '=','data_paket_resep.id_barang')
    //                 ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_paket_resep.id_satuan')
    //                 ->select(
    //                 'data_paket_resep.*',
    //                 'data_barang.nm_barang',
    //                 'ref_satuan.nm_satuan'
    //                 )
    //                 ->get();
    //             foreach ($obt as $obat) {
    //                 $out .='
    //                  <tr>
    //                     <td class="text-center" >'.$obat->nm_barang .'</td>
    //                     <td class="text-center">'.$obat->qty .'</td>
    //                   </tr>
    //                   ';
    //                 }
    //                   $out .= '

    //                             </tbody>
    //                         </tbody>
    //                     </table>
    //                 </div>
    //             </div>';



    //         $result['nm_paket']     = $pak->nm_paket;
    //         $result['content']  = $out;
    //         $result['additem'] = $btn;

    //         return json_encode($result);

    //     }
    // }
    // public function getDetailmaster($id){
    //   $data=data_paket::hid($id);
    //   if($data->count() == 0)
    //       return redirect('/mastertreatment');
    //     $data=data_paket::hid($id)->first();
    //     $master = data_paket_item::byid($id)->get();
    //     return view('Pelayanan.Treatment.Master.detailpaket',[
    //         'data' =>$data,
    //         'master'=> $master,
    //         ]);
    // }
    // public function getAdditem($id){
    //     $data=data_paket::hid($id);
    //       if($data->count() == 0)
    //           return redirect('/mastertreatment');
    //         $data=data_paket::hid($id)->first();
    //          return view('Pelayanan.Treatment.Master.additem',[
    //             'data' =>$data,
    //             ]);

    // }
    // public function getEdit( $id){
    //      $data=data_paket::hid($id);
    //   if($data->count() == 0)
    //       return redirect('/mastertreatment');
    //     $data=data_paket::hid($id)->first();
    //     $master = data_paket_item::byid($id)->get();
    //     return view('Pelayanan.Treatment.Master.edit',[
    //         'data' =>$data,
    //         'master'=> $master,
    //         ]);

    // }
    //   public function postEdit(Request $req){
    //             $arr = $this->dispatch(new UpdateMasterJob($req->all()));
    //             if($arr['res'])
    //                 return redirect('/mastertreatment')->withNotif([
    //                         'label' => $arr['label'],
    //                         'err' => $arr['err']
    //                     ]);
    //             else
    //                 return redirect()->back()->withNotif([
    //                         'label' => $arr['label'],
    //                         'err' => $arr['err']
    //                     ]);
    //          }
    //  public function postAdditem(Request $req){
    //          // if(count($req->id_service) == 0 )
    //          //        return redirect()->back()->withNotif([
    //          //            'label' => 'danger',
    //          //            'err' => '<center>OOps!, Anda Belum Tindakan atau jasa </center>'
    //          //        ]);
    //     $arr    = $this->dispatch(new AdditemJob($req->all()));
    //         if($arr['res'])
    //         return redirect('/mastertreatment')->withNotif([
    //         'label' => $arr['label'],
    //         'err'   => $arr['err']
    //         ]);
    //         else
    //         return redirect()->back()->withNotif([
    //         'label' => $arr['label'],
    //         'err'   => $arr['err']
    //         ]);
    //     }
    // public function getMaster()
    //     {

    //        return view('Pelayanan.Treatment.Master.Create');
    //     }

    public function postAktif(Request $req){
        if($req->ajax()){
            ref_service::find($req->id)->update([
                 'status' => 1
                ]);
            return json_encode([
                'result' =>true
                ]);
        }
    }
    //   public function getLoaditemsaturan(Request $req){
    //         if($req->ajax()){
    //             $res = [];
    //             $out = '';
    //             $items = data_item_gudang::treatment($req->all())->paginate(5);
    //             //dd($items);
    //             $total = $items->total();
    //             if($total > 0):
    //                 foreach($items as $item){
    //                     $akhir =$item->in - $item->out ;
    //                     $out .= '
    //                         <tr class="barangaturan-' . $item->id_barang . '">
    //                             <td>' . $item->kode . '</td>
    //                             <td>' . $item->nm_barang . ' <small class="pull-right hide itematuran-loading-' . $item->id_barang . '">Memuat...</small></td>
    //                             <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itematuran-loading-loading-' . $item->id_barang . '">Memuat...</small></td>
    //                             <td class="text-right"><button class="btn btn-white btn-small btn-itematuran-' . $item->id_barang . '" onclick="add_itematuran(' . $item->id_barang . ');"><i class="fa fa-plus"></i></button></td>
    //                         </tr>
    //                     ';
    //                 }
    //             else:
    //                 $out = '
    //                     <tr>
    //                         <td colspan="3">Tidak ditemukan</td>
    //                     </tr>
    //                 ';
    //             endif;

    //             $res['total'] = $total;
    //             $res['content'] = $out;
    //             $res['pagin'] = $items->render();

    //             return json_encode($res);
    //         }
    // }
      public function getAlldata(Request $req){
        if($req->ajax()){
            $result = [];
          $items= ref_service_kode::data($req->nm_service, $req->id_unit, $req->status)->paginate($req->limit);

          $out = '';
          $total = $items->total();
            $status =[
              1=>'Non Aktif',
              2 =>'Aktif',
            ];

            if($total > 0):
              $no= $items->currentPage() == 1 ? 1 :($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
            foreach ($items as $item) {

              $out .='
              <trclass="data1_'. $item->service_kode .'">
                <td>'.$no.'</td>
                <td >
                '.$item->nm_service.'
                <div class="link text-muted">
                  <small>
                  <a href="'.url('mastertreatment/editkode/'.$item->service_kode).'">Edit</a>
                  <a href="javascript:;" onclick="hapus('. $item->service_kode .');" class="text-danger">Hapus</a>
                  </small>
              </div>
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

            $result['data'] = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/mastertreatment');
        }
    }
     public function getAllpaket(Request $req){
        if($req->ajax()){
            $result = [];
            $items = ref_service::service1($req->nm_service)->paginate($req->limit);
            $out = '';
            $total = $items->total();
              $status=[
                2=>'Aktif',
                1=>'Non Aktif',
                ];

            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                      $aktif=$item->status < 2 ?'
                        <a href="javascript:;" onclick="nonaktif('. $item->id_service .');" class="text-danger"><button class="btn btn-danger btn-xs btn-mini">Non Aktif</button></a>': '';
                        $nonaktif=$item->status > 1 ?'
                         <a href="javascript:;" onclick="aktif('.$item->id_service.');" class="text-danger"><button class="btn btn-primary btn-xs btn-mini">Aktif</button></a>':'';
                    $out .= '
                        <tr class="tin_' . $item->id_service . '">
                            <td>' . $no . '</td>
                            <td>
                                <div> ' .  $item->nm_service. '</div>
                                <small>
                                  <a href="'.url('mastertreatment/edittinda'.$item->id_service).'">Edit</a>||
                                  <a href="#" onclick="detail_tinda('. $item->id_service .');" data-toggle="modal" data-target="#detail">Lihat</a>
                                </small>

                            </td>
                            <td><div>'. \Format::indoDate2($item->created_at) .'</div>
                                  <div class="text-muted"><small>'. \Format::hari($item->created_at) .' '. \Format::jam($item->created_at) .'</small></div>
                                </td>
                            <td>' .$status[$item->status] . '
                            <div class="text-muted"><small>

                           '. $aktif . '
                           '. $nonaktif . '

                          </small></div>
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

            $result['data'] = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/mastertreatment');
        }
    }


    //       public function getLoadjasa(Request $req){
    //             if($req->ajax()){
    //                 $res = [];
    //                 $out = '';
    //                 $items = ref_service::search($req->all())->whereIn('ref_service.tipe',[1,3])->paginate(5);
    //                 $total = $items->total();
    //                 if($total > 0):
    //                     foreach($items as $item){
    //                         $out .= '
    //                             <tr class="jasa-' . $item->id_service . '">
    //                                 <td>' . $item->kode_service . '</td>
    //                                 <td>' . $item->nama_service . ' <small class="pull-right hide jasa-loading-' . $item->id_service . '">Memuat..........</small></td>
    //                                 <td class="text-right"><button onclick="add_jasa(\'' . $item->id_service . '\');" class="btn btn-jasa-' . $item->id_service . ' btn-white btn-small"><i class="fa fa-plus"></i></button></td>
    //                             </tr>
    //                         ';
    //                     }
    //                 else:
    //                     $out = '
    //                         <tr>
    //                             <td colspan="4">Tidak ditemukan</td>
    //                         </tr>
    //                     ';
    //                 endif;
    //                 $res['total'] = $total;
    //                 $res['content'] = $out;
    //                 $res['pagin'] = $items->render();

    //                 return json_encode($res);
    //             }
    //             }
    //         public function getAddjasa(Request $req){
    //             if($req->ajax()){
    //                 $res = [];
    //                 $out = [];
    //                 $pa=ref_service::where('id_service', $req->id)->first();
    //                     $res['pa'] = $pa;
    //                 return json_encode($res);
    //             }
    //         }
    //         public function getLoaditems(Request $req){
    //             if($req->ajax()){
    //                 $res = [];
    //                 $out = '';
    //                 $items = data_item_gudang::treatment($req->all())->paginate(5);
    //                 //dd($items);
    //                 $total = $items->total();
    //                 if($total > 0):
    //                     foreach($items as $item){
    //                         $akhir =$item->in - $item->out ;
    //                         $out .= '
    //                             <tr class="barang-' . $item->id_barang . '">
    //                                 <td>' . $item->kode . '</td>
    //                                 <td>' . $item->nm_barang . ' <small class="pull-right hide items-loading-' . $item->id_barang . '">Memuat...</small></td>
    //                                 <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide items-loading-loading-' . $item->id_barang . '">Memuat...</small></td>
    //                                 <td class="text-right"><button class="btn btn-white btn-small btn-items-' . $item->id_barang . '" onclick="add_items(' . $item->id_barang . ');"><i class="fa fa-plus"></i></button></td>
    //                             </tr>
    //                         ';
    //                     }
    //                 else:
    //                     $out = '
    //                         <tr>
    //                             <td colspan="3">Tidak ditemukan</td>
    //                         </tr>
    //                     ';
    //                 endif;

    //                 $res['total'] = $total;
    //                 $res['content'] = $out;
    //                 $res['pagin'] = $items->render();

    //                 return json_encode($res);
    //             }
    //      }
    //        public function getAdditems(Request $req){
    //              $me = \Me::subgudang();

    //             if($req->ajax()){

    //                 $res = [];
    //                 $out='';
    //                     $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
    //                         ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
    //                         ->where('data_item_gudang.id_gudang', $me->id_gudang)
    //                         // ->where('data_item_gudang.id_gudang', 26)
    //                         ->where('data_item_gudang.id_barang', $req->id)
    //                         ->select(
    //                          'data_item_gudang.*',
    //                          'data_barang.id_satuan',
    //                          'data_barang.kode',
    //                           'data_barang.nm_barang',
    //                           'data_barang.harga_jual',
    //                           'data_item_gudang.in AS masuk',
    //                          'data_item_gudang.out AS keluar',
    //                         'ref_satuan.nm_satuan' )
    //                         ->first();
    //                 $res['item'] = $item;
    //                  $res['content'] = $out;
    //                 // dd($res);
    //                 return json_encode($res);
    //             }
    //         }
    //


}

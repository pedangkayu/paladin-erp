<?php

namespace App\Http\Controllers\treatment;

use Illuminate\Http\Request;

use App\Jobs\resep\InsertPasienMysqlJob;
use App\Jobs\Treatment\InsertJasaTreatmentJob;

use App\Models\data_treatment;
use App\Models\data_karyawan;
use App\Models\data_paket;
use App\Models\data_paket_item;
use App\Models\ref_service;
use App\Models\data_item_gudang;
use App\Models\data_treatment_resep;
use App\Models\data_treatment_item;
use App\Models\data_resep_item;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mssql;

class JasatreatmentController extends Controller
{
  
   public function getIndex(){
        $item=data_treatment::Listtretment()->paginate(10);
        return view('Pelayanan.treatment.indexjasa', [
         'items' => $item,
        ]);
      }
    public function getCreate(){
        $m= data_karyawan::whereIn('data_karyawan.id_profesi',[1,2,3,4,8,9,10,11])->get();
          return view('Pelayanan.Treatment.createjasa', [
              'dokter'=>$m,
          ]);
        }
    public function postCreate(Request $req){
    if(count($req->id_pasien) == 0 )
        return redirect()->back()->withNotif([
            'label' => 'danger',
            'err' => '<center>OOps!, Anda Belum Memilih Pasien </center>'
        ]);
    $arr = $this->dispatch(new InsertJasaTreatmentJob($req->all()));
    if($arr['res'])
        return redirect('/jasa')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    else
        return redirect()->back()->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    }
    public function getLoadpasien(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            // $items =  \MSSQL::tbl("PASIEN")->paginate(5);
            $items = \MSSQL::tbl("PASIEN")->where('NAMA_PASIEN', 'LIKE', '%'.$req['NAMA_PASIEN'].'%')->paginate(4);
        if(!empty($req['ID_PASIEN']))
            $items= \MSSQL::tbl("PASIEN")->where('ID_PASIEN','LIKE', '%' . $req['ID_PASIEN'] . '%')->paginate(4);
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $out .= '
                        <tr class="pa-' . $item->ID_PASIEN . '">
                            <td>' . $item->ID_PASIEN . '</td>
                            <td>' . $item->NAMA_PASIEN . '</td>
                            <td class="text-right"><button onclick="add_pasien(\'' . $item->ID_PASIEN . '\');" class="btn btn-pasien-' . $item->ID_PASIEN . ' btn-white btn-small"><i class="fa fa-plus"></i></button></td>
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
    public function getLoadpaket(Request $req){
            if($req->ajax()){
                $res = [];
                $out = '';
                $items = data_paket::paginate(5);
                //dd($items);
                $total = $items->total();
                if($total > 0):
                    foreach($items as $item){
                        $out .= '
                            <tr class="paket-' . $item->id_paket . '">
                                <td>' . $item->nm_paket . ' <small class="pull-right hide paket-loading-' . $item->id_paket . '">Memuat...</small></td>
                                <td> '.$item->harga_paket.'<small class="pull-right hide paket-loading-loading-' . $item->id_paket . '">Memuat...</small></td>
                                <td class="text-right"><button class="btn btn-white btn-small btn-paket-' . $item->id_paket . '" onclick="add_paket(' . $item->id_paket . ');"><i class="fa fa-plus"></i></button></td>
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

            if($req->ajax()){
                $res =[];
                $out ='';
                $paket = data_paket::find($req->id);
                $items = data_paket_item::leftjoin('data_paket', 'data_paket.id_paket', '=', 'data_paket_item.id_paket')
                        ->leftjoin('ref_service', 'ref_service.id_service', '=', 'data_paket_item.id_service')
                        ->leftjoin('data_barang', 'data_barang.id_barang', '=', 'data_paket_item.id_barang')
                        ->leftjoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_paket_item.id_satuan')
                        ->where('data_paket_item.id_paket', $req->id)
                        ->select(
                            'data_paket_item.*',
                            'data_barang.nm_barang',
                            'ref_service.nama_service',
                            'ref_service.id_service',
                            'ref_satuan.nm_satuan',
                            'ref_satuan.id_satuan',
                            'data_paket.nm_paket'
                            )
                            ->get();
                    $out .= '<table class="table table-striped item-paket" onclick="id_hapuspaket(' . $paket->id_paket . ');" data-paket="' . $paket->id_paket . '">
                        <tr style="border-botom:solid 2px #333;">

                            <td><h4>' . $paket->nm_paket . '</h4></td>
                            <td><button type="button" class="btn-resep btn-white btn" onclick="reseptretment(' .$paket->id_paket . ',1);"><i class="fa fa-plus"></i></button><td>      
                            <td>
                                <input type="hidden" name="harga_paket[]" value="' . $paket->harga_paket . '">
                                <input type="hidden" name="id_paket_tre[]" value="'. $paket->id_paket .'" />
                            </td>
                            <td>
                            </td><td>
                            </td>  
                        </tr>
                           
                    ';
                    $c = count($items);
                    $a = 1;
                    foreach($items as $item):

                        $class = $c == $a ? 'item-obat-'. $paket->id_paket : '';
                        if($item->id_barang < 1):
                        $out .= '
                            <tr class="' . $class . '">
                                <td>
                                <input type="checkbox" name="status_ser[' . $paket->id_paket . '][]" value="1" checked="checked" /> 
                              
                                </td>
                                <td>' . $item->nama_service . '
                                <input type="hidden" name="id_service_tre[' . $paket->id_paket . '][]" value="' . $item->id_service . '"/></td>
                                </td>
                                <td></td>
                                </tr>
                        ';
                        endif;
                        if($item->id_barang > 0):
                        $out .= '
                            <tr class="'. $class . '">
                            <td>
                                 <input type="checkbox" name="status_tre[' . $paket->id_paket . '][]" value="1" checked="checked" />
                            </td>
                            <td>' . $item->nm_barang . '
                                <input type="hidden" name="id_item_gudang_tre['.$paket->id_paket.'][]" value="'.$item->id_item_gudang.'"/>
                                <input type="hidden" name="id_gudang_tre['.$paket->id_paket.'][]" value="'.$item->id_gudang.'" />
                                <input type="hidden" name="id_barang_tre['.$paket->id_paket.'][]" value="'.$item->id_barang.'"/>
                              </td>
                            <td><input type="number" name="qty_tre['.$paket->id_paket.'][]" value="'.$item->qty .'" />'.$item->nm_satuan.'
                                <input type="hidden" name="id_satuan_tre['.$paket->id_paket.'][]" value="'.$item->id_satuan.'"</td>      
                            </tr>        
                       
                        ';
                        endif;
                        $a++;
                    endforeach;
                    $out .= '</table>';
                    $res['content'] = $out;
                    $res['id_paket'] = $paket->id_paket;
                    return json_encode($res);
                }
         }

    public function getLoaditemsaturan(Request $req  ){
            if($req->ajax()){
                $res = [];
                $out = '';
                $items = data_item_gudang::treatment($req->all())->paginate(5);
                
                $total = $items->total();
                if($total > 0):
                    foreach($items as $item){
                        $akhir =$item->in - $item->out ;
                        $out .= '<tr class="barangaturan-' . $item->id_barang . '">
                                <td>' . $req->paket . '</td>
                                <td>' . $item->nm_barang . ' <small class="pull-right hide itematuran-loading-' . $item->id_barang . '">Memuat...</small></td>
                                <td>' . $akhir.  ' '.$item->nm_satuan.'<small class="pull-right hide itematuran-loading-loading-' . $item->id_barang . '">Memuat...</small></td>
                                <td class="text-right"><button class="btn btn-white btn-small btn-itematuran-' . $item->id_barang . '" onclick="add_itematuran(\'' . $item->id_barang . '\', \'' . $req->paket . '\');"><i class="fa fa-plus"></i></button></td>
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
                $res['content'] = $out;
                $res['pagin'] = $items->render();
                //  //dd($res);
                // header('Content-Type: application/json');
                return json_encode($res);
                }
        }
    public function getAdditematuran(Request $req){
             $me = \Me::subgudang();

            if($req->ajax()){

                $res = [];
                $out='';
                    $item= data_item_gudang::join('data_barang', 'data_barang.id_barang', '=', 'data_item_gudang.id_barang')
                        ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                        // ->where('data_item_gudang.id_gudang', $me->id_gudang)
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
                           $out .='
                        <tr>
                            <td>
                                 <input type="checkbox" name="status_tre[' . $req->id_paket . '][]" value="1" checked="checked" />
                            </td>
                            <td>' . $item->nm_barang . '
                                <input type="hidden" name="id_item_gudang_tre['.$req->id_paket.'][]" value="'.$item->id_item_gudang.'"/>
                                <input type="hidden" name="id_gudang_tre['.$req->id_paket.'][]" value="'.$item->id_gudang.'" />
                                <input type="hidden" name="id_barang_tre['.$req->id_paket.'][]" value="'.$item->id_barang.'"/>
                               
                              </td>
                            <td><input type="number" name="qty_tre['.$req->id_paket.'][]" value="'.$item->qty .'" />'.$item->nm_satuan.'
                                <input type="hidden" name="id_satuan_tre['.$req->id_paket.'][]" value="'.$item->id_satuan.'"</td>  
                            </tr>
                ';
                 $res['content'] = $out;
                // dd($res);
                return json_encode($res);
            }
            }

         public function getLoadtindakan(Request $req){
          
        if($req->ajax()){
            $res = [];
            $out = '';
            $items=ref_service::service($req->all())->paginate(5);          
            $tipe =[
                  1=> 'Administrasi',
                  2 => 'Tindakan',
                  3 => 'Jasa'
              ];
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $out .= '
                        <tr class="tindakan-' . $item->id_service . '">
                            <td>' . $item->kode_service . '</td>
                            <td>' . $item->nama_service . ' <small class="pull-right hide tindakan-loading-' . $item->id_service . '"> Memuat....</small></td>
                            <td class="text-right"><button onclick="add_tindakan(\'' . $item->id_service . '\');" class="btn btn-tindakan-' . $item->id_service . ' btn-white btn-small"><i class="fa fa-plus"></i></button></td>
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
    public function getAddtindakan(Request $req){
        if($req->ajax()){
            $res = [];
            $out = [];
            $tipe =[
                  1=> 'Administrasi',
                  2 => 'Tindakan',
                  3 => 'Jasa'
              ];
            $pa=ref_service::where('id_service', $req->id)->first();
                $res['pa'] = $pa;
            return json_encode($res);
        }
    }
    public function getLoadjasa(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $items = ref_service::search($req->all())->paginate(5);
         // $items=ref_service::whereIn('status_service',[1,3])->paginate(5);
          $tipe =[
                1=> 'Administrasi',
                2 => 'Tindakan',
                3 => 'Jasa'
            ];
            $total = $items->total();
            if($total > 0):
                foreach($items as $item){
                    $out .= '
                        <tr class="jasa-' . $item->id_service . '">
                            <td>' . $item->kode_service . '</td>
                            <td>' . $item->nama_service . ' <small class="pull-right hide jasa-loading-' . $item->id_service . '">Memuat..........</small></td>
                            <td class="text-right"><button onclick="add_jasa(\'' . $item->id_service . '\');" class="btn btn-jasa-' . $item->id_service . ' btn-white btn-small"><i class="fa fa-plus"></i></button></td>
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
        public function getAddjasa(Request $req){
            if($req->ajax()){
                $res = [];
                $out = [];
                $pa=ref_service::where('id_service', $req->id)->first();
                    $res['pa'] = $pa;
                return json_encode($res);
            }
        }
        public function getLoaditems(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $items = data_item_gudang::treatment($req->all())->paginate(5);
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
    public function getAdditem(Request $req){
     $me = \Me::subgudang();    if($req->ajax()){
        $res = [];
        $out='';
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
        $res['item'] = $item;
         $res['content'] = $out;
        // dd($res);
        return json_encode($res);

            }
        }
    public function getDetail($id){
      $data=data_treatment::hid($id);
      if($data->count() == 0)
          return redirect('/jasa');
        $data=data_treatment::hid($id)->first();
        $tindakan=data_treatment_item::tindakan($id)->get();
        $tipe=[
          1=> 'Administrasi',
          2 => 'Tindakan',
          3 => 'Jasa'
        ];
        $bahan=data_resep_item::bahan($id)->get();
            return view('Pelayanan.treatment.detailjasa',[
            'data' =>$data,
            'tindakan' =>$tindakan,
            'tipe'=>$tipe,
            'bahan' =>$bahan,
            ]);

    }

     public function getAlljasa(Request $req){
        if($req->ajax()){
            $result = [];
            $items = data_treatment::bytreatment($req->nomor_treatment, $req->id_pasien_hc)->paginate($req->limit);
            $out = '';
            $total = $items->total();
           
            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                    $out .= '
                        <tr class="tr_' . $item->id_treatment . '">
                            <td>' . $no . '</td>
                            <td width="20%">
                                <div> ' .  $item->nomor_treatment. '</div>
                                <div class="link text-muted">
                                    <small>
                                        [
                                        |<a href="'. url('/jasa/detail/'.$item->id_treatment). '">Lihat</a>
                                        ]
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>' . $item->id_pasien_hc. '</div>
                                <div class="text-muted"><small>Pasien : ' . $item->nama_pasien. '</small></div>
                            </td>
                            <td>'.$item->nama_pasien.' </td>
                            <td>
                                <div>' . \Format::indoDate2($item->created_at) . '</div>
                                <div class="text-muted"><small>' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small></div>
                            </td>
                            <td>' . $item->nm_depan .''.$item->nm_belakang. '</td>
                        
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
            return redirect('/jasa');
        }

    }

}

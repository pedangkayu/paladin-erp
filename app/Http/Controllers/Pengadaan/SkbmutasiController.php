<?php

namespace App\Http\Controllers\Pengadaan;

use Illuminate\Http\Request;

//Model
use App\Models\data_mutasi_skb;
use App\Models\data_mutasi_skb_item;
use App\Models\data_mutasi_spb;
use App\Models\data_mutasi_spb_item;
use App\Models\ref_gudang;
use App\Models\data_karyawan;
use App\Models\ref_satuan;

///Job
use App\Jobs\Pengadaan\CreateSmbJob;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SkbmutasiController extends Controller
{

    public function getIndex()
    {
        $items=data_mutasi_skb::listsmb()->paginate(10);
        $title='Surat Mutasi Barang';
         $btn = [
                'status' => true,
                'title' => 'PMBU'
            ];
        $gud=ref_gudang::all();
        return view('Pengadaan.Smb.index',[
            'title' =>$title,
            'items' =>$items,
            'btn'   =>$btn,
            'gud'   =>$gud,
            ]);

    }
   
    public function getSpbm(){
        $data=data_mutasi_spb::termohon('',0)->paginate(10);
        $gudang=ref_gudang::all();
         $status = [
            1 => 'Baru',
            2 => 'Proses',
            3 => 'Selesai',
            4 => 'Batal',
            5 => '*Selesai'
        ];
          $akses = \Me::accessGudang();
        
        if(count($akses) > 1){
            $ket = [
                'status' => true,
                'title' => 'Surat PMBU'
            ];
        }elseif(count($akses) == 0){
            $ket = [
                'status' => false,
                'title' => 'Tidak ada Akses'
            ];
        }
        return view('Pengadaan.Smb.listspbmutasi',[
                'data' =>$data,
                'status' =>$status,
                'gudang' =>$gudang,
                'ket' =>$ket,
            ]);
    } public function getAllpmb(Request $req){
        if($req->ajax()){
            $result = [];
            $items = data_mutasi_spb::termohon($req->kode,$req->status, $req->pemohon_gud, $req->deadline,  $req->all())->paginate($req->limit);
            $out = '';
            //dd($req->All());
            $total = $items->total();
            $status = [
                1 => 'Baru',
                2 => 'Proses',
                4 => 'Batal',
                5 => 'Selesai'
            ];

            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){

                $acc = $item->id_acc > 0 ? '<i class="fa fa-check" title="Telah disetujui Kepala"></i>' : '<i class="fa fa-warning" title="Belum disetujui Kepala"></i>';

                    $out .= '
                        <tr class="spbm_' . $item->id_mutasi_spb . '">
                            <td>' . $no . '</td>
                            <td>
                                <div> ' . $item->no_mutasi_spb . ' ' . $acc . '</div>
                                <div class="link text-muted">
                                    <small>
                                        [
                                            
                                        <a href="#" onclick="detailspbmutasi(' . $item->id_mutasi_spb . ');" data-toggle="modal" data-target="#detail">Lihat</a>
                                           
                                        ]
                                    </small>
                                </div>
                            </td>

                            <td>
                                <div>' . $item->nm_depan . ' ' . $item->nm_belakang . '</div>
                                <div class="text-muted"><small>Dept : ' . $item->nm_departemen . '</small></div>
                            </td>

                            <td>
                                <div>' . $item->gudang_pemohon . '</div>
                            </td>
                            <td>
                                <div>' . \Format::indoDate2($item->created_at) . '</div>
                                <div class="text-muted"><small>' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small></div>
                            </td>
                            <td class="text-center">' . $status[$item->status] . '</td>
                        </tr>
                    ';

                    $no++;
                }
            else:
                $out = '
                    <tr>
                    <td colspan="5">Tidak ditemukan</td>
                    </tr>
                ';
            endif;

            $result['data'] = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/Smb/Spbm');
        }

    }
        public function postDetailspbmutasi(Request $req){
        if($req->ajax()){
            $result = [];
            $out = '';

            $spb = data_mutasi_spb::find($req->id);
           if($spb->status > 2){
                $items = data_mutasi_spb_item::join('data_barang','data_barang.id_barang', '=', 'data_mutasi_spb_item.id_item')
                    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_mutasi_spb_item.id_satuan')
                    ->where('data_mutasi_spb_item.id_mutasi_spb', $req->id)
                    ->whereIn('data_mutasi_spb_item.status',[1,2])
                    ->select('data_mutasi_spb_item.*', 'data_barang.nm_barang', 'data_barang.kode', 'ref_satuan.nm_satuan')
                    ->get();
            }else{
                $items = data_mutasi_spb_item::join('data_barang','data_barang.id_barang', '=', 'data_mutasi_spb_item.id_item')
                    ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_mutasi_spb_item.id_satuan')
                    ->where('data_mutasi_spb_item.id_mutasi_spb', $req->id)
                    ->where('data_mutasi_spb_item.status',1)
                    ->select('data_mutasi_spb_item.*', 'data_barang.nm_barang', 'data_barang.kode', 'ref_satuan.nm_satuan')
                    ->get(); 
            }

            if($spb->id_acc > 0){
                $me = data_karyawan::find($spb->id_acc);
                $out .= '<div class="grid simple">
                            <div class="grid-title no-border"></div>
                            <div class="grid-body no-border">
                                <b>Disetujui Oleh : </b> ' . $me->nm_depan . ' ' . $me->nm_belakang . '
                            </div>
                        </div>
                ';
            }else{
                $out .= '<div class="grid simple">
                            <div class="grid-title no-border"></div>
                            <div class="grid-body no-border">
                                <i class="fa fa-warning"></i> Permintaan belum disetujui Kepala
                            </div>
                        </div>
                ';
            }

            $out .= '<div class="grid simple">
                        <div class="grid-title no-border">
                        <h4>' . count($items) . ' barang <strong>ditemukan</strong></4><br />
                        <small>Deadline : ' . \Format::indoDate($spb->deadline) . '</small>
                        </div>
                        <div class="grid-body no-border">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Barang</th>
                                    <th class="text-right">Req Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                ';
            foreach($items as $item){
                $out .= '
                    <tr>
                        <td>' . $item->kode . '</td>
                        <td>' . \Format::substr($item->nm_barang,20) . '</td>
                        <td class="text-right">' . number_format($item->qty_awal,0,',','.') . ' ' . $item->nm_satuan . '</td>
                    </tr>
                ';
            }
            $out .= '
                            </tbody>
                        </table>
                    </div>
                </div>';

            $btn = $spb->id_acc > 0 && \Auth::user()->permission > 1 && in_array($spb->status, [1,2]) ? '<a href="' . url('/Smb/process/' . $req->id) . '" class="btn btn-primary">Proses</a>' : '';

            $result['kode']     = $spb->no_mutasi_spb;
            $result['content']  = $out;
            $result['button']   = $btn;

            return json_encode($result);

        }
    }
    public function getProcess($id){
        $spb = data_mutasi_spb::join('data_karyawan AS a', 'a.id_karyawan', '=', 'data_mutasi_spb.id_pemohon')
            ->leftJoin('data_karyawan AS b', 'b.id_karyawan', '=', 'data_mutasi_spb.id_acc')
            ->leftjoin('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_spb.id_departemen')
            ->where('data_mutasi_spb.id_mutasi_spb', $id)
            ->select(
                'a.nm_depan',
                'a.nm_belakang',
                'b.nm_depan AS acc_depan',
                'b.nm_belakang AS acc_belakang',
                'data_departemen.nm_departemen',
                'data_mutasi_spb.*'
            )
            ->first();

        $akses = \Me::accessGudang();

        if($spb->status > 2 || $spb->id_acc == 0)
            return redirect('/Smb/spbm');
        $items = data_mutasi_spb_item::byunit($id)->get();
        
        return view('Pengadaan.Smb.ProcessSmb', [
            'spb' => $spb,
            'items' => $items,
            'satuan' => ref_satuan::all()
        ]);
    }

    public function postProcess(Request $req){
    $smb = $this->dispatch(new CreateSmbJob($req->all()));
    if($smb['result'] == true){
            return redirect('/Smb/view/' . $smb['id'])->withNotif([
                'label' => $smb['label'],
                'err' => $smb['err']
            ]);
        }else{
            return redirect('/Smb/spbm')->withNotif([
                'label' => $smb['label'],
                'err' => $smb['err']
            ]);
        }
    }
     public function getView($id = 0){
        if(empty($id))
            return redirect('/Smb');

         $skb = data_mutasi_skb::byid($id);
        $items = data_mutasi_skb_item::byid($id)->get();
        return view('Pengadaan.Smb.View', [
            'skb' => $skb,
            'items' => $items
        ]);
    }
     public function getPrint($id){

        $find = data_mutasi_spb::join('data_karyawan AS a', 'a.id_karyawan', '=', 'data_mutasi_spb.id_pemohon')
            ->join('data_departemen', 'data_departemen.id_departemen', '=', 'data_mutasi_spb.id_departemen')
            ->join('data_mutasi_skb', 'data_mutasi_skb.id_mutasi_spb', '=', 'data_mutasi_spb.id_mutasi_spb')
            ->join('data_karyawan AS b', 'b.id_karyawan', '=', 'data_mutasi_skb.id_petugas')
            ->join('data_mutasi_skb_item', 'data_mutasi_skb_item.id_mutasi_skb', '=', 'data_mutasi_skb.id_mutasi_skb')
            // ->join('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_mutasi_skb.id_gudang')
            ->join('ref_gudang as pemohon', 'pemohon.id_gudang', '=', 'data_mutasi_skb.id_unit_asal')
            ->join('ref_gudang as termohon', 'termohon.id_gudang', '=', 'data_mutasi_skb.id_unit_tujuan')
            ->where('data_mutasi_skb.id_mutasi_skb', $id)
            ->select(
                'data_mutasi_spb.*', 
                'a.nm_depan', 
                'a.nm_belakang', 
                'data_departemen.nm_departemen', 
                'b.nm_depan As petugas_depan', 
                'b.nm_belakang AS petugas_belakang',
                'data_mutasi_skb.no_mutasi_skb',
                'pemohon.nm_gudang As nm_gudang_pemohon',
                'termohon.nm_gudang As nm_gudang_termohon'
            )
            ->groupby('data_mutasi_skb.id_mutasi_skb');
        $spb    = $find->first();

        $items = data_mutasi_skb_item::bysmb($id)->get();

        //dd($items);
        return view('Print.Pengadaan.smb', [
            'spb' => $spb,
            'items' => $items
        ]);
    }

     public function getAllsmb(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $items = data_mutasi_skb::listsmb($req->all())->paginate($req->limit);
            if($items->total() > 0){
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                    $out .= '
                        <tr>
                            <td>' . $no . '</td>
                            <td>
                                <div>' . $item->no_mutasi_skb . '</div>
                                <div class="link text-muted">
                                    <small>
                                        [
                                            <a href="' . url('/Smb/view/' . $item->id_mutasi_skb) . '">Lihat</a>
                                            | <a href="' . url('/Smb/print/' . $item->id_mutasi_skb ) . '" target="_blank">Print</a>
                                        ]
                                    </small>
                                </div>
                            </td>
                            <td>' . $item->no_mutasi_spb . '</td>
                            <td>' . $item->nm_depan . ' ' . $item->nm_belakang . '</td>
                            <td>
                                ' . $item->nm_gudang_asal . '
                                <div>
                                    <small class="text-muted"> ' . $item->nm_departemen . ' </small>
                                </div>
                            </td>
                            <td>
                                ' . \Format::indoDate($item->created_at) . '<br />
                                <small class="text-muted">' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small>
                            </td>
                        </tr>
                    ';

                    $no++;
                }
            }else{
                $out = '
                    <tr>
                        <td colspan="6">Tidak ditemukan</td>
                    </tr>
                ';
            }

            $res['content'] = $out;
            $res['pagin'] = $items->render();

            return json_encode($res);
            
        }
    }
    /*Mengambil Notifikasi dengan ajax*/
    public function getNotifsmb(Request $req){
        $gudang= \Me::subgudang()->id_gudang;
        if($req->ajax()){

            $akses = \Me::accessGudang();

            $spb = data_mutasi_spb::whereIn('status', [1,2])->where('data_mutasi_spb.id_unit_tujuan', $gudang)->count();
            return json_encode([
                'total' => $spb
            ]);
        }
    }



}

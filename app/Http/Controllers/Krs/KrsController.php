<?php

namespace App\Http\Controllers\Krs;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\data_pasien;
use App\Models\Views\view_krs;

class KrsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex(){
        $data['pasien'] = view_krs::groupby('nama_pasien')->get();
    return  view('Pelayanan.Krs.index',$data); 
    }
    public function getKrsajax(Request $req){
             if($req->ajax()){
                $res = [];
                $out = '';
                $items = view_krs::krs($req->all())->get();
                 $total= count($items);
                if($total > 0):
                    $no=1;
                    // $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                    foreach($items as $item){
                        $out .= '
                            <tr class="">
                                <td>'.$no.'</td>
                                <td>'. $item->nama_pasien .'</td>
                                <td>'.$item->id_antrian.'</td>
                                <td>'.\Format::indoDate2($item->daftar_rinap) .'</td>
                                <td>'.\Format::indoDate2($item->tgl_pakai).'</td>
                                <td>'.\Format::indoDate2($item->selesai_rinap).'</td>
                                <td>'.$item->nm_kamar.'</td>
                            </tr>
                        ';
                        $no++;
                    }
                else:
                    $out = '
                        <tr>
                        <td colspan="6">Tidak ditemukan</td>
                        </tr>
                    ';
                endif;
                $result['content'] = $out;
                $result['data']  = $out;
                $result['total'] = $total;

                return json_encode($result);

            }else{
                return redirect('/krs');
            }

        }
    public function getPrintkrs(Request $req){
    $krs = view_krs::krs($req->all())->get();
    return view('Pelayanan.Krs.Krsprint',[
        'krs' => $krs,
        'req'   => $req,
       
        ]);
    }


    
}

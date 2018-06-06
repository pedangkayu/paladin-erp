<?php

namespace App\Http\Controllers\LaporanKeuangan;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Views\view_report_jasa_medis;
use App\Models\data_karyawan;

class LaporanHonordokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $data['dokter'] = view_report_jasa_medis::groupby('nm_depan')->get();;
        $data['pasien'] = view_report_jasa_medis::groupby('nama_pasien')->get();
        return view('LaporanKeuangan.Honor.Honordokter',$data);
    }
    public function getDokterpasienjax(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $item = view_report_jasa_medis::medis($req->all())->get();

            $total= count($item);
            if($total > 0){
                $no=1;
                foreach ($item as $medis) {
                    $out .='
                            <tr>
                                <td>'.$no.'</td>
                                <td>'.$medis->nm_depan.' '.$medis->nm_belakang.'</td>
                                <td>'.$medis->nama_pasien.'</td>
                                <td>'.$medis->nm_gudang.'</td>
                                <td>'. \Format::indoDate2($medis->tgl_input).' '.\Format::hari($medis->tgl_input).' '.\Format::jam($medis->tgl_input).'</td>
                                <td>'.$medis->nm_service.'</td>
                                <td>'.number_format($medis->tarif_dr,0,',','.').'</td>
                            </tr>
                            ';
                        $no++;
                }

            }else{
              $out  ='
                <tr>
                    <td colspan="6">Tidak di Temukan</td>
                </tr>
                ';   
             }
        }
    $res['content'] = $out;
    return json_encode($res);
    }
    public function getPrintdokterpasien(Request $req){
    $medis = view_report_jasa_medis::medis($req->all())->get();
    return view('LaporanKeuangan.Honor.Print.Honordokter',[
        'medis' => $medis,
        'req'   => $req
        ]);
    }

   
}

<?php

namespace App\Http\Controllers\Antrian;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Mssql;

class AntrianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        
       
          return view('Pelayanan.Antrian.index');
    }
    public function getAntrian(Request $req){
        $me = \Me::data()->id_karyawan;
        if($req->ajax()){
            $res = [];
            $out = '';
        $items = \MSSQL::tbl("ANTRIAN")
         ->leftJoin('registrasi_pl', 'registrasi_pl.id_ps', '=', 'antrian.ID_PASIEN')
         ->join('JENIS_LAYANAN_RS', 'JENIS_LAYANAN_RS.ID_LAYANAN_RS', '=', 'ANTRIAN.ID_LAYANAN_RS')
         ->join('PEGAWAI', 'PEGAWAI.ID_PGW', '=', 'ANTRIAN.MAS_ID_PGW')
         ->join('PASIEN', 'PASIEN.ID_PASIEN', '=', 'ANTRIAN.ID_PASIEN')
         ->where('PEGAWAI.ID_PGW', $me)
         ->where('ANTRIAN.FINISH_ANTRIAN',1)
         ->select(
            'ANTRIAN.*',
            'PASIEN.*',
            'JENIS_LAYANAN_RS.*',
            'PEGAWAI.*'
            )
            ->paginate(4);
            // dd($items);
            $total=$items->total();
            if($total > 0):
                foreach ($items as $item) {
                    $out .='
                        <tr>
                        <td>'.$item->NO_ANTRIAN.'</td>
                        <td>'.$item->ID_PASIEN.'</td>
                        <td>'.$item->NAMA_PASIEN.'</td>
                        <td>'.$item->ALAMAT_PASIEN.'</td>
                        </tr>
                        ';
                }
                else:
                    $out .='
                        <tr>
                            <td colspan="4"> Tidak ditemukan</td>
                        </tr>
                        ';
                endif;
                $res['total'] = $total;
                $res['content']=$out;
                $res['pagin'] = $items->render();

                return json_encode($res);

        }
    }


}

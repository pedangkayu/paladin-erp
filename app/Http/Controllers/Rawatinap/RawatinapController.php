<?php

namespace App\Http\Controllers\Rawatinap;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ref_kamar;
use App\Models\data_rawat_inap;

use App\Jobs\Rawatinap\CreateRawatJob;
use App\Jobs\Rawatinap\InsertrawatJob;
use App\Jobs\Rawatinap\UpdaterawatJob;

use DB;
use Mssql;

class RawatinapController extends Controller
{
   
    public function getIndex(Request $req)
    {
    	$items=data_rawat_inap::cari()->paginate(10);
        // dd($items);
          $No_trans = [
            0 => 'Check-In',
            1 => 'Check-Out kamar',
            2 => 'Lunas',
            3 => '',
        ];
        
       	return view('Pelayanan.Rawatinap.index',[
               'items' =>$items,
               'No_trans' =>$No_trans
            ]);
    }
    public function getAllrawat(Request $req){
            // dd($req->all());
        if($req->Ajax()){
            $result = [];
            $items = data_rawat_inap::cari($req->id_pasien, $req->id_antrian, $req->No_trans, $req->nama_pasien, $req->all())->paginate($req->limit);
            $out = '';
            $total = $items->total();
             $No_trans = [
                    0 => 'Check-In',
                    1 => 'Check-Out kamar',
                    2 => 'Lunas',
        ];
        if($total > 0):
              $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                 foreach ($items as $item) {
                    if($item->No_trans == 2):
                           $a='Lunas';
                    elseif ($item->No_trans == 1): 
                         $a='Check-Out kamar';
                    else:
                        $a= 'Check-In';
                    endif;
                    if($item->tgl_pakai >0):
                        $selesai=''. \Format::indoDate2($item->tgl_pakai).' '.\Format::hari($item->tgl_pakai).' '.\Format::jam($item->tgl_pakai).'';
                    else:
                    $selesai='';
                    endif;
                    $out .='
                        <tr class="rawat_'. $item->id_rinap .'">
                            <td>'. $no. '</td>
                            <td>'. $item->id_antrian.'</td>
                            <td>'. $item->id_pasien.'</td>
                            <td>'. $item->nama_pasien.'</td>
                            <td>'. $item->alamat_pasien .'</td>
                            <td>
                            '.$selesai.'</td>
                            <td>'.$a.'</td>
                            <td>'. $item->nm_kamar.'</td>
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
             $result['items'] = $out;
            $result['pagin'] = $items->render();
            $result['total'] = $total;

            return json_encode($result);

        }else{
            return redirect('/Rawatinap');
        }
    }
    public function getLoadrawat(Request $req){
        if($req->ajax()){
            $res=[];
            $out = '';
            $items= \MSSQL::tbl('PEMAKAIAN_KAMAR')
                         ->leftJoin('PELAYANAN_RAWAT_INAP AS pk', 'pk.ID_ANTRIAN_RWT_INAP', '=','PEMAKAIAN_KAMAR.ID_ANTRIAN_RWT_INAP')
                         ->leftJoin('MASTER_KAMAR_RAWAT_INAP AS mkamar', 'mkamar.ID_KAMAR_RWT_INAP','=','PEMAKAIAN_KAMAR.ID_KAMAR_RWT_INAP')
                          ->leftJoin('MASTER_KELAS_RAWAT_INAP AS mkelas', 'mkelas.ID_KELAS_RINAP','=','mkamar.ID_KELAS_RINAP')
                         ->leftJoin('PASIEN AS ps', 'ps.ID_PASIEN', '=','pk.ID_PASIEN')
                         ->whereIn('pk.STATUS_MASUK_RWT_INAP',[1])
                         ->whereIn('pk.STATUS_KELUAR_RWT_INAP',[0])
                         ->whereNull('PEMAKAIAN_KAMAR.TGL_SELESAI_PAKAI_KAMAR')
                         ->whereNull('PEMAKAIAN_KAMAR.NO_TRANS')
                         ->Select('PEMAKAIAN_KAMAR.*','ps.NAMA_PASIEN', 'mkamar.NAMA_KAMAR_RWT_INAP','pk.*', 'mkelas.*')->paginate(10);
            $total= $items->total();
            if($total > 0):
                foreach($items as $item) { 
                    $out .='
                        <tr class="rawat-'.$item->ID_ANTRIAN_RWT_INAP.'">
                            <td>'.$item->NAMA_KAMAR_RWT_INAP.'</td>
                            <td>'.$item->NAMA_KELAS_RINAP.'</td>
                            <td>'.$item->ID_PASIEN.'</td>
                            <td>'.$item->NAMA_PASIEN.'</td>
                            <td>'.$item->TGL_MULAI_PAKAI_KAMAR . '<small class="pull-right  rawat-loading-'.$item->ID_ANTRIAN_RWT_INAP.'Memuat....</small></td>
                            <td class="text-right"><button onclick="add_rawat(\'' . $item->ID_ANTRIAN_RWT_INAP . '\');" class="btn btn-rawat-' . $item->ID_ANTRIAN_RWT_INAP . ' btn-white btn-small"><i class="fa fa-plus"></i></button></td>
                        </tr>
                    ';
                }else:
                $out ='
                <tr>
                    <td colspan="7">Tidak ditemukan</td>
                </tr>
                ';
                endif;
                 $res['total'] = $total;
            $res['content'] = $out;
            $res['pagin'] = $items->render();
            return json_encode($res);

        }
    }
    public function getAddrawat(Request $req){
        if($req->Ajax()){
            $res= [];
            $out =[];
            $item= \MSSQL::tbl('PEMAKAIAN_KAMAR')
                         ->leftJoin('PELAYANAN_RAWAT_INAP AS pk', 'pk.ID_ANTRIAN_RWT_INAP', '=','PEMAKAIAN_KAMAR.ID_ANTRIAN_RWT_INAP')
                         ->leftJoin('MASTER_KAMAR_RAWAT_INAP AS mkamar', 'mkamar.ID_KAMAR_RWT_INAP','=','PEMAKAIAN_KAMAR.ID_KAMAR_RWT_INAP')
                          ->leftJoin('MASTER_KELAS_RAWAT_INAP AS mkelas', 'mkelas.ID_KELAS_RINAP','=','mkamar.ID_KELAS_RINAP')
                         ->leftJoin('PASIEN AS ps', 'ps.ID_PASIEN', '=','pk.ID_PASIEN')
                         ->where('PEMAKAIAN_KAMAR.ID_ANTRIAN_RWT_INAP',$req->id)
                         ->whereIn('pk.STATUS_MASUK_RWT_INAP',[1])
                         ->whereIn('pk.STATUS_KELUAR_RWT_INAP',[0])
                         ->whereNull('PEMAKAIAN_KAMAR.TGL_SELESAI_PAKAI_KAMAR')
                         ->whereNull('PEMAKAIAN_KAMAR.NO_TRANS')
                         ->Select('PEMAKAIAN_KAMAR.*','ps.NAMA_PASIEN', 'mkamar.NAMA_KAMAR_RWT_INAP','pk.*', 'mkelas.*')->first();
                           // $pp = $this->dispatch(new InsertRawatMysqlJob($item));
                           $res['item'] = $item;
                            // $res['pas'] = $pa;
                          // $res['tgl']= \Format::indoDate2($pa->TGLLAHIR_PASIEN);
                        return json_encode($res);
        }
    }

    public function postRawat(Request $req){
        if(count($req->id_pasien) == 0 )

            return redirect()->back()->withNotif([
                'label' => 'danger',
                'err' => '<center>OOps!, Anda Belum Memilih Pasien Rawat Inap</center>'
            ]);

        $arr = $this->dispatch(new CreateRawatJob($req->all()));
        if($arr['res'])
            return redirect('/Rawatinap')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        else
            return redirect()->back()->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        }


    public function getDatapasien(Request $req){
        if($req->jenis==1){
          $data= \MSSQL::tbl('PEMAKAIAN_KAMAR')
                 ->leftJoin('PELAYANAN_RAWAT_INAP AS pk', 'pk.ID_ANTRIAN_RWT_INAP', '=','PEMAKAIAN_KAMAR.ID_ANTRIAN_RWT_INAP')
                 ->leftJoin('MASTER_KAMAR_RAWAT_INAP AS mkamar', 'mkamar.ID_KAMAR_RWT_INAP','=','PEMAKAIAN_KAMAR.ID_KAMAR_RWT_INAP')
                  ->leftJoin('MASTER_KELAS_RAWAT_INAP AS mkelas', 'mkelas.ID_KELAS_RINAP','=','mkamar.ID_KELAS_RINAP')
                 ->leftJoin('PASIEN AS ps', 'ps.ID_PASIEN', '=','pk.ID_PASIEN')
                 ->where('pk.ID_ANTRIAN_RWT_INAP',$req->id_antrian)
                 ->where('pk.ID_PASIEN', $req->id_pasien)
                 // ->where('pk.kamar', $req->kamar)
                 ->whereIn('pk.STATUS_MASUK_RWT_INAP',[1])
                 ->whereIn('pk.STATUS_KELUAR_RWT_INAP',[0])
                 ->whereNull('PEMAKAIAN_KAMAR.TGL_SELESAI_PAKAI_KAMAR')
                 ->whereNull('PEMAKAIAN_KAMAR.NO_TRANS')
                 ->Select('PEMAKAIAN_KAMAR.*','ps.*', 'mkamar.NAMA_KAMAR_RWT_INAP','pk.*', 'mkelas.*')->first();

                $rawat = $this->dispatch(new InsertrawatJob($data));

                if($rawat['res']){
                        return redirect('/authhc/logout')->withNotif([
                            'label' =>$rawat['label'],
                            'err' => $rawat['err']
                            ]);
                }else{
                        return redirect()->back()->withNotif([
                            'label' =>$rawat['label'],
                            'err' =>$rawat['err']
                            ]);
                    }

        }else{
              $data= \MSSQL::tbl('PEMAKAIAN_KAMAR')
                 ->leftJoin('PELAYANAN_RAWAT_INAP AS pk', 'pk.ID_ANTRIAN_RWT_INAP', '=','PEMAKAIAN_KAMAR.ID_ANTRIAN_RWT_INAP')
                 ->leftJoin('MASTER_KAMAR_RAWAT_INAP AS mkamar', 'mkamar.ID_KAMAR_RWT_INAP','=','PEMAKAIAN_KAMAR.ID_KAMAR_RWT_INAP')
                ->leftJoin('MASTER_KELAS_RAWAT_INAP AS mkelas', 'mkelas.ID_KELAS_RINAP','=','mkamar.ID_KELAS_RINAP')
                 ->leftJoin('PASIEN AS ps', 'ps.ID_PASIEN', '=','pk.ID_PASIEN')
                 ->where('pk.ID_ANTRIAN_RWT_INAP',$req->id_antrian)
                 ->where('pk.ID_PASIEN', $req->id_pasien)
                 // ->where('pk.kamar', $req->kamar)
                 ->whereIn('pk.STATUS_MASUK_RWT_INAP',[1])
                 ->whereIn('pk.STATUS_KELUAR_RWT_INAP',[1])
                 ->Select('PEMAKAIAN_KAMAR.*','ps.*', 'mkamar.NAMA_KAMAR_RWT_INAP','pk.*', 'mkelas.*')->first();
                $rawat = $this->dispatch(new UpdaterawatJob($data));

                    if($rawat['res'])
                    return redirect('/authhc/logout')->withNotif([
                        'label' =>$rawat['label'],
                        'err' => $rawat['err']
                        ]);
                    else
                    return redirect()->back()->withNotif([
                        'label' =>$rawat['label'],
                        'err' =>$rawat['err']
                        ]);
        }
        ///btas if
    }
}
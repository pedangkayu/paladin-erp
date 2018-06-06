<?php

namespace App\Http\Controllers\Penggajian;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\data_karyawan;
use App\Models\data_karyawan_honor;

use App\Models\Views\view_karyawan_detail;
use App\Models\data_log_honor;
use App\Models\data_loan;
use App\Models\data_log_honor_item;
use App\Models\ref_komponen_honor;
use App\Models\data_karyawan_potongan;
use App\Jobs\Penggajian\UpdatePenggajianJob;
use App\Jobs\Penggajian\CreateGajiJob;


class PenggajianController extends Controller
{
     public function getIndex(){
         $data['karyawan']=  view_karyawan_detail::all();
         return View('Penggajian.index',$data);
     }

     public function postKaryawan(Request $req){
         $data['karyawan']= data_karyawan::karyawanku($req->all())->paginate($req->limit);
         return View('Penggajian.data_karyawan_honor',$data);
     }
     public function postStatus(Request $req){
        //   dd($req->id_karyawan);
          $prode_tahun=date('Y', strtotime($req->tahun));
          $prode_bulan=11;
         //  $prode_bulan=date('m', strtotime($req->tanggal));
           $ar_fitur = explode(',', $req->id_karyawan);
        //    dd($ar_fitur);
          $res=[];
          $gaji=data_log_honor::whereIn('id_karyawan',$ar_fitur)
          ->where(\DB::raw('MONTH(data_log_honor.periode)'),$prode_bulan)
          ->where(\DB::raw('YEAR(data_log_honor.periode)'),$prode_tahun)->get();
          $out ='';
          $ka ='';
          foreach ($gaji as $k) {
              $ka .=$k->id_karyawan;

            if ($k->status_pembayaran==1) {
                 $status ='Belum Diterima';
                 $label ='label-important';

             }elseif ($k->status_pembayaran==2) {
                 $status='Sudah Diterima';
                 $label ='label-danger';
             }
              $out .= '' . $status . '';
          }
          $res['status'] = $out;
          $res['karyawan'] = $ka;
        //   dd($res['status'] );
        return response()->json($res);
        //   return json_encode($res);
      }

     public function postGet_status_gaji(Request $req){
        //  dd($req->all());
         $prode_tahun=date('Y', strtotime($req->tahun));
         $prode_bulan=date('m', strtotime($req->tanggal));
         $res=[];
         $gaji=data_log_honor::where('id_karyawan',$req->id_karyawan)
         ->where(\DB::raw('MONTH(data_log_honor.periode)'),$prode_bulan)
         ->where(\DB::raw('YEAR(data_log_honor.periode)'),$prode_tahun)->get();
         $out ='';
         foreach ($gaji as $k) {
             $out .= '<td>' . $k->status_pembayaran . '</td>';
         }
         $res['gaji'] = $out;
         return json_encode($res);
     }
        public function getListpenggajian(Request  $req){
            $data['karyawan']=  view_karyawan_detail::all();
            return View('Penggajian.list_penggajian',$data);
        }
        public function getAllpenggajian(Request $req){
           // dd($req->all());
            if($req->ajax()){
                $result = [];
                $items = data_log_honor::bygajian($req->all())->paginate($req->limit);
                $out = '';

                $total = $items->total();
                $status_pembayaran = [
                   0 => '',
                   1 => 'Belum Diterima',
                   2 => 'Sudah Diterima',
               ];
                if($total > 0):
                    $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                    foreach($items as $item){
                        $out .= '
                            <tr class="sr_' . $item->id_resep . '">
                            <tr class="honor_'.$item->id_log_honor.'">
                                <td>'.$no.'</td>
                                <td>'. $item->nm_depan .' '.$item->nm_belakang.'</td>
                                <td>'.$item->nm_departemen.'<br />
                                    <small><i>'.$item->nama_profesi.'</i></small>
                                </td>
                                <td>' .$status_pembayaran[$item->status_pembayaran] . '</td>
                                <td>'.\Format::indoDate2($item->periode) .'</td>
                                <td><a href="#" onclick="event.preventDefault();detailgajiku('. $item->id_log_honor .');" data-toggle="modal" data-target="#detail" class="text-danger " title="Review Data">
                                <i class="fa fa-pencil-square-o"></i></a>
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
                $result['content'] = $out;
                $result['data']  = $out;
                $result['pagin'] = $items->render();
                $result['total'] = $total;

                return json_encode($result);

            }else{
                return redirect('/resep');
            }

        }

     public function getDetailhonorku($id){
         $req->all();
         $data['data_karyawan'] = view_karyawan_detail::where('id_karyawan',$id)->first();
         $data ['detail_gaji'] = data_karyawan_honor::detail()->where('data_karyawan_honor.id_karyawan',$id)->get();
         $gaji = $data ['detail_gaji'];
         return view('Penggajian.modal_detail_honorku',$data);
     }
     public function postBtnbayar(Request $req){
 	    if($req->ajax()){
 	        $bayar = data_log_honor::find($req->id);
 	        $bayar->update([
 	        	'status_pembayaran'=>2,
 	        ]);
            return json_encode($bayar);
 	    }
    }

    public function getGajian(){
        $data['karyawan'] = view_karyawan_detail::whereNotIn('jabatan',[0])->get();
        return view('Penggajian.gajian',$data);
    }


    public function postDetailkaryawan(Request $req){
        $data['data_karyawan'] = view_karyawan_detail::where('id_karyawan',$req->id_karyawan)->first();
        $data ['detail_gaji'] = data_karyawan_honor::detail()->where('data_karyawan_honor.id_karyawan',$req->id_karyawan)->get();
        $gaji = $data ['detail_gaji'];
        return view('Penggajian.data-detail-gaji',$data);
    }


    public function getReviewgaji($id){
        $data1 = data_karyawan_honor::detail()->where('data_karyawan_honor.id_karyawan',$id)->get();
       if($data1->count() == 0)
       return redirect('/penggajian')->withNotif([
          'label' => 'danger',
          'err' => 'Mohon karyawan Yang anda pilih belum di seting di bagian personalia'
       ]);
        $data['data_karyawan'] = view_karyawan_detail::where('id_karyawan',$id)->first();
        $data['data_loan'] = data_loan::where('id_karyawan',$id)->whereNotIn('status',[1,3])->get();
        $data ['detail_gaji'] = data_karyawan_honor::detail()->where('data_karyawan_honor.id_karyawan',$id)->get();
        $data ['history_gaji'] = data_log_honor::where('id_karyawan',$id)->get();
        return view ('Penggajian.step_review_gaji',$data);
    }


    public function postReviewgaji(Request $req){
        $id=$req->id_karyawan;
        $prode_tahun=date('Y', strtotime($req->tanggal));
        $prode_bulan=date('m', strtotime($req->tanggal));
        $prode_bulan_in=date('M', strtotime($req->tanggal));

        $cek_prode_bayar = data_log_honor::where('id_karyawan',$id)
        ->where(\DB::raw('MONTH(data_log_honor.periode)'),$prode_bulan)
        ->where(\DB::raw('YEAR(data_log_honor.periode)'),$prode_tahun);
        if($cek_prode_bayar->count() >0)
            return redirect('/penggajian')->withNotif([
               'label' => 'danger',
               'err' => 'Karyawan Ini Sudah Menerima gaji pada periode Bulan ini '.$prode_bulan_in.'/'.$prode_tahun.''
            ]);
        $arr = $this->dispatch(new CreateGajiJob($req->all()));
        $id_log_honor=$arr['id_log_honor'];
        // dd($id_log_honor);
        if($arr['res'])
            return redirect('/penggajian/gajiku/'.$id_log_honor)->withNotif([
                'label' => 'success',
                'err' => '<center>Berhasil Input Transaksi Honor Karyawan</center>'
            ]);
        }


        public function getPendapatanku(Request $req){
                if ($req->ajax()){
                    $res=[];
                    $komponen=ref_komponen_honor::whereIn('tipe',[1])->get();
                    $out ='';
                    foreach ($komponen as $k) {
                        $out .= '<option value="' . $k->id_komponen_honor . '">' . $k->nm_komponen_honor . '</option>';
                    }
                    $res['komponen'] = $out;
                    return json_encode($res);
                }
        }


        public function getPotonganku(Request $req){
                if ($req->ajax()){
                    $res=[];
                    $potonganku=ref_komponen_honor::whereIn('tipe',[2])->get();
                    $out ='';
                    foreach ($potonganku as $k) {
                        $out .= '<option value="' . $k->id_komponen_honor . '">' . $k->nm_komponen_honor . '</option>';
                    }
                    $res['potonganku'] = $out;
                    return json_encode($res);
                }
        }


    public function getGajiku($id){
        $data['data_karyawan'] = data_log_honor::join('data_karyawan','data_karyawan.id_karyawan', '=', 'data_log_honor.id_karyawan')
                                            ->join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
                                                ->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
                                                ->join('data_departemen','data_departemen.id_departemen', '=', 'data_karyawan.id_departemen')
                                                ->join('ref_profesi','ref_profesi.id_profesi','=', 'data_karyawan.id_profesi')->where('data_log_honor.id_log_honor',$id)->first();
        $data ['detail_gaji'] = data_log_honor::byid($id)->get();
        $data['data_potongan'] = data_log_honor::bypotongan($id)->get();
        $data['data_casbon'] = data_log_honor::bycasbon($id)->get();
        return view ('Penggajian.gaji_karyawan_bulan',$data);
    }
    public function getDetailgaji($id){
        // dd($req->all());
        $data['data_karyawan'] = data_log_honor::join('data_karyawan','data_karyawan.id_karyawan', '=', 'data_log_honor.id_karyawan')
                                            ->join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
                                                ->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
                                                ->join('data_departemen','data_departemen.id_departemen', '=', 'data_karyawan.id_departemen')
                                                ->join('ref_profesi','ref_profesi.id_profesi','=', 'data_karyawan.id_profesi')->where('data_log_honor.id_log_honor',$id)->first();
        $data ['detail_gaji'] = data_log_honor::byid($id)->get();
        $data['data_potongan'] = data_log_honor::bypotongan($id)->get();
        $data['data_casbon'] = data_log_honor::bycasbon($id)->get();
        return view ('Penggajian.modal_detail_gaji',$data);
    }
    public function getUpdate($id){
        $data['data_karyawan'] = data_log_honor::join('data_karyawan','data_karyawan.id_karyawan', '=', 'data_log_honor.id_karyawan')
                                            ->join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
                                                ->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
                                                ->join('data_departemen','data_departemen.id_departemen', '=', 'data_karyawan.id_departemen')
                                                ->join('ref_profesi','ref_profesi.id_profesi','=', 'data_karyawan.id_profesi')->where('data_log_honor.id_log_honor',$id)->first();
        $data ['detail_gaji'] = data_log_honor::byid($id)->get();
        $data['data_potongan'] = data_log_honor::bypotongan($id)->get();
        $data['data_casbon'] = data_log_honor::bycasbon($id)->get();
        return view ('Penggajian.edit_gaji',$data);
    }
    public function postUpdate(Request $req){
        $arr = $this->dispatch(new UpdatePenggajianJob($req->all()));
        $id_log_honor=$arr['id_log_honor'];
        // dd($arr);
        if($arr['res'])
            return redirect('/penggajian/gajiku/'.$id_log_honor)->withNotif([
                'label' => 'success',
                'err' => '<center>Berhasil Input Transaksi Honor Karyawan</center>'
            ]);
    }

    public function getPrint($id){
        $data_karyawan = data_log_honor::join('data_karyawan','data_karyawan.id_karyawan', '=', 'data_log_honor.id_karyawan')->where('data_log_honor.id_log_honor',$id)->first();
        $detail_gaji = data_log_honor::byidprint($id)->get();
        $data_potongan = data_log_honor::bypotonganprint($id)->get();
        $data_casbon = data_log_honor::bycasbonprint($id)->get();
        return view('Penggajian.print.gaji',[
            'data_karyawan' =>$data_karyawan,
            'detail_gaji' =>$detail_gaji,
            'data_potongan' =>$data_potongan,
            'data_casbon' =>$data_casbon
        ]);
    }
    public function getGajibulanan(Request $req){
        # code...
    }

}

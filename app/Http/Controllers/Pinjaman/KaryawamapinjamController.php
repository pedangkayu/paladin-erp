<?php

namespace App\Http\Controllers\Pinjaman;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\data_karyawan;
use App\Models\data_loan;
use App\Models\data_log_loan;
use App\Models\ref_bank;
use App\Models\ref_payment_method;

use App\Jobs\Pinjaman\CreatePinjamanJob;
use App\Jobs\Pinjaman\UpdatePinjamanJOb;


class KaryawamapinjamController extends Controller
{
    public function getIndex()
    {
        $data['pinjaman']=data_loan::pinjaman()->paginate(10);
        $data['status']= [
            1=>'Baru',
            2=>'Acc',
            3=>'Lunas',
            ];
        return view('Pinjaman.index',$data);
    }
    public function getCreate(){
        $data['karyawan']=data_karyawan::join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
                                    ->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
                                    ->where('tipe_status', 1)->get();
        return view('Pinjaman.create',$data);
    }
    public function postCreate(Request $req){
        // dd($req->all());
        if(count($req->nominal) == 0)
        return redirect()->back()->withNotif([
            'label' => 'danger',
            'err' => '<center>OOps!, nominal Tidak Boleh Kosong </center>'
        ]);

        $arr = $this->dispatch(new CreatePinjamanJob($req->all()));
      if($arr['res'])

             return redirect('Pinjaman/')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
     else
        return redirect('Pinjaman/')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    }
    public function getAllpinjaman(Request $req){
       // dd($req->all());
        if($req->ajax()){
            $result = [];
            $items = data_loan::pinjaman($req->all())->paginate($req->limit);
            $out = '';
            // dd($items);
            $total = $items->total();

            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                    $akhir =$item->nominal - $item->total_terbayar ;
                     $status= [
                            1=>'Baru',
                            2=>'Disetujui',
                            3=>'Lunas',
                            ];
                            if($item->status == 2):
                            $print='|<a href="'. url('/Pinjaman/printpinjaman/' . $item->id_loan) .'" target="_blank" ><i class="fa fa-print"></i> Print</a>';
                            $kembali='|<a href="'.url('/Pinjaman/kembali/'.$item->id_loan).'">Kredit Pinjaman</a>';
                            elseif($item->status == 3):
                                $print='|<a href="'. url('/Pinjaman/printpinjaman/' . $item->id_loan) .'" target="_blank" ><i class="fa fa-print"></i> Print</a>';
                                $kembali='';
                            else:
                                $print='';
                                $kembali='';

                            endif;
                    $out .= '
                        <tr class="sr_' . $item->id_loan . '">
                            <td>' . $no . '</td>
                            <td width="20%">
                                <div> '. $item->nd.' '.$item->nb.'</div>
                                <div class="link text-muted">
                                    <small>
                                        <a href="#" onclick="event.preventDefault();detailpinjaman('. $item->id_loan .');" data-toggle="modal" data-target="#detail">Lihat</a>

                                            '.$print.' '.$kembali.'

                                    </small>
                                </div>
                            </td>
                            <td>'. $item->no_pinjaman .'</td>
                           <td>'. \number_format($item->nominal,0,',','.') .'</td>
                           <td>'. \number_format($akhir,0,',','.') .'</td>
                            <td>'.\Format::indoDate2($item->start_time) .' s/d '.\Format::indoDate2($item->end_time).'</td>
                            <td>'.$status[$item->status].'</td>


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
            return redirect('/Pinjaman');
        }

    }
    public function getDetailpinjaman($id){

            $data['item']=data_loan::pinjaman()->where('data_loan.id_loan',$id)->first();
             return view('Pinjaman.modal_acc_pinjaman',$data);
    }
    public function postAccpinjaman(Request $req){
    if($req->ajax()){
        $pinjaman = data_loan::find($req->id);
        $pinjaman->update([
            'id_acc' => \Me::data()->id_karyawan,
            'status'    =>2,
            'tgl_approval' => date('Y-m-d H:i:s')
        ]);

        \Loguser::create('Melakukan Verifikasi terhadap Permohonan Pinjaman Dana dengan No#. ' . $pinjaman->no_pinjaman);
    }
    }
    public function getEdit($id){
        $data['item']=data_loan::pinjaman()->where('data_loan.id_loan',$id)->first();
        $data['karyawan']=data_karyawan::join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
                                    ->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
                                    ->where('tipe_status', 1)->get();
        return view('Pinjaman.modal_pinjaman',$data);

    }
    public function getPrintpinjaman($id){
        $data=data_loan::id($id);
      if($data->count() == 0)
          return redirect('/Pinjaman');
        $data=data_loan::id($id)->first();
        $log=data_log_loan::byid($id)->get();
        // dd($data);
        return view('Pinjaman.Print.pinjaman',[
            'data' =>$data,
            'log'   =>$log,
            ]);
    }
    public function getKembali($id){
         $data['data']=data_loan::id($id);
      if($data['data']->count() == 0)
          return redirect('/Pinjaman');
        $data['data']=data_loan::id($id)->first();
        $data['data_karyawan']= data_karyawan::join('ref_jabatan','ref_jabatan.id','=','data_karyawan.jabatan')
                                    ->join('ref_status_karyawan','ref_status_karyawan.id','=','data_karyawan.id_status')
                                    ->where('tipe_status', 1)->get();
        // dd($data);
        $data['banks'] = ref_bank::all();
        $data['metode']=ref_payment_method::get();
        return view('Pinjaman.Pengembalian.create',$data);
    }
    public function postKembali(Request $req){
        if(count($req->pengembalian) == 0)
            return redirect()->back()->withNotif([
                'label' => 'danger',
                'err' => '<center>OOps!, nominal Tidak Boleh Kosong </center>'
            ]);
        $arr = $this->dispatch(new UpdatePinjamanJOb($req->all()));
          if($arr['res'])

                 return redirect('Pinjaman/')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
         else
            return redirect('Pinjaman/')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        }

}

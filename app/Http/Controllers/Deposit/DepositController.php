<?php

namespace App\Http\Controllers\Deposit;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\data_pasien;
use App\Models\data_log_deposit;
use App\Models\ref_bank;
use App\Models\data_deposit;
use App\Models\ref_payment_method;
use App\Jobs\Deposit\CreatedepositJob;
use App\Jobs\Deposit\PengembalianJob;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex(){
       $depo=data_deposit::join('data_pasien', 'data_pasien.id_pasien_hc', '=', 'data_deposit.id_pasien')->paginate(10);
        return view('Deposit.index',[
            'depo' =>$depo,
            ]);
    }
    public function getAlldeposit(Request $req){
       // dd($req->all());
        if($req->ajax()){
            $result = [];
            $items = data_deposit::byidpasien($req->id_pasien_hc,$req->nama_pasien, $req->nomor_resep, $req->status, $req->status_resep, $req->all())->paginate($req->limit);
            $out = '';

            $total = $items->total();

            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                
                    $out .= '
                        <tr class="sr_' . $item->id_deposit . '">
                            <td>' . $no . '</td>
                            <td width="20%">
                                <div> ' .  $item->nama_pasien. '</div>
                                <div class="link text-muted">
                                    <small>
                                            <a href="'. url('/Deposit/detail/'.$item->id_resep). '">Lihat &middot; </a>
                                            <a href="'. url('/Deposit/edit/'.$item->id_Deposit) .'">Edit &middot; </a>
                                    </small>
                                </div>
                            </td>
                            <td>
                               ' . $item->id_pasien_hc. '
                                <div class="text-muted"><small>' . \Format::indoDate2($item->created_at) . '
                                ' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small></div>
                            </td>
                            <td>Rp.'.number_format($item->saldo,0,',','.') .'</td>
                            <td>' . \Format::indoDate2($item->tanggal) . '</td>
                            <td>' . $item->keterangan .'</td>
                            
                           
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
            return redirect('/Deposit');
        }

    }
    public function getCreate(){
        $data=data_pasien::get();
        $metode=ref_payment_method::get();
        // dd($data);
        $banks = ref_bank::all();
        return view('Deposit.create',[
            'metode' =>$metode,
            'data' => $data,
            'banks' =>$banks
            ]);
    }
    public function postCreate(Request $req){
    if(count($req->nominal) == 0)
        return redirect()->back()->withNotif([
            'label' => 'danger',
            'err' => '<center>OOps!, nominal Tidak Boleh Kosong </center>'
        ]);

    $arr = $this->dispatch(new CreatedepositJob($req->all()));
      if($arr['res'])
            
             return redirect('Deposit/')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
     else
        return redirect('Deposit/')->withNotif([
                'label' => $arr['label'],
                'err' => $arr['err']
            ]);
    }

    public function getTransaksi($id){
         $data = data_deposit::hid($id);
      
        if($data->count() == 0)
            return redirect('/resep');
              $data = data_deposit::hid($id)->first();
              $detail= data_log_deposit::byid($id)->get();

            $id_payment_method = [
            0 => '',
            1 => 'Bank',
            3 => 'Cash'
        ];
        return view('Deposit.transaksi',[
            'data' =>$data,
            'detail'=> $detail,
            'id_payment_method'=>$id_payment_method
            ]);
    }
    public function getPrint($id){
         $data = data_deposit::hid($id);
      
        if($data->count() == 0)
            return redirect('/Deposit');
              $data = data_deposit::hid($id)->first();
              $detail= data_log_deposit::byid($id)->get();
            $id_payment_method = [
            0 => '',
            1 => 'Bank',
            3 => 'Cash'
        ];
        return view('Deposit.printtransaksi',[
            'data' =>$data,
            'detail'=> $detail,
            'id_payment_method'=>$id_payment_method
            ]);
    } 
    public function getCreatepengembalian($id){
        $data=data_deposit::hid($id);
         if($data->count() == 0)
            return redirect('/Deposit');
        $data = data_deposit::hid($id)->first();
        $metode=ref_payment_method::get();
        // dd($data->id_pasien);
        $banks = ref_bank::all();
        $pasien=data_pasien::get();
        return view('Deposit.Pengembalian.create',[
            'metode' =>$metode,
            'data'   => $data,
            'banks'  =>$banks,
            'pasien' =>$pasien
            ]);
    }  
    public function postCreatepengembalian(Request $req){
        if(count($req->keluar) == 0)
            return redirect()->back()->withNotif([
                'label' => 'danger',
                'err' => '<center>OOps!, nominal Tidak Boleh Kosong </center>'
            ]);

        $arr = $this->dispatch(new PengembalianJob($req->all()));
          if($arr['res'])
                
                 return redirect('Deposit/')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
         else
            return redirect('Deposit/')->withNotif([
                    'label' => $arr['label'],
                    'err' => $arr['err']
                ]);
        }
   }

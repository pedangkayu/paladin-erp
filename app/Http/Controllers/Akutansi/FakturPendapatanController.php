<?php

namespace App\Http\Controllers\Akutansi;

use Illuminate\Http\Request;

use App\Models\data_faktur;
use App\Models\data_payer;
use App\Models\ref_payment_terms;
use App\Models\data_faktur_item;
use App\Models\data_jurnal;
use App\Models\ref_payment_method;
use App\Models\ref_coa;

use App\Jobs\Akutansi\Pendapatan\AddCustomerJob;
use App\Jobs\Akutansi\Pendapatan\CreateFakturPendapatanJob;
use App\Jobs\Akutansi\Pendapatan\CreateSaveJurnalJob;
use App\Jobs\Akutansi\Pendapatan\EditpendapatanJob;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\data_config_coa_pendapatan as Config;

class FakturPendapatanController extends Controller {

    public function getIndex(){
        $items = data_faktur::pendapatan()->paginate(10);
        $status = [
            0 => 'Belum Bayar',
            1 => 'Nyicil',
            2 => 'Lunas',
            3 => 'Batal'
        ];
        return view ('Akutansi.FakturPendapatan.index',[
            'items'     =>$items,
            'status'    =>$status,
            ]);
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
    public function getCustomers(Request $req){
       //dd($req->all());
        if($req->ajax()){
            $res = [];
            $out = '<option value="">-Pilih Customer-</option>';
            $items = data_payer::where('status', 1)->get();
            foreach($items as $item){
                $select = $req->select == $item->id_payer ? 'selected="selected"' : '';
                $out .= '<option value="' . $item->id_payer . '" ' . $select . '>' . $item->nm_payer . ''.$item->nm_last.'</option>';
            }
            $res['content'] = $out;
            return json_encode($res);
        }
    }

    public function postAddcustomer(Request $req){
        //dd($req->all());
        if($req->ajax()){
            $arr=$this->dispatch(new AddCustomerJob($req->All()));
            return json_encode($arr);
        }
    }

    public function getBaru(){

        $terms = ref_payment_terms::all();

        return view('Akutansi.FakturPendapatan.Baru', [
            'terms' => $terms
        ]);
    }
    public function postBaru(Request $req){
        if (count($req->id_barang)==0)
            return redirect()->back()->withNotif([
                'label' =>'danger',
                'err' =>'<center>Ooops!, Tidak ada item yang anda masukan silahkan ulangi lagi masukan item dengan benar </center>'
            ]);
        $arr = $this->dispatch(new CreateFakturPendapatanJob($req->all()));
        if($arr['res'])
            return redirect('/fakturpendapatan')->withNotif([
                'label' =>$arr['label'],
                'err'   =>$arr['err'],
                ]);
        else
            return redirect()->back()->withNotif([
                'label' =>$arr['label'],
                'err'   =>$arr['err'],
                ]);

    }
    public function getView($id){
        if(empty($id))
            return redirect('/fakturpendapatan');
        $faktur = data_faktur::viewpendapatan($id)->first();
        $items = data_faktur_item::byitems($id)->get();

        $config =  Config::active()->first();

        if($faktur == null)
            return redirect('/fakturpendapatan')->withNotif([
                'label' => 'danger',
                'err' => 'Faktur tidak ditemukan'
                ]);
        if($faktur->status == 3)
            return redirect('/fakturpendapatan');

        $status = [
            0 => [
                'label' => 'important',
                'err' => 'Unpaid'
            ],
            1 => [
                'label' => 'warning',
                'err' => 'Partially Paid'
            ],
            2 => [
                'label' => 'info',
                'err' => 'Paid'
            ],
            3 => [
                'label' => 'important',
                'err' => 'Batal'
            ]
        ];

        $methods = ref_payment_method::all();

        $jurnals = data_jurnal::faktur($id)->get();

        $coas = [];
        foreach(ref_coa::where('cash', '<>', 1)->orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
        }

        $select_coa = \Format::select_coa_faktur($coas);
        $akun_bank = ref_coa::where('cash', 1)->get();

        //dd($akun_bank);
        $total_bayar = 0;
        foreach($jurnals as $ju){
            $total_bayar += $ju->total;
        }
        
        return view('Akutansi.FakturPendapatan.view', [
            'faktur' => $faktur,
            'items' => $items,
            'status' => $status,
            'methods' => $methods,
            'jurnals' => $jurnals,
            'select_coa' => $select_coa,
            'total_bayar' => $total_bayar,
            'akun_bank' => $akun_bank,
            'config' => $config
        ]);
    }
    public function postSavejurnal(Request $req){
        $arr = $this->dispatch(new CreateSaveJurnalJob($req->all()));
        return redirect()->back()->withNotif([
            'label' => $arr['label'],
            'err' => $arr['err']
        ]);
    }
    public function getEdit($id = 0){

        if(empty($id))
            return redirect('/fakturpendapatan');

        $faktur = data_faktur::find($id);
        $items = data_faktur_item::byitems($id)->get();

        if($faktur->status == 3)
            return redirect('/fakturpendapatan');

        $terms = ref_payment_terms::all();      
        return view('Akutansi.FakturPendapatan.edit', [
            'faktur' => $faktur,
            'items' => $items,

            'terms' => $terms
        ]);
    }
    public function postEdit(Request $req){

        $arr = $this->dispatch(new EditpendapatanJob($req->all()));

        return redirect()->back()->withNotif([
            'label' => $arr['label'],
            'err' => $arr['err']
        ]);

    }

    public function getAlamat(Request $req){
        if($req->ajax()){
            $res = [];
            $payer = data_payer::find($req->id);
            if($payer == null)
                $res['alamat'] = '';
            else
                $res['alamat'] = $payer->alamat;
            return json_encode($res);
        }
    }
        public function getPrint($id){

        if(empty($id))
            return redirect('/fakturpembelian');

         $faktur =data_faktur::viewpendapatan($id)->first();
        $items=data_faktur_item::byitems($id)->get();

        if($faktur->status == 3)
            return redirect('/fakturpendapatan');

        $status = [
            0 => [
                'label' => 'danger',
                'err' => 'Unpaid'
            ],
            1 => [
                'label' => 'info',
                'err' => 'Partially Paid'
            ],
            2 => [
                'label' => 'primary',
                'err' => 'Paid'
            ],
            3 => [
                'label' => 'important',
                'err' => 'Batal'
            ]
        ];

        $jurnals = data_jurnal::faktur($id)->get();

        return view('Akutansi.FakturPendapatan.print', [
            'faktur' => $faktur,
            'items' => $items,
            'status' => $status,
            'jurnals' => $jurnals
        ]);
    }
        public function postStatus(Request $req){
        if($req->ajax()){
            data_faktur::find($req->id)->update([
                'status' => $req->status
            ]);

            $status = [
                0 => [
                    'label' => 'important',
                    'err' => 'Unpaid'
                ],
                1 => [
                    'label' => 'warning',
                    'err' => 'Partially Paid'
                ],
                2 => [
                    'label' => 'info',
                    'err' => 'Paid'
                ],
                3 => [
                    'label' => 'important',
                    'err' => 'Batal'
                ]
            ];

            $out = '
                <span class="label label-' . $status[$req->status]['label'] . '">
                    ' . $status[$req->status]['err'] . '
                </span>
            ';

            return json_encode([
                'err' => $out,
                'status' => $status[$req->status]['err']
            ]);

        }
    }
   
}

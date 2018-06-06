<?php

namespace App\Http\Controllers\Pengadaan;

use Illuminate\Http\Request;
	// Modes
use App\Models\data_mutasi_spb;
use App\Models\data_mutasi_spb_item;
use App\Models\ref_gudang;
use App\Models\data_barang;
use App\Models\data_item_gudang;
use App\Models\data_karyawan;

	//job
use App\Jobs\Pengadaan\Mutasi\CreateMutasiJob;
use App\Jobs\Pengadaan\Mutasi\UpdatemutasiSpbJob;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MutasiController extends Controller
{
 
    public function getIndex()
    {
    	$data=data_mutasi_spb::pemohon('',0)->paginate(10);
        $gudang=ref_gudang::all();
         $status = [
            1 => 'Baru',
            2 => 'Proses',
            3 => 'Selesai',
            4 => 'Batal',
            5 => '*Selesai'
        ];
        return view('Pengadaan.Mutasi.index',[
        		'data' =>$data,
                'status' =>$status,
                'gudang' =>$gudang,
        	]);
    }
    public function getCreate(){
    	  $gudangs = ref_gudang::all();
    	return view('Pengadaan.Mutasi.create',[
            'gudangs'   => $gudangs,
            'id_gudang' => \Me::subgudang()->id_gudang
    		]);
    }
       public function postCreate(Request $req){

        if($req->id_barang == 0 || $req->id_gudang_tujuan == 0)
            return redirect()->back()->withNotif([
                'label' => 'danger',
                'err' => 'Gudang tujuan tidak boleh kosong!'
            ]);
        
        $spb = $this->dispatch(new CreateMutasiJob($req->all()));
        
        if($spb['res']){
            
            return redirect('/Mutasi')->withNotif([
                'label' => $spb['label'],
                'err' => $spb['err']
            ]);
        }else{
            return redirect()->back()->withNotif([
                'label' => $spb['label'],
                'err' => $spb['err']
            ]);
        }
    }
    public function getEditmutasi($id){

         $spbmutasi=data_mutasi_spb::byid($id);
            if($spbmutasi->count() == 0)
              return redirect('/Mutasi');
        $spbmutasi=data_mutasi_spb::byid($id)->first();
        $item=data_mutasi_spb_item::byitem($id)->get();
        $gudangs=ref_gudang::all();
        return view('Pengadaan.Mutasi.editspbmutasi',[
                 'gudangs'   => $gudangs,
                 'id_gudang' => \Me::subgudang()->id_gudang,
                 'spb'      =>$spbmutasi,
                 'item'     =>$item,
            ]);

    }
    public function postEditmutasi(Request $req){
        //dd($req->all());
       $arr = $this->dispatch(new UpdatemutasiSpbJob($req->all()));
           if($arr['res'])
              return redirect('/Mutasi')->withNotif([
                      'label' => $arr['label'],
                      'err' => $arr['err']
                  ]);
          else
              return redirect('/Mutasi')->withNotif([
                      'label' => $arr['label'],
                      'err' => $arr['err']
                  ]);
    
    }
    public function postDetailspbmutasi(Request $req){
        if($req->ajax()){
            $result = [];
            $out = '';

            $spb = data_mutasi_spb::find($req->id);

            if($spb->status > 2){
                $items = data_mutasi_spb_item::join('data_barang','data_barang.id_barang', '=', 'data_mutasi_spb_item.id_item')
                                            ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                                            ->where('data_mutasi_spb_item.id_mutasi_spb', $req->id)
                                            ->whereIn('data_mutasi_spb_item.status',[1,2])
                                            ->select('data_mutasi_spb_item.*', 
                                                'data_barang.nm_barang', 'data_barang.kode', 
                                               'ref_satuan.nm_satuan')
                    ->get();
            }else{
                $items = data_mutasi_spb_item::join('data_barang','data_barang.id_barang', '=', 'data_mutasi_spb_item.id_item')
                                        ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_mutasi_spb_item.id_satuan')
                                        ->where('data_mutasi_spb_item.id_mutasi_spb', $req->id)
                                        ->where('data_mutasi_spb_item.status',1)
                                        ->select('data_mutasi_spb_item.*', 'data_barang.nm_barang', 
                                            'data_barang.kode', 'ref_satuan.nm_satuan')
                                        ->get(); 
            }

            

            if($spb->id_acc > 0){
                $me = data_karyawan::find($spb->id_acc);
                $out .= '<div class="grid simple">
                            <div class="grid-title no-border"></div>
                            <div class="grid-body no-border">
                                <b>Disetujui Oleh : </b> ' . $me->nm_depan . ' ' . $me->nm_belakang . '<br />
                                <small class="text-muted">' . \Format::hari($spb->tgl_approval) . ', ' . \Format::indoDate2($spb->tgl_approval) . ' ' . \Format::jam($spb->tgl_approval) . '</small>
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
                                    
                                    <th class="text-right" title="qty yang di minta">Qty</th>
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

            $btn = \Auth::user()->permission > 1 && $spb->status < 2 && empty($spb->id_acc) ? '<button data-loading-text="<i class=\'fa fa-circle-o-notch fa-spin\'></i> Proses..." class="btn btn-primary btn-accspb" onclick="acc(' . $req->id . ');"><i class="fa fa-check"></i> Setujui</button>' : '';

            $result['kode']     = $spb->no_mutasi_spb;
            $result['content']  = $out;
            $result['button']   = $btn;

            return json_encode($result);

        }
    }
   public function postAccmutasi(Request $req){
    if($req->ajax()){
        $spb = data_mutasi_spb::find($req->id);
        $spb->update([
            'id_acc' => \Me::data()->id_karyawan,
            'tgl_approval' => date('Y-m-d H:i:s')
        ]);

        \Loguser::create('Melakukan Verifikasi terhadap Permohonan Mutasi Barang dengan No. ' . $spb->no_mutasi_spb);
    }
    }

    public function getAllpermohonan(Request $req){
        if($req->ajax()){
            $result = [];
            $items = data_mutasi_spb::pemohon($req->kode,$req->status, $req->gtujuan, $req->all())->paginate($req->limit);
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

                    $delete = $item->status < 2 && \Auth::user()->permission > 1 && empty($item->id_acc) ? '
                        | <a href="' . url('/Mutasi/editmutasi/' . $item->id_mutasi_spb) . '">Edit</a> |
                        <a href="javascript:void(0);" onclick="delmutasispb(' . $item->id_mutasi_spb . ');" class="text-danger">Hapus</a>' : '';

                    $skbbtn =  in_array($item->status, [2,3]) ? '| <a href="#" data-toggle="modal" data-target="#detailSKB" onclick="listviewskb(' . $item->id_mutasi_spb . ');">Lihat SMBU</a>' : '';

                    $tanda = empty($item->id_acc) ? '<i class="fa fa-times text-muted pull-right" title="Belum terverifikasi"></i>' : '<i title="Terverifikasi" class="fa fa-check-circle text-success pull-right"></i>';

                    if($item->tgl_approval !=0){
                        $tgl_approval = '
                            <div>' . \Format::indoDate2($item->tgl_approval) . '</div>
                            <div class="text-muted"><small>' . \Format::hari($item->tgl_approval) . ', ' . \Format::jam($item->tgl_approval) . '</small></div>
                        ';
                    }else{
                        $tgl_approval = '<center>-</center>';
                    }

                    $out .= '
                        <tr class="mutasi_' . $item->id_mutasi_spb . '">
                            <td>' . $no . '</td>
                            <td>
                                <div> ' . $item->no_mutasi_spb . ' ' . $tanda . '</div>
                                <div class="link text-muted">
                                    <small>
                                        [
                                            <a href="#" onclick="detailspbmutasi(' . $item->id_mutasi_spb . ');" data-toggle="modal" data-target="#detail">Lihat</a>
                                            ' . $skbbtn . $delete . '
                                        ]
                                    </small>
                                </div>
                            </td>

                            <td>
                                <div>' . $item->nm_depan . ' ' . $item->nm_belakang . '</div>
                                <div class="text-muted"><small>Dept : ' . $item->nm_departemen . '</small></div>
                            </td>

                            <td>
                                <div>' . $item->gudang_termohon . '</div>
                            </td>
                            <td>
                                <div>' . \Format::indoDate2($item->created_at) . '</div>
                                <div class="text-muted"><small>' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small></div>
                            </td>
                            <td>'.$tgl_approval.'</td>
                            <td class="text-center">' . $status[$item->status] . '</td>
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
            return redirect('/Mutasi');
        }

    }
    public function postDelmutasispb(Request $req){
        if($req->ajax()){
            data_mutasi_spb::find($req->id)->update([
                'status' => 4
            ]);

            return json_encode([
                'result' => true
            ]);
        }
    }
   public function postDelmutasiitem(Request $req){
    if($req->ajax()){
        data_mutasi_spb_item::find($req->id)->delete();

        return json_encode([
            'result' => true
        ]);
    }
    }
    public function getLoadbarang(Request $req){
    	if($req->ajax()){
    		$res = [];
    		$out = '';
                // dd($req->all());
    		$barang=data_item_gudang::byunit($req->all())->paginate(5);
            // dd($req->all());
            $total=$barang->total();
            if ($req->gudang > 0){
            		if($total > 0):
            			foreach ($barang as $item) {
                             $akhir =$item->in - $item->out ;
                             $btn_aktif=($akhir >0) ? '': 'disabled';
            				$out .='
            					<tr class="barang-'.$item->id_barang.'">
            						<td>'.$item->kode.'<small class="pull-right hide item-loading-'.$item->id_barang.'">Memuat...</small></td>
            						<td>'.$item->nm_barang.'</td>
                                    <td>'.$akhir.'&nbsp;'.$item->nm_satuan.'</td>
            						<td class="text-right"><button class="btn-sm btn-danger btn-item-' . $item->id_barang . '" onclick="add_item(' . $item->id_barang . ');"  '.$btn_aktif.'><i class="fa fa-plus"></i></button></td>
            					</tr>
            				';
            				# code...
            			}
            			else:
            				$out .='
            					<tr>
            						<td colspan="3">Tidak ditemukan </td>
            					</tr>
            				';
            			endif;
            }else{
                $out .='
                <tr>
                    <td colspan="3">Silahkan menentukan Gudang tujuan untuk membuat Permohonan Mutasi.</td>
                </tr>
                ';
            }
				$res['total']   = $total;
				$res['content'] = $out;
				$res['pagin']   = $barang->render();
				return json_encode($res);
    	}
    }

  	 public function getAdditem(Request $req){
	    $me = \Me::subgudang();

	    if($req->ajax()){

	        $res = [];
	        $out='';
	            $item= data_barang::join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
	                ->where('data_barang.id_barang', $req->id)
	                    ->select(
	                      'data_barang.*',
	                      'ref_satuan.nm_satuan')->first();
	            $res['item'] = $item;
	             $res['content'] = $out;
	            // dd($res);
	            return json_encode($res);
	            }
    }
     public function getLoaditem(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';

          $barang=data_item_gudang::byunit($req->all())->paginate(5);;
            $total=$barang->total();
              if ($req->gudang > 0){

                if($total > 0):
                    foreach ($barang as $item) {
                        $akhir =$item->in - $item->out ;
                        $btn_aktif = ($akhir>0) ? '' : 'disabled';
                        $out .='
                            <tr class="item-'.$item->id_barang.'">
                                <td>'.$item->kode.'<small class="pull-right hide barang-loading-'.$item->id_barang.'">Memuat...</small></td>
                                <td>'.$item->nm_barang.'</td>
                                <td>'.$akhir.' &nbsp; '.$item->nm_satuan.'</td>
                                <td class="text-right"><button class="btn-sm btn-danger btn-barang-' . $item->id_barang . '" onclick="add_itembarang(' . $item->id_barang . ');"  '.$btn_aktif.'><i class="fa fa-plus"></i></button></td>
                            </tr>
                        ';
                        # code...
                    }
                    else:
                        $out .='
                            <tr>
                                <td colspan="3">Tidak ditemukan </td>
                            </tr>
                        ';
                    endif;
             }else{
            $out .='
            <tr>
                <td colspan="3">Silahkan menentukan Gudang tujuan untuk membuat Permohonan Mutasi.</td>
            </tr>
            ';
            }
                $res['total']   = $total;
                $res['content'] = $out;
                $res['pagin']   = $barang->render();
                return json_encode($res);
        }
    }
    public function getAdditembarang(Request $req){
        $me = \Me::subgudang();

        if($req->ajax()){

            $res = [];
            $out='';
                $item= data_barang::join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
                    ->where('data_barang.id_barang', $req->id)
                        ->select(
                          'data_barang.*',
                          'ref_satuan.nm_satuan')->first();
                $res['item'] = $item;
                 $res['content'] = $out;
                // dd($res);
                return json_encode($res);
                }
    }
  
     public function postNoverif(Request $req){
        if($req->ajax()){
            $total = data_mutasi_spb::pemohon('',1, '', $req->all())->count();

            return json_encode([
                'total' => $total
            ]);
        }
    }
    public function postFinishsmb(Request $req)
    {
         $gudang= \Me::subgudang()->id_gudang;
         if($req->ajax()){
            $total_finish = data_mutasi_spb::where('data_mutasi_spb.id_unit_asal',$gudang)->whereIn('data_mutasi_spb.status',[5])->count();
            //dd($total_finish);
            return json_encode([
                'total_finish' => $total_finish
            ]);
        }
    }
      public function postProses(Request $req)
    {
         $gudang= \Me::subgudang()->id_gudang;
         if($req->ajax()){
            $total_proses = data_mutasi_spb::where('data_mutasi_spb.id_unit_asal',$gudang)->whereNotIn('data_mutasi_spb.status',[5])->count();
            //dd($total_finish);
            return json_encode([
                'total_proses' => $total_proses
            ]);
        }
    }

}

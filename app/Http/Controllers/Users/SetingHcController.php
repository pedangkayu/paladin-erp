<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;

use App\Models\data_transfer;
use App\Models\ref_gudang;
use App\Models\ref_layanan;

use App\jobs\Users\Setinghc\SetingHcJob;
use App\Jobs\Users\Setinghc\UpdateAksesJob;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SetingHcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        // 
        $users=data_transfer::leftjoin('ref_gudang', 'ref_gudang.id_gudang', '=', 'data_transfer.id_gudang_item')
                            ->leftjoin('ref_gudang as gudang_jasa', 'gudang_jasa.id_gudang','=', 'data_transfer.id_gudang_jasa')
                            ->leftjoin('ref_layanan', 'ref_layanan.id_layanan', '=', 'data_transfer.id_layanan')
            ->select('data_transfer.*', 'ref_gudang.nm_gudang', 'ref_layanan.nm_layanan','gudang_jasa.nm_gudang as jasa')->paginate(10);
            $tabel_antrian=[
                1 =>'ANTRIAN',
                2 =>'PERIKSA PENUNJANG MEDIS',
                3 =>'PELAYANAN RAWAT INAP',
                4 =>'JADWAL OPERASI',
                5 =>'ANTRIAN NON POLI',
                ];
        return view('Users.Setinghc.index', [
            'users'     => $users,
            'gudangs'   => ref_gudang::all(),
            'tabel_antrian'=>$tabel_antrian,
        ]);
    }
    public function getBaru(){

        return view('Users.Setinghc.baru',[
            'gudang' =>ref_gudang::all(),
            'layanan'  =>ref_layanan::all(),
            ]);
    }
    public function postBaru(Request $req){
         $arr = $this->dispatch(new SetingHcJob($req->all()));
            if($arr['res'])
                return redirect('/Setinghc')->withNotif([
                        'label' => $arr['label'],
                        'err' => $arr['err']
                    ]);
            else
                return redirect()->back()->withNotif([
                        'label' => $arr['label'],
                        'err' => $arr['err']
                    ]);
            }
    public function getUpdate($id){
        $data=data_transfer::where('id_transfer',$id);
        if($data->count()==0)
             return redirect('/Setinghc');
        $data=data_transfer::where('id_transfer',$id)->first();
// dd($data);
        return view('Users.Setinghc.edit',[
            'data' =>$data,
            'gudang' =>ref_gudang::all(),
            'layanan'  =>ref_layanan::all(),]);
    }
      public function postUpdate(Request $req){
         $arr = $this->dispatch(new UpdateAksesJob($req->all()));
            if($arr['res'])
                return redirect('/Setinghc')->withNotif([
                        'label' => $arr['label'],
                        'err' => $arr['err']
                    ]);
            else
                return redirect()->back()->withNotif([
                        'label' => $arr['label'],
                        'err' => $arr['err']
                    ]);
            }
    public function getAddblankform(Request $req){
        $gudang=ref_gudang::all();
        $layanan  =ref_layanan::all();
        $out ='';
        $out .='
                <tr class="baris_form">
                        <td>
                            <select class="select2" style="width:100%;" name="id_layanan[]" required>
                                <option value="">Silahkan Pilih</option>';
                                foreach($layanan as $data):
                        $out .='<option value="'. $data->id_layanan.'">'. $data->nm_layanan .'</option>';
                                endforeach;
                           $out .='      
                            </select>
                        </td>
                         <td><input type="text" name="no_antrian[]"  requered class="form-control" value="" ></td>
                        <td>
                            <select style="width:100%;" name="id_gudang[]">
                                <option value="">Silahkan Pilih</option>';
                                foreach($gudang as $unit):
                            $out .='<option value="'.$unit->id_gudang .'">'.$unit->nm_gudang.'</option>';
                                endforeach;
                             $out .='  
                            </select>
                        </td>
                        <td>
                            <select style="width:100%;" name="tabel_antrian[]" required>
                                    <option value="">Silahkan Pilih</option>
                                    <option value="1">ANTRIAN</option>
                                    <option value="2">PERIKSA PENUNJANG MEDIS</option>
                                    <option value="3">PELAYANAN RAWAT INAP</option>
                                    <option value="4">JADWAL OPERASI</option>
                                    <option value="5">ANTRIAN NON POLI</option>

                                </select>
                            </td>
                        <td>
                            <select style="width:100%;" name="id_layanan_sim[]" required>
                                <option value="">Silahkan Pilih</option>';
                                foreach($gudang as $unit):
                            $out .='<option value="'.$unit->id_gudang .'">'.$unit->nm_gudang.'</option>';
                                endforeach;
                             $out .='  
                            </select>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-hapus"><i class="fa fa-trash"></i></button></td>
                      </tr>
                        ';
             $res['content'] = $out;
            return json_encode($res);
  }
  public function getAlluser(Request $req){
        //dd($req->all());
        if($req->ajax()){
            $result = [];
            $items = data_transfer::user($req->id_gudang_jasa, $req->id_gudang_item,$req->all())->paginate($req->limit);
            $out = '';
            $total = $items->total();
            $tabel_antrian=[
                1 =>'ANTRIAN',
                2 =>'PERIKSA PENUNJANG MEDIS',
                3 =>'PELAYANAN RAWAT INAP',
                4 =>'JADWAL OPERASI',
                5 =>'ANTRIAN NON POLI',
                ];
            if($total > 0):
                $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
                foreach($items as $item){
                      
                $edit = ' <a href="'.url('/Setinghc/update/'. $item->id_transfer).'" >Edit</a>';
                    $out .= '
                        <tr class="user-' . $item->id_transfer . '">
                            <td>' . $no . '</td>
                            <td width="20%">
                                <div> ' .  $item->nm_layanan. '</div>
                                <div class="link text-muted">
                                    <small>
                                        [
                                        
                                        |'.$edit.'
                                        ]
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>' . $item->no_antrian. '</div>
                              
                            </td>
                            <td>'.$item->nm_gudang.' </td>
                            <td>'.$tabel_antrian[$item->tabel_antrian].'</td>
                            <td>'.$item->jasa.'</td>
                         
                        
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
            return redirect('/treatment');
        }

    }

    
}

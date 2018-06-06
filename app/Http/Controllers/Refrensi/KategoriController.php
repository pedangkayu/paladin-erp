<?php

namespace App\Http\Controllers\Refrensi;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ref_kategori;
use App\Models\ref_coa;
use App\Models\ref_coa_ledger;


class KategoriController extends Controller
{
        public function __construct(){
    $this->middleware('auth');
  }

    /**
    * Ref Klasifikasi Satuan
    * @access protected
    * @author yoga@valdlabs.com
    */

    public function getIndex(){
      $items = ref_kategori::join('ref_coa','ref_coa.id_coa', '=', 'ref_kategori.id_coa')
        ->join('ref_coa AS b','b.id_coa', '=', 'ref_kategori.coa_pembelian')
                             
                              ->select(
                                'ref_coa.nm_coa',
                                'ref_coa.kode',
                                'b.nm_coa as nm_coa_hpp',
                                'b.kode as kode_coa_hpp',
                                'ref_kategori.*')
                              ->paginate(10);
        $coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_coa = \Format::select_coa($coas);
          $res['select_coa'] = $select_coa;
      return view('Pengadaan.Setting.Kategori.index',[
         'select_coa' => $select_coa,
        'items' => $items
        ]);
    }

    public function getCreate(){
      
      return view('Pengadaan.Setting.Kategori.create');
    }

    public function postCreate(Request $req){
      // dd($req->all());
      ref_kategori::firstOrCreate(array(
        'nm_kategori' => $req->nama,
        'alias' => $req->alias,
        'id_coa' =>$req->id_coa,
        'coa_pembelian' =>$req->coa_pembelian,
        ));

      return redirect()->back()->withNotif([
        'label' => 'success',
        'err' => 'Berhasil terupdate di Database'
        ]);
    }

    public function getUpdate($id){
     
      $data = ref_kategori::find($id);

       $coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_coa = \Format::select_coa($coas,0, $data->id_coa);
          $res['select_coa'] = $select_coa;
      $coa_pembelian = \Format::select_coa($coas, 0, $data->coa_pembelian);
          $res['coa_pembelian'] = $coa_pembelian;

      return view('Pengadaan.Setting.Kategori.update',[
        'data' => $data,
        'select_coa' => $select_coa,
        'coa_pembelian' =>$coa_pembelian
        ]);

    }

    public function postUpdate(Request $req){
      
       if(count($req->id_coa) == 0 )
            return redirect()->back()->withNotif([
                'label' => 'danger',
                'err' => '<center>OOps!, Anda Belum Memilih Kode Coa </center>'
            ]);
      ref_kategori::where('id_kategori',$req->id)
      ->update([
        'nm_kategori' => $req->nama,
        'alias'       => $req->alias,
        'id_coa'      =>$req->id_coa,
        'coa_pembelian' =>$req->coa_pembelian,
        ]);

      return redirect()->back()->withNotif([
        'label' => 'success',
        'err' => 'Berhasil terupdate di Database'
        ]);
    }

    public function postDestroy(Request $req){

      ref_kategori::find($req->id)->delete();

      return json_encode([
        'result' => true
        ]);
    }
}

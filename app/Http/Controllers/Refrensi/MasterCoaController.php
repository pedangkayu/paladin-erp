<?php

namespace App\Http\Controllers\Refrensi;

use Illuminate\Http\Request;

use App\Jobs\RefCoa\CreateCoaJob;
use App\Jobs\RefCoa\CreateCoaLedgerJob;
use App\Jobs\RefCoa\UpdateCoaJob;
use App\Jobs\RefCoa\UpdateCoaLedgerJob;
use App\Jobs\RefCoa\SavePositionCoaJob;


use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\ref_coa;
use App\Models\data_jurnal;
use App\Models\data_log_coa;
use App\Models\ref_coa_ledger;
use App\Models\ref_service_kode;

class MasterCoaController extends Controller
{

	public function getIndex(){
		
		$items = ref_coa::orderby('kode','asc')->get();
		
		 $grup = [
             1=> 'Group',
             2=> 'Ledger',
        ];

        $normal = [
        	1 => 'DR',
        	2 => 'CR'
        ];

		return view('Akutansi.RefCoa.index',[
			'items' => $items,
			'grup' =>$grup,
			'normal' => $normal
			]);

	}

	public function getLedger(){
		$parent = ref_coa::whereNotIn('ref_coa.parent_id',[0])->get();

		$coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_coa_group = \Format::select_coa_group($coas);
          $res['select_coa_group'] = $select_coa_group;

		return view('Akutansi.RefCoa.ledger',[
			'parent' => $parent,
			'select_coa_group' => $select_coa_group,
			]);
	}

	public function getAdd(){

		$menu = ref_coa::all();
		$coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_coa = \Format::select_coa($coas);
          $res['select_coa'] = $select_coa;

		$type = array(
			1 => "Debit",
			2 => "Kredit "
			);

		return view('Akutansi.RefCoa.show', [
			'parent' => $menu,
			'type' => $type,
			'select_coa' => $select_coa,
			]);

	}

	public function postAdd(Request $req){
		$this->dispatch(new CreateCoaJob($req->all()));
		return redirect()->back()->withNotif([
			'label' => 'success',
			'err' => 'COA Berhasil dibuat'
			]);
	}

	public function postLedger(Request $req){
		$this->dispatch(new CreateCoaLedgerJob($req->all()));
		return redirect()->back()->withNotif([
			'label' => 'success',
			'err' => 'Coa Ledger Berhasil dibuat'
			]);
	}

	public function getEditgrup($id)
	{
		$data = ref_coa::find($id);
		$menu = ref_coa::all();
			$coas = [];
          foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
            $coas[$coa->parent_id][] = $coa;
          }
        $select_id = \Format::select_coa($coas, 0, $data->parent_id);

		return view('Akutansi.RefCoa.editgrup',[
			'parent' => $menu,
			'data' => $data,
			'select_id' => $select_id
			]);
	}

	public function getEditleadger($id) {

		$data1 = ref_coa::find($id);
		$data= ref_coa::all();
		
      $coas = [];
	    foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
	      $coas[$coa->parent_id][] = $coa;
	    }
	  
	    $coa_parent = \Format::select_coa($coas, 0, $data1->parent_id);

	    $logs = data_log_coa::where('id_coa', $id)->orderby('id', 'desc')->get();

		return view('Akutansi.RefCoa.editledger',[
			'logs' => $logs,
			// 'parent' => $menu,
			'data' => $data1,
			'coa_parent' => $coa_parent
			]);
	}

	public function postEditledger(Request $req){
		$this->dispatch(new UpdateCoaLedgerJob($req->all()));

		return redirect()->back()->withNotif([
			'label' => 'success',
			'err' => 'COA Ledger berhasil diperbaharui'
			]);
	}

	public function postUpdate(Request $req){
		$this->dispatch(
			new UpdateCoaJob($req->all())
			);

		return redirect()->back()->withNotif([
			'label' => 'success',
			'err' => 'COA berhasil diperbaharui'
			]);
	}

	public function postDestroy(Request $req){
		ref_coa::find($req->id)->delete();

		return json_encode([
			'result' => true
			]);
	}

	public function postDestroyledger(Request $req){
		ref_coa_ledger::find($req->id)->delete();

		return json_encode([
			'result' => true
			]);
	}

	
	public function getAllitems(Request $req){
		if($req->ajax()):
			$res = [];

		$items = ref_coa::paginate(10);   

		$out = '';
		if($items->total() > 0){
			$no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
			foreach($items as $item){

				$out .= '
				<tr class="item_' .  $item->id . ' items">
					<td>' . $no . '</td>
					<td>'. $item->kode .'</td>
					<td>'. $item->nm_coa .'</td>
					<td>
						<div>
							' . \Format::indoDate($item->created_at) . '
						</div>
						<small class="text-muted">' . \Format::hari($item->created_at) . ', ' . \Format::jam($item->created_at) . '</small>
					</td>
				</tr>
				';
				$no++;
			}
		}else{
			$out = '
			<tr>
				<td colspan="4">Tidak ditemukan</td>
			</tr>
			';
		}

		$res['data'] = $out;
		$res['pagin'] = $items->render();

		return json_encode($res);
		endif;
	}
	

	public function getAkun($id_coa){

		if(empty($id_coa))
			return redirect('/coa');

		$data['jurnal'] = data_jurnal::logakun($id_coa)->paginate(50);
		$data['akun'] = ref_coa::find($id_coa);
		return view('Akutansi.Akun.Index', $data);
	}

	public function getLogakun(Request $req){
		if($req->ajax()){

			$res = [];
			$out = '';
			$items = data_jurnal::logakun($req->id_coa, $req->all())->paginate($req->limit);
			
			if(count($items) > 0) :
				foreach($items as $item){

					$masuk = number_format($item->debit,0,',','.');
					$keluar = number_format($item->kredit,0,',','.');

					$out .= '
						<tr>
							<td>' . date('d/m/Y', strtotime($item->tanggal)) . '</td>
							<td>' . $item->deskripsi . '</td>
							<td class"text-right">' . $masuk . '</td>
							<td class"text-right">' . $keluar . '</td>
						</tr>
					';
				}
			else:
				$out .= '<tr>
					<td colspan="5">Tidak ditemukan transaksi</td>
				<tr>';
			endif;

			$res['content'] = $out;
			$res['pagin'] = $items->render();

			return response()->Json($res);

		}
	}
	public function getPrintcoa(Request $req){
		$items = ref_coa::orderby('kode','asc')->get();
		
		 $grup = [
             1=> 'Group',
             2=> 'Ledger',
        ];

        $normal = [
        	1 => 'DR',
        	2 => 'CR'
        ];
		return view('Akutansi.RefCoa.Print.coa',[
			'items' => $items,
			'req' 	=> $req,
			'grup'	=>$grup,
			'normal'=>$normal
			]);
		}

}

<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\data_spbm;
use App\Models\data_batch;
use App\Models\data_barang;
use App\Models\ref_kategori;
use App\Models\data_log_batch;
use App\Models\data_spbm_item;

use App\Jobs\Pembelian\Batch\CreateJob;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BatchController extends Controller {

	public function getIndex(){
		$items = data_barang::masterbatch()->paginate(10);
		return view('Pembelian.Batch.Index', [
			'kategoris' => ref_kategori::all(),
			'items' => $items
		]);
	}

	public function getMasterbatch(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';

			$items = data_barang::masterbatch($req->all())->paginate($req->limit);
			$total = $items->total();

			if($total > 0):
				$no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
				foreach($items as $item){
					$i = 1;

					if($req->titipan == 'true')
						$sitem = $item->batchs()->where('status', 1)->where('titipan', '>', 0)->orderby('tgl_expired', 'asc')->get();
					else
						$sitem = $item->batchs()->where('status', 1)->orderby('tgl_expired', 'asc')->get();

					foreach($sitem as $batch){
						
						if($i == 1){
							$out .= '
								<tr>
									<td>' . $no . '</td>
									<td>' . $item->kode . '</td>
									<td>' . $item->nm_barang . '</td>
							';	
						}else{
							$out .= '
								<tr>
									<td></td>
									<td></td>
									<td></td>
							';	
						}
						

						$out .= '
								<td>
									<a href="#" onclick="detail(' . $batch->id_batch . ');" data-toggle="modal" data-target="#myModal">' . $batch->nomor_batch . '</a>
								</td>
								<td class="text-right">' . $batch->total_qty . '</td>
								<td class="text-right">' . ($batch->in - $item->out) . '</td>
								<td>' . \Format::indoDate2($batch->tgl_expired) . '</td>
							</tr>
						';

						$i++;
					}
					$no++;
				}
			else:
				$out .= '<tr><td colspan="7">Tidak ditemukan</td></tr>';
			endif;

			$res['content'] = $out;
			$res['pagin'] = $items->render();
			$res['total'] = $total;

			return json_encode($res);
		}
	}
    
    public function getGr(){
    	$items = data_spbm::frombatch()->paginate(10);
    	return view('Pembelian.Batch.Gr', [
    		'items' => $items
    	]);
    }

    public function getGrajax(Request $req){
    	if($req->ajax()){
    		$res = [];
			$out = '';
			$items = data_spbm::frombatch($req->all())->paginate($req->limit);
			
			if($items->total() > 0):
				$no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
				foreach($items as $item){
					$out .= '
						<tr>
							<td>' . $no . '</td>
							<td>
								<a href="' . url('/gr/detail/' . $item->id_spbm) . '">' . $item->no_spbm . '</a>
								<div class="text-muted">
									<small>PO No. ' . $item->no_po . '</small>
								</div>
							</td>
							<td>
								' . \Format::indoDate($item->tgl_terima_barang) . '
								<div class="text-muted">
									<small>Periksa ' . \Format::indoDate($item->tgl_periksa_barang) . '</small>
								</div>
							</td>
							<td>
								' . $item->nm_vendor . '
								<div class="text-muted">
									<small>oleh ' . $item->nm_pengirim . '</small>
								</div>
							</td>
							<td class="text-center">' . $item->total_batch . '</td>
							<td>
								<a href="' . url('/batch/create/' . $item->id_spbm) . '" class="btn btn-primary">Proses</a>
							</td>
						</tr>
					';
					$no++;
				}
			else:
				$out = '
					<tr>
						<td colspan="6">Tidak ditemukan</td>
					</tr>
				';
			endif;
			$res['content'] = $out;
			$res['pagin'] = $items->render();

			return json_encode($res);

    	}
    }

    public function getCreate($id = 0){

    	if(empty($id) || !is_numeric($id))
			return redirect('/batch')->withNotif([
				'label' => 'danger',
				'err' => 'Maaf, Data Good Receive tidak ditemukan !'
			]);

		$gr = data_spbm::byid($id)->first();
		
		if($gr == null)
			return redirect('/batch')->withNotif([
				'label' => 'danger',
				'err' => 'Maaf, Data Good Receive tidak ditemukan !'
			]);
		$items = data_spbm_item::bybatch($id)->get();
		
		$kirim = [
			1 => 'Dikirim Oleh Supplier',
			2 => 'Dikirim Oleh Ekspedisi',
			3 => 'Diambil Oleh Onkologi'
		];

    	return view('Pembelian.Batch.Create', [
    		'gr' => $gr,
			'items' => $items,
			'kirim' => $kirim
    	]);
    }

    public function postCreate(Request $req){
    	if($req->ajax()){
    		$err = $this->dispatch(new CreateJob($req->all()));
    		return json_encode($err);
    	}
    }

    public function getGetbatch(Request $req){
    	if($req->ajax()){
    		$res = [];
    		$out = '';
    		$items = data_batch::byidspbmitem($req->id_spbm_item)->get();
    		$total = count($items);
    		$sum = 0;
    		if($total > 0):

	    		foreach($items as $item){
	    			$out .= '
	    				<tr class="batch-' . $item->id_batch . '">
	    					<td>
	    						<!-- <input type="text" name="list_batch" class="form-control" value="' . $item->nomor_batch . '" /> -->
	    						' . $item->nomor_batch . '
	    						<div>
		    						<small>
		    							<a href="javascript:void();" onclick="hapus(' . $item->id_batch . ');">[Hapus]</a>	
		    						</small>
	    						</div>
	    					</td>
	    					<td>
	    						<!-- <input type="text" name="list_tanggal" class="form-control" value="' . $item->tgl_expired . '" readonly="readonly" /> -->
	    						' . \Format::indoDate2($item->tgl_expired) . '
	    					</td>
	    					<td class="text-right">
	    					<input type="number" onchange="update_qty(this.value, ' . $item->id_batch . ');" value="' . ($item->in - $item->out) . '" name="list_total" class="form-control text-right" />
	    					</td>
	    				</tr>
	    			';
	    			$sum += ($item->in - $item->out);
	    		}
	    	else:

	    		$out = '
	    			<tr>
						<td colspan="3">Tidak ditemuakn</td>
					</tr>
	    		';

	    	endif;

	    	$spbm = data_spbm_item::find($req->id_spbm_item);
	    	$sisa = $spbm->qty - $sum;

	    	$res['content'] = $out;
	    	$res['total'] = $total;
	    	$res['sisa'] = $sisa;

	    	return json_encode($res);


    	}
    }

    public function getDetail(Request $req){
    	if($req->ajax()){
    		$res = [];
    		$batch = data_batch::show($req->id_batch)->first();
    		$res = [
    			'nm_barang' => $batch->nm_barang,
    			'kode' => $batch->kode,
    			'oleh' => 'Oleh : ' . $batch->nm_depan . ' ' . $batch->nm_belakang,
    			'nomor_batch' => $batch->nomor_batch,
    			'total_qty' => $batch->total_qty,
    			'qty_item' => $batch->qty_item,
    			'sisa' => ($batch->in - $batch->out),
    			'tgl_expired' => \Format::indoDate($batch->tgl_expired),
    			'tgl_terima_barang' => \Format::indoDate($batch->tgl_terima_barang),
    			'no_spbm' => $batch->no_spbm,
    			'no_po' => $batch->no_po,
    			'nm_pengirim' => $batch->nm_pengirim,
    			'titipan' => $batch->titipan > 0 ? '<i class="fa fa-check"></i>' : '-',
    		];

    		return json_encode($res);
    	}
    }


    public function postUpdateqty(Request $req){
    	if($req->ajax()){

    		$me = \Me::data();
    		$name = $me->nm_depan . ' ' . $me->nm_belakang;

    		$batch = data_batch::find($req->id);
    		$sisa = $batch->in - $batch->out;
    		if($sisa > $req->qty){
    			$batch->out = $batch->out + ($sisa - $req->qty);

    			data_log_batch::create([
    				'id_batch' => $req->id,
    				'id_barang' => $batch->id_barang,
    				'qty_out' => ($sisa - $req->qty),
    				'qty_in' => 0,
    				'id_gudang' => 0,
    				'tipe' => 0,
    				'id_parent' => 0,
    				'id_karyawan' => $me->id_karyawan,
    				'keterangan' => $name . ' mengurangi nilai batch.'
    			]);

    			\Loguser::create($name . ' mengurangi nilai batch No. ' . $batch->nomor_batch);
    		}else if($sisa < $req->qty){
    			$batch->in = $batch->in + ($req->qty - $sisa);
    			data_log_batch::create([
    				'id_batch' => $req->id,
    				'id_barang' => $batch->id_barang,
    				'qty_out' => 0,
    				'qty_in' => ($req->qty - $sisa),
    				'id_gudang' => 0,
    				'tipe' => 0,
    				'id_parent' => 0,
    				'id_karyawan' => $me->id_karyawan,
    				'keterangan' => $name . ' menambah nilai batch.'
    			]);
    			\Loguser::create($name . ' menambah nilai batch No. ' . $batch->nomor_batch);
    		}

    		$batch->save();

    		return json_encode([
    			'id' => $req->id
    		]);
    	}
    }

    public function postHapus(Request $req){
    	if($req->ajax()){
    		$batch = data_batch::find($req->id);
    		$batch->update([
    			'status' => 0
    		]);

    		\Loguser::create('Menghapus batch No. ' . $batch->nomor_batch);

    		return json_encode([
    			'id' => $req->id
    		]);
    	}
    }

}

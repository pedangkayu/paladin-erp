<?php

namespace App\Http\Controllers\Laporan\Pembelian;

use App\Models\data_po_item;
use App\Models\data_vendor;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel;
use PDF;
use DB;

class LaporanPOController extends Controller {
    
	public function getIndex(Request $req){
		$items = data_vendor::all();

		return  view('Laporan.Pembelian.PO.index', [
			'items' => $items,
			'req'	=> $req,
		]);
	}
	public function getLaporan(Request $req){
		if($req->ajax()){

			$res = [];
			$out = '';

			$items = data_po_item::laporan($req->all())->paginate($req->limit);

			$total = $items->total();

			if($total > 0):
				$no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
				foreach($items as $item){

					/////////////////// MATEMATIKA ////////////////////////

					$diskonitem	= ($item->harga * $item->diskon) / 100;
					$aftdiskon 	= $item->harga - $diskonitem;
					$ppnitem	= ($aftdiskon * $item->ppn) / 100;
					$pphitem	= ($aftdiskon * $item->pph) / 100;
					$totalitem 	= $aftdiskon + $ppnitem + $pphitem;

					$gdiskon 	= ($totalitem * $item->gdiskon) / 100;
					$gaftdisk	= $totalitem - $gdiskon;
					$gppn		= ($gaftdisk * $item->gppn) / 100;
					$gpph		= ($gaftdisk * $item->gpph) / 100;

					$grandtotal = ($gaftdisk + $gppn + $gpph) * $item->qty;

					$out .= '
						<tr>
							<td>' . $no . '</td>
							<td>
								' . $item->no_prq . '<br />
								<small class="text-muted">' . date('d/m/Y', strtotime($item->tanggal_prq)) . '</small>
							</td>
							<td>
								<a href="' . url('/po/print/' . $item->id_po) . '" target="_blank">' . $item->no_po . '</a><br />
								<small class="text-muted">' . date('d/m/Y', strtotime($item->tanggal_po)) . '</small>
							</td>
							<td>
								<a href="' . url('/gr/print/' . $item->id_spbm) . '" target="_blank">' . $item->no_spbm . '</a><br />
								<small class="text-muted">' . date('d/m/Y', strtotime($item->tanggal_gr)) . '</small>
							</td>
							<td>' . $item->nm_vendor . '</td>
							<td>
								' . $item->nm_barang . '<br />
								<small class="text-muted">' . $item->kode . '</small>
							</td>
							<td class="text-right">' . number_format($item->qty,0,',','.') . ' ' . $item->nm_satuan . '</td>
							<td class="text-right">' . number_format($item->harga,0,',','.') . '</td>
							<td class="text-right" style="background:#ddd;">' . number_format($item->gdiskon,0,',',',') . '</td>
							<td class="text-right" style="background:#ddd;">' . number_format($item->gppn,0,',',',') . '</td>
							<td class="text-right">' . number_format($item->diskon,0,',',',') . '</td>
							<td class="text-right">' . number_format($item->ppn,0,',',',') . '</td>
							<td class="text-right">' . number_format($grandtotal,0,',','.') . '</td>
							<td>' . date('d/m/Y', strtotime($item->deadline)) . '</td>
						</tr>
					';
					$no++;
				}
			else:
				$out = '
					<tr>
						<td colspan="14">Tidak ditemukan!</td>
					</tr>';
			endif;

			$res['total'] = $total;
			$res['content'] = $out;
			$res['pagin'] = $items->render();

			return json_encode($res);
		}	
	}

	public function getPrint(Request $req){
		$items = data_po_item::laporan($req->all())->get();
		return  view('Laporan.Pembelian.PO.Print', [
			'items' => $items,
			'req' => $req
		]);
	}
	public function getExcel(Request $req){

		   Excel::create('Laporan_PO'.date('d_F_Y'), function($excel) use($req) {

		   	$items = data_po_item::laporan($req->all())->get();
		   	  $r = array(
                'items' => $items,
                'req' => $req,
                );  
	
            $excel->sheet('New sheet', function($sheet)use($r){

             $sheet->loadView('Laporan.Pembelian.PO.excel',$r);

            });
        })->export('xlsx');
	}
}

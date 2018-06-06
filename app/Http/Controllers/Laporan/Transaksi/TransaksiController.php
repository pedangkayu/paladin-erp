<?php

namespace App\Http\Controllers\Laporan\Transaksi;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\data_spb;
use App\Models\data_skb;	
use App\Models\data_spbm;	
use App\Models\data_resep;
use App\Models\data_resep_item;
use App\Models\data_resep_campur;
use App\Models\data_treatment;
use App\Models\data_treatment_item;
use App\Models\ref_kamar;
use App\Models\data_rawat_inap;
use App\Models\data_mutasi_spb;
use App\Models\data_mutasi_skb;
use App\Models\ref_gudang;
use App\Models\ref_asuransi;
use App\Models\data_faktur;
use App\Models\data_jurnal_pembayaran;
use App\Models\ref_bank;
use App\Models\ref_payment_method;
use App\Models\data_jurnal;

class TransaksiController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
	}

	public function getSpb()
	{
		return view('Laporan.Transaksi.SPB');
	}

	public function getSpbajax(Request $req)
	{
		if($req->ajax()){
			$res = [];
			$out = '';
            //$items = data_po_item::lpbdo($req->all())->paginate($req->limit);
		//	$items = data_spb::rekapspb($req->all())->get();
			$items = data_spb::with(['rekap'=>function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_spb_item.id_item')
					  ->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekapspb($req->all())->get();
			$total = count($items);
			if($total > 0){

				$no = 1;
				$id_spb = '';
				foreach ($items as $item) {
					$i = 1;
					foreach($item->rekap as $data){
						if($i == 1){
							$out .= '<tr>
							<td>' . $no . '</td>
							<td class="text-left">' . $item->no_spb . '</td>
							<td class="text-left">' . $item->nm_depan . ' ' . $item->nm_belakang . '</td>
							<td class="text-left">' . $item->nm_departemen . '</td>
							<td class="text-left">' . \Format::indoDate2($item->deadline). '</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
						';
						}else{
							$out .= '
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							';
						}
						$out .= '

							<td class="text-left">' . $data->nm_barang . '</td>
							<td class="text-left">' . $data->qty . '</td>
							<td class="text-left">' . $data->nm_satuan . '</td>
						</tr>
						';
						$i++;
					}
					$no++;
				}

		}else{
			$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
		}

		$res['content'] = $out;
            // $res['pagin'] = $items->render();
            // $res['total'] = $total;

		return json_encode($res);
	}
}

public function getSkb()
{
	return view('Laporan.Transaksi.SKB');
}

public function getSkbajax(Request $req)
{
	if($req->ajax()){
		$res = [];
		$out = '';
            //$items = data_po_item::lpbdo($req->all())->paginate($req->limit);
		$items = data_skb::with(['rekap'=>function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_skb_item.id_item')
				->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekapskb($req->all())->get();
		$total = count($items);
		if($total > 0){

			$no = 1;
			$id_skb = '';
				foreach ($items as $item) {
					$i = 1;
					foreach($item->rekap as $data){

						if($i == 1){
							$out .= '<tr>
								<td>' . $no . '</td>
								<td class="text-left">' . $item->no_skb . '</td>
								<td class="text-left">' . $item->nm_depan . ' ' . $item->nm_belakang . '</td>
								<td class="text-left">' . $item->nm_departemen . '</td>
								<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
								';
						}else{
							$out .= '
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							';
						}

						$out .= '
							<td class="text-left">' . $data->nm_barang . '</td>
							<td class="text-right">' . $data->qty . '</td>
							<td class="text-right">' . $data->qty_lg . '</td>
							<td class="text-right">' . $data->sisa . '</td>
							<td class="text-left">' . $data->nm_satuan . '</td>
						</tr>
						';
						$i++;
					}
					$no++;
				}

		}else{
			$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
		}

		$res['content'] = $out;
            // $res['pagin'] = $items->render();
            // $res['total'] = $total;

		return json_encode($res);
	}
}

public function getGr()
{
	return view('Laporan.Transaksi.GR');
}

public function getGrajax(Request $req)
{
	if($req->ajax()){
		$res = [];
		$out = '';
            //$items = data_po_item::lpbdo($req->all())->paginate($req->limit);
		$items = data_spbm::with(['rekap'=>function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_spbm_item.id_barang')
					  ->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekapspbm($req->all())->get();
		$total = count($items);
		if($total > 0){

			$no = 1;
			$id_spbm = '';
				foreach ($items as $item) {
					$i = 1;
					foreach($item->rekap as $data){

						if($i == 1){
							$out .= '<tr>
								<td>' . $no . '</td>
								<td class="text-left">' . $item->no_spbm . '</td>
								<td class="text-left">' . $item->no_surat_jalan . '</td>
								<td>'. $item->nm_vendor .'</td>
								';
						}else{
							$out .= '
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							';
						}

						$out .= '
							<td class="text-left">' . $data->kode . '</td>
							<td class="text-left">' . $data->nm_barang . '</td>
							<td class="text-left">' . $data->merek . '</td>
							<td class="text-left">' . $data->qty_lg . '</td>
							<td class="text-left">' . $data->qty . '</td>
							<td class="text-left">' . $data->sisa . '</td>
							<td class="text-left">' . $data->bonus . '</td>
							<td class="text-left"></td>
							<td class="text-left">' . $data->satuan . '</td>
						</tr>
						';
						
						$i++;
					}
					$no++;
				}

		}else{
			$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
		}

		$res['content'] = $out;
            // $res['pagin'] = $items->render();
            // $res['total'] = $total;
		return json_encode($res);
	}
}

public function getRetur()
{
	return view('Laporan.Transaksi.retur');
}

public function postRetur(Request $req)
{
	if($req->ajax()){
		$res = [];
		$out = '';
            //$items = data_po_item::lpbdo($req->all())->paginate($req->limit);
		$items = data_po_item::rekapprodusesn($req->all())->get();
		$total = count($items);
		if($total > 0){

			$no = 1;
			foreach ($items as $item) {
				$out .= '
				<tr>
					<td>' . $no . '</td>
					<td>' . $item->nm_vendor . '</td>
					<td class="text-right">' . number_format($item->total,0,',','.') . '</td>
					<td class="text-right">' . number_format($item->harga,2,',','.') . '</td>
					<td class="text-right"></td>
					<td class="text-right"></td>
					<td class="text-right">' . number_format($item->total,0,',','.') . '</td>
					<td class="text-right">' . number_format($item->harga,2,',','.') . '</td>
				</tr>
				';
				$no++;
			}

		} else{
			$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
		}

		$res['content'] = $out;
            // $res['pagin'] = $items->render();
            // $res['total'] = $total;

		return json_encode($res);
	}
}

public function getPrintspb(Request $req)
{
	$items = data_spb::with(['rekap'=>function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_spb_item.id_item')
					  ->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekapspb($req->all())->get();

	return view('Print.Transaksi.SPB',[
		'items' => $items,
		'req' 	=> $req
		]);
}

public function getPrintskb(Request $req)
{
	$items = data_skb::with(['rekap'=>function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_skb_item.id_item')
				->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekapskb($req->all())->get();


	return view('Print.Transaksi.SKB',[
		'items' => $items,
		'req' => $req
		]);
}

public function getPrintgr(Request $req)
{
	$items = data_spbm::with(['rekap'=>function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_spbm_item.id_barang')
					  ->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekapspbm($req->all())->get();

	return view('Print.Transaksi.GR',[
		'items' => $items,
		'req' => $req
		]);
}
//Holil ini utk rekap transaksi Penjualan Obat di apoti atau rekap resep
public function getResep(){
	return view('Laporan.Transaksi.Resep');
}
public function getResepajax(Request $req)
	{
		if($req->ajax()){
			$res = [];
			$out = '';

			$items = data_resep::with(['obat'=>function($query){
				$query->join('data_barang', 'data_barang.id_barang', '=','data_resep_item.id_barang')
					->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
					->join('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan');
			}])->rekapresep($req->all())->get();

			$total = count($items);
			if($total > 0){

				$no = 1;
				$id_resep = '';
				foreach ($items as $item) {
					$i = 1;
					foreach($item->obat as $data){
					
						
						if($i == 1){
							$out .= '
							<tr>
							<td>' . $no . '</td>
							<td class="text-left">' . $item->nomor_resep . '</td>
							<td class="text-left">' .$item->id_pasien_hc. '</td>
							<td class="text-left">' . $item->nama_pasien . '</td>
							<td class="text-left">' . $item->nm_depan . ' ' . $item->nm_belakang . '</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
						';
						}else{
							$out .= '
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							';
						}
						$out .= '

							<td class="text-left">' . $data->nm_barang . '</td>
							<td class="text-left">' . $data->qty . ' &nbsp; ' . $data->nm_satuan . '</td>
							
						</tr>
						';
						$i++;
					}
					$no++;
				}

		}else{
			$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
		}

		$res['content'] = $out;
            // $res['pagin'] = $items->render();
            // $res['total'] = $total;

		return json_encode($res);
	}
}
public function getPrintresep(Request $req)
{
	$items = data_resep::with(['obat'=>function($query){
				$query->join('data_barang', 'data_barang.id_barang', '=','data_resep_item.id_barang')
					->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
					->join('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan');
			}])->rekapresep($req->all())->get();

	return view('Print.Transaksi.Resep',[
		'items' => $items,
		'req' 	=> $req
		]);
}
public function getRacikan(){
	return view('Laporan.Transaksi.Resepcampur');

}
public function getRacikajax(Request $req)
	{
		if($req->ajax()){
			$res = [];
			$out = '';
			$items = data_resep::with(['obat'=>function($query){
			}])->rekapresep($req->all())->get();
			$total = count($items);
			if($total > 0){

				$no = 1;
				$id_resep = '';
				foreach ($items as $item) {
					$i = 1;
					foreach($item->obat as $data){
						$c = 1;
						$ite = $data->campur()
								->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
								->join('data_item_gudang', 'data_item_gudang.id_item_gudang', '=' ,'data_resep_campur.id_item_gudang') //<-- untuk join² gudankan id_item_gudang. Perlu di ingat id_item_gudang tidak sama dengan id_barang
								->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_campur.id_satuan_campur')
								->join('data_resep_item','data_resep_item.id_resep_item', '=', 'data_resep_campur.id_resep_item')
								->join('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan')
								->select(
									'data_resep_campur.*',
									'data_barang.kode',
									'data_barang.nm_barang',
									'data_resep_item.keterangan',
									'data_resep_item.id_resep_aturan',
									'ref_resep_aturan.resep_aturan',
									'data_resep_item.nama_campur',
										'ref_satuan.nm_satuan'
									)
								->get();
					foreach ($ite as $campur) {
						# code...
						
						if($i == 1){
							$out .= '
							<tr>
							<td>' . $no . '</td>
							<td class="text-left">' . $item->nomor_resep . '</td>
							<td class="text-left">' .$item->id_pasien_hc. '</td>
							<td class="text-left">' . $item->nama_pasien . '</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
						';
						}else{
							$out .= '
								
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							';
						}
						if($c ==1){
							$out .='
							<td class="text-left">' . $data->nama_campur . '</td>
							';
						}else{
							$out .='
							<td></td>
								';
						}
						$out .='
							<td class="text-left">' . $campur->nm_barang . '</td>
							<td class="text-left">' . $campur->qty . ' &nbsp; ' . $campur->nm_satuan . '</td>
						</tr>
						';
						$c++;
						$i++;
					}//campur
				}
					$no++;
				}

		}else{
			$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
		}

		$res['content'] = $out;
		return json_encode($res);
	}
}
	public function getPrintracikan(Request $req)
	{
	$items = data_resep::with(['obat'=>function($query){
				}])->rekapresep($req->all())->get();
		return view('Print.Transaksi.Racik',[
			'items' => $items,
			'req' 	=> $req
			]);
	}
				// 3/4/2016 
	public function getApotik(){
		return view('Laporan.Transaksi.Apotik');
	}

	public function getApotikajax(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';

			$items=data_resep::rekapresep($req->all())->get();
			$total = count($items);
			if($total > 0){

				$no = 1;
				$id_resep = '';
				foreach ($items as $item) {			
			$patens=$item->obat()
					->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
					->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
					->get();
					$i = 1;
					foreach ($patens as $paten) {//paten
						if($paten->id_barang !=0){ //cek id_barang

						if($i==1){
						$out .='
							<tr>
								<td>'.$no.'</td>
								<td>'.$item->nomor_resep.'</td>
								<td>'.$item->nama_pasien.'</td>
								<td>'.$item->nm_depan.' '.$item->nm_belakang.'</td>
								<td>' . \Format::indoDate2($item->created_at). '</td>
							';
						}else{
							$out .='
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								';
						}
						$out .='
								<td>'.$paten->nm_barang.'</td>
								<td>-</td>
								<td>'.$paten->qty.'</td>
								<td>'.$paten->nm_satuan.'</td>
								</tr>
								';
					}//cek id_barang
					if($paten->id_barang ==0){
					$campurs=$paten->campur()
						->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
						->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_campur.id_satuan_campur')
						->get();
						$c=1; 
						foreach ($campurs as $campur) {
						if($c==1){
							$out .='
								<tr>
								<td></td>
								<td colspan="2">Nama Rscikan</td>
								<td colspan="3" class="semi-bold text-center">'.$paten->nama_campur.'</td>
								';
						}else{
							$out .='
							<td colspan="6"></td>
							';
						}
						$out .='
							<td>'.$campur->nm_barang.'</td>
							<td>'.$campur->qty.'</td>
							<td>'.$campur->nm_satuan.'</td>
							</tr>
							';
							$c++;
						}
					}
					$i++;
					}//paten
				$no++;
				}
			}else{
			$out = '
			<tr>
				<td colspan="8">Tidak Temukan</td>
			</tr>
			';
			}	
		$res['content'] = $out;
		return json_encode($res);
		}
	}
	public function getPrintapotik(Request $req){
		$items=data_resep::rekapresep($req->all())->get();
		return view ('Print.Transaksi.apotik',[
				'items'=> $items,
				'req' 	=> $req
			]);
	}

	//proses Dev belom seleseai
	public function getTreatment(){
		return view('Laporan.Transaksi.Treatment');
	}
	public function getTreatmentajax(Request $req){
		if($req->ajax()){
			$res= [];
			$out = '';

			$treatment=data_treatment::rekaptreatment($req->all())->get();
			$total = count($treatment);
		if($total > 0){
			$no=1;
			$id_treatment = '';
			foreach ($treatment as $item) {
				$jasa =$item->items()
					//->join('ref_service', 'ref_service.id_service', '=', 'data_treatment_item.id_service')
					->leftjoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'data_treatment_item.service_kode')
					//->join('data_treatment_dokter', 'data_treatment_dokter.id_treatment_item', '=', 'data_treatment_item.id_treatment_item')
					->get();
					$i =1;
					foreach ($jasa as $jasa) {
					if($i==1){
				$out .='
					<tr>
						<td>'.$no.'</td>
						<td>'.$item->nomor_treatment.'</td>
						<td>'.$item->nama_pasien.'</td>
						<td>'. \Format::indoDate2($item->created_at).'</td>
					';
				}else{
					$out .='
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					 ';
				}
					$out .='
						<td>'.$jasa->nm_service.'</td>
						<td>-</td>
						<td>-</td>
					</tr>
				';
			$bhp=$jasa->bhp()
					->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
					->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
					->get();
					$c=1;
					foreach ($bhp as $obt) {
						if($c==1){
							$out .='
						<tr>
							<td></td>
							<td></td>
							<td colspan="3"></td>
							';
						}else{
							$out .='
							<td colspan="4"></td>
						';
						}
						$out .='
							<td>'.$obt->nm_barang.'</td>
							<td>'.$obt->qty.' &nbsp;'.$obt->nm_satuan.'</td>
						</tr>
								';
					}
				$i++;
				}
				$no++;
			}
		}else{
			$out  ='
					<tr>
						<td colspan="7">Tidak di Temukan</td>
					</tr>
					';
		}
		$res['content'] = $out;
		return json_encode($res);
		}
	}
	public function getPrinttreatment(Request $req){
		$treatment=data_treatment::rekaptreatment($req->all())->get();
		return view ('Print.Transaksi.Treatment',[
				'treatment'=> $treatment,
				'req' 	=> $req
			]);
	}
	public function getRawatinap(){
		return view('Laporan.Transaksi.Rawatinap',[
			'kamar' => ref_kamar::all(),
			]);
	}
	public function getRinapajax(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';

			$rinap=data_rawat_inap::rekaprinap($req->all())->get();
			$total= count($rinap);
			if($total > 0){
				$no=1;
				$id_rinap = '';
				foreach ($rinap as $item) { 
					if($item->No_trans == 2):
						   $a='Lunas';
					elseif ($item->No_trans == 1): 
						 $a='Check-Out kamar';
					else:
						$a= 'Check-In';
					endif;
					if($item->selesai_rinap >0):
						$selesai=''. \Format::indoDate2($item->selesai_rinap).' '.\Format::hari($item->selesai_rinap).' '.\Format::jam($item->selesai_rinap).'';
					else:
					$selesai='';
					endif;
                
			$out .='
				<tr>
					<td>'.$no.'</td>
					<td>'.$item->id_antrian.'</td>
					<td>'.$item->nama_pasien.'</td>
					<td>'.$item->nm_kamar.'</td>
					<td>'. \Format::indoDate2($item->tgl_pakai).' '.\Format::hari($item->tgl_pakai).' '.\Format::jam($item->tgl_pakai).'</td>
					<td>
					'.$selesai.'</td>
					<td>'.$a.'</td>
				</tr>
				';
			$no++;
				}

			}

		}else{
		$out  ='
				<tr>
					<td colspan="7">Tidak di Temukan</td>
				</tr>
				';
	}
	$res['content'] = $out;
	return json_encode($res);
	}
	public function getPrintrinap(Request $req){
		$rinap=data_rawat_inap::rekaprinap($req->all())->get();
		 $No_trans = [
            0 => 'Check-In',
            1 => 'Check-Out kamar',
            2 => 'Lunas',
        ];
		return view ('Print.Transaksi.Rinap',[
				'rinap'    => $rinap,
				'req'      => $req,
				'No_trans' =>$No_trans
			]);
	}
	public function getPmbu(){
		return view('Laporan.Transaksi.PMBU');
	}
	public function getPmbuajax(Request $req)
	{
		if($req->ajax()){
			$res = [];
			$out = '';

			$items=data_mutasi_spb::with(['spbm' => function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_mutasi_spb_item.id_item')
					  ->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekappmbu($req->all())->get();
			$total=count($items);
			///if
			if($total > 0){
				$no =1;
				$id_mutasi_spb= '';
				foreach ($items as $item) {
					$i =1;
					foreach ($item->spbm as $data) {
						if($i == 1){
							$out .='<tr>
							<td>'.$no.'</td>
							<td class="text-left">'.$item->no_mutasi_spb.'</td>
							<td class="text-left">'.$item->nm_depan.''.$item->nm_belakang.'</td>
							<td calss="text-left">'.$item->gudang_termohon.'</td>
							<td class="text-left">' . \Format::indoDate2($item->deadline). '</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
							';
						}else{
							$out .='
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							';
						}
						$out .='
							<td class="text-left">'.$data->nm_barang.'</td>
							<td class="text-left">'.$data->qty_awal.' '.$data->nm_satuan.'</td>
							';
							/////
						if($data->qty > 0){
							$out .='
								<td class="text-left">'.$data->qty.''.$data->nm_satuan.'</td>
								';
							}else{
								$out .='
								<td class="text-left"></td>
								';

							}
							/////
						$out .='
						</tr>
							';
							$i++;
						# code.2..
					}
					$no++;
					# code.1..
				}

			}else{
				$out .='
					<tr>
						<td colspan="8">Data tidak ditemukan</td>
					</tr>
				';

		}
			$res['content'] = $out;
			return json_encode($res);
	}
}
	public function getPrintpmbu(Request $req)
	{
		$items=data_mutasi_spb::with(['spbm' => function($query){
					$query->join('data_barang','data_barang.id_barang','=','data_mutasi_spb_item.id_item')
						  ->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
				}])->rekappmbu($req->all())->get();
		return view('Print.Transaksi.pmbu',[
			'items' => $items,
			'req' 	=> $req
			]);
	}
	public function getSmbu(){
		$gud=ref_gudang::all();
		return view('Laporan.Transaksi.SMBU',[
			'gud' =>$gud,
			]);
	}
	public function getSmbuajax(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';

			$items= data_mutasi_skb::with(['rekapsmbu' =>function($query){
				$query->join('data_barang','data_barang.id_barang','=','data_mutasi_skb_item.id_item')
				->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
			}])->rekapsmb($req->all())->get();
			$total=count($items);
			if($total > 0){
				$no=1;
				$id_mutasi_skb= '';
				foreach ($items as $item) {
					$i =1;
					foreach ($item->rekapsmbu as $data) {
						if($i == 1){
							$out .='<tr>
								<td>'.$no.'</td>
								<td class="text-left">'.$item->no_mutasi_skb.'</td>
								<td class="text-left">'.$item->nm_depan.' '.$item->nm_belakang.'</td>
								<td class="text-left">'.$item->nm_gudang_asal.'</td>
								<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
								';
						}else{
							$out .= '
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							';
						}
							$out .= '
							<td class="text-left">' . $data->nm_barang . '</td>
							<td class="text-right">' . $data->qty_awal . ' ' . $data->nm_satuan . '</td>
							<td class="text-right">' . $data->qty . ' ' . $data->nm_satuan . '</td>
							
						</tr>
						';
						$i++;
						# code...
					}
					$no++;
					# code...
				}
			}else{
				$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
			}
			$res['content'] = $out;
			return json_encode($res);
		}
	}
	public function getPrintsmbu(Request $req)
		{
				$items= data_mutasi_skb::with(['rekapsmbu' =>function($query){
					$query->join('data_barang','data_barang.id_barang','=','data_mutasi_skb_item.id_item')
					->join('ref_satuan','data_barang.id_satuan','=','ref_satuan.id_satuan');
				}])->rekapsmb($req->all())->get();
			return view('Print.Transaksi.SMBU',[
				'items' => $items,
				'req' 	=> $req
				]);
		}
	public function getAsuransi(){
		$asuransi=ref_asuransi::all();
		$paymen=ref_payment_method::All();
		return view('Laporan.Keuangan.Asuransi',[
			'asuransi' =>$asuransi,
			'paymen'	=>$paymen
			]);
	}
	public function getAsuransiajax(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';
			$items=data_jurnal_pembayaran::asuransi($req->all())->get();
			
			$total=count($items);
			// dd($total);
			if($total > 0){
				$no=1;
				$id_faktur='';
				foreach ($items as $item) {
					$out.='<tr>
							<td>'.$no.'</td>
							<td class="text-left">'.$item->nama_pasien.'</td>
							<td class="text-left">'.$item->nomor_faktur.'</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
							<td class="text-left">'.$item->nm_asuransi.'</td>
							<td class="text-left">'.$item->no_asuransi.'</td>
							<td class="text-left">Rp'. number_format($item->jumlah,0,',','.').'</td>
						</tr>
						';
								
					$no++;
				}
			}else{
				$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
			}
			$res['content'] = $out;
			return json_encode($res);
		}
	}
	public function getPrintasuransi(Request $req){
		$items=data_jurnal_pembayaran::asuransi($req->all())->get();
		return view('Print.Transaksi.Keuangan.Asuransi',[
			'items' => $items,
			'req' 	=> $req
			]);
		}
	public function getBank(){
		$bank=ref_bank::all();
		return view('Laporan.Keuangan.Bank',[
			'bank' =>$bank,
			]);
		}
	public function getBankajax(Request $req){
		//dd($req->All());
		if($req->ajax()){
			$res = [];
			$out = '';
			$items=data_jurnal_pembayaran::bank($req->all())->get();
			
			$total=count($items);
			// dd($total);
			if($total > 0){
				$no=1;
				$id_faktur='';
				$total=0;
				foreach ($items as $item) {
					$total += $item->jumlah;
					$out.='<tr>
							<td>'.$no.'</td>
							<td class="text-left">'.$item->nama_pasien.'</td>
							<td class="text-left">'.$item->nomor_faktur.'</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
							<td class="text-left">'.$item->nm_bank.'</td>
							<td class="text-left">Rp .' . number_format($item->jumlah,0,',','.') . '</td>
						</tr>
						';
								
					$no++;
				}
				$out .='
						<tr>
							<td class="text-left" colspan="5"><center>Total</center> </td>
							<td  class="text-left"> Rp.' . number_format($total,0,',','.') . ',00</td>
						</tr>
					';
			}else{
				$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
			}
			$res['content'] = $out;
			return json_encode($res);
		}
	}
	public function getPrintbank(Request $req){
		$items=data_jurnal_pembayaran::bank($req->all())->get();
		return view('Print.Transaksi.Keuangan.bank',[
			'items' => $items,
			'req' 	=> $req
			]);
		}
	public function getCash(){

		return view('Laporan.Keuangan.Cash');
	}
	public function getCashajax(Request $req){
		//dd($req->All());
		if($req->ajax()){
			$res = [];
			$out = '';
			$items=data_jurnal_pembayaran::cash($req->all())->get();
			
			$total=count($items);
			// dd($total);
			if($total > 0){
				$no=1;
				$id_faktur='';
				$total=0;
				foreach ($items as $item) {
					$total += $item->jumlah;
					$out.='<tr>
							<td>'.$no.'</td>
							<td class="text-left">'.$item->nama_pasien.'</td>
							<td class="text-left">'.$item->nomor_faktur.'</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
							<td class="text-left">Rp.' . number_format($item->jumlah,0,',','.') . '</td>
						</tr>
						';
								
					$no++;
				}
				$out .='
						<tr>
							<td class="text-left" colspan="4"><center>Total</center> </td>
							<td  class="text-left"> Rp.' . number_format($total,0,',','.') . '</td>
						</tr>
					';
			}else{
				$out = '
			<tr>
				<td colspan="5">Data tidak ditemukan</td>
			</tr>
			';
			}
			$res['content'] = $out;
			return json_encode($res);
		}
	}
	public function getPrintcash(Request $req){
		$items=data_jurnal_pembayaran::cash($req->all())->get();
		return view('Print.Transaksi.Keuangan.Cash',[
			'items' => $items,
			'req' 	=> $req
			]);
		}
	public function getSumbangan(){
		$asuransi=ref_asuransi::all();
		$paymen=ref_payment_method::All();
		return view('Laporan.Keuangan.Sumbangan',[
		'asuransi' =>$asuransi,
		'paymen'	=>$paymen
		]);
	}
	public function getSumbanganajax(Request $req){
		//dd($req->All());
		if($req->ajax()){
			$res = [];
			$out = '';
			$items=data_jurnal_pembayaran::sumbangan($req->all())->get();
			
			$total=count($items);
			// dd($total);
			if($total > 0){
				$no=1;
				$id_faktur='';
				$total=0;
				foreach ($items as $item) {
					$total += $item->jumlah;
					$out.='<tr>
							<td>'.$no.'</td>
							<td class="text-left">'.$item->nama_pasien.'</td>
							<td class="text-left">'.$item->nomor_faktur.'</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
							<td class="text-left">Sumbangan</td>
							<td class="text-left">Rp .' . number_format($item->jumlah,0,',','.') . '</td>
						</tr>
						';
								
					$no++;
				}
				$out .='
						<tr>
							<td class="text-left" colspan="5"><center>Total</center> </td>
							<td  class="text-left"> Rp.' . number_format($total,0,',','.') . ',00</td>
						</tr>
					';
			}else{
				$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
			}
			$res['content'] = $out;
			return json_encode($res);
		}
	}
	public function getPrintsumbangan(Request $req){
		$items=data_jurnal_pembayaran::sumbangan($req->all())->get();
		return view('Print.Transaksi.Keuangan.sumbangan',[
			'items' => $items,
			'req' 	=> $req
			]);
		}
	public function getSubsidi(){
		$paymen=ref_payment_method::All();
		return view('Laporan.Keuangan.Subsidi',[
		'paymen'	=>$paymen
		]);
	}
	public function getSubsidiajax(Request $req){
		//dd($req->All());
		if($req->ajax()){
			$res = [];
			$out = '';
			$items=data_jurnal_pembayaran::subsidi($req->all())->get();
			
			$total=count($items);
			// dd($total);
			if($total > 0){
				$no=1;
				$id_faktur='';
				$total=0;
				foreach ($items as $item) {
					$total += $item->jumlah;
					$out.='<tr>
							<td>'.$no.'</td>
							<td class="text-left">'.$item->nama_pasien.'</td>
							<td class="text-left">'.$item->nomor_faktur.'</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
							<td class="text-left">Subsidi</td>
							<td class="text-left">Rp .' . number_format($item->jumlah,0,',','.') . '</td>
						</tr>
						';
								
					$no++;
				}
				$out .='
						<tr>
							<td class="text-left" colspan="5"><center>Total</center> </td>
							<td  class="text-left"> Rp.' . number_format($total,0,',','.') . ',00</td>
						</tr>
					';
			}else{
				$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
			}
			$res['content'] = $out;
			return json_encode($res);
		}
	}
	public function getPrintsubsidi(Request $req){
		$items=data_jurnal_pembayaran::subsidi($req->all())->get();
		return view('Print.Transaksi.Keuangan.Subsidi',[
			'items' => $items,
			'req' 	=> $req
			]);
		}
	public function getDeposit(){
		// $paymen=ref_payment_method::All();
		return view('Laporan.Keuangan.Deposit',[
		// 'paymen'	=>$paymen
		]);
	}
	public function getDepositajax(Request $req){
		//dd($req->All());
		if($req->ajax()){
			$res = [];
			$out = '';
			$items=data_jurnal_pembayaran::deposit($req->all())->get();
			
			$total=count($items);
			// dd($total);
			if($total > 0){
				$no=1;
				$id_faktur='';
				$total=0;
				foreach ($items as $item) {
					$total += $item->jumlah;
					$out.='<tr>
							<td>'.$no.'</td>
							<td class="text-left">'.$item->nama_pasien.'</td>
							<td class="text-left">'.$item->nomor_faktur.'</td>
							<td class="text-left">' . \Format::indoDate2($item->created_at). '</td>
							<td class="text-left">Deposit</td>
							<td class="text-left">Rp .' . number_format($item->jumlah,0,',','.') . '</td>
						</tr>
						';
								
					$no++;
				}
				$out .='
						<tr>
							<td class="text-left" colspan="5"><center>Total</center> </td>
							<td  class="text-left"> Rp.' . number_format($total,0,',','.') . ',00</td>
						</tr>
					';
			}else{
				$out = '
			<tr>
				<td colspan="8">Data tidak ditemukan</td>
			</tr>
			';
			}
			$res['content'] = $out;
			return json_encode($res);
		}
	}
	public function getPrintdeposit(Request $req){
		$items=data_jurnal_pembayaran::deposit($req->all())->get();
		return view('Print.Transaksi.Keuangan.Deposit',[
			'items' => $items,
			'req' 	=> $req
			]);
		}	

}

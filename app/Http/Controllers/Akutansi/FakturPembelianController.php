<?php

namespace App\Http\Controllers\Akutansi;

use App\Models\data_po;
use App\Models\ref_coa;
use App\Models\data_faktur;
use App\Models\data_vendor;
use App\Models\data_barang;
use App\Models\data_jurnal;
use App\Models\data_po_item;
use App\Models\data_faktur_item;
use App\Models\ref_payment_terms;
use App\Models\ref_payment_method;
use App\Models\data_hutang_vendor;
use App\Models\data_config_coa_pembelian as Config;
use App\Models\Views\view_report_faktur_pembelian;
use App\Jobs\Akutansi\Faktur\SaveJurnalJob;
use App\Models\data_client;

use App\Jobs\Akutansi\Faktur\CreateFakturJob;
use App\Jobs\Akutansi\Faktur\EditFaktur;

use App\Jobs\Pembelian\Vendor\AddVendorJob;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FakturPembelianController extends Controller {

	public function getIndex(){
		$data['status'] = [
			0 => 'Belum Bayar',
			1 => 'Nyicil',
			2 => 'Lunas',
			3 => 'Batal'
		];
		$data['items'] = data_faktur::daftar()->paginate(10);

		$data['total_hutang'] = data_hutang_vendor::hutang()->first();
		$data['total_hutang_tempo'] = data_hutang_vendor::hutangjatuhtempo()->first();
		$data['count_hutang'] = data_hutang_vendor::counthutang()->first();
		$data['count_hutang_tempo'] = data_hutang_vendor::counthutangjatuhtempo()->first();

		return view('Akutansi.FakturPembelian.Index', $data);
	}

	public function getItemsfaktur(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';

			$status = [
				0 => 'Belum Bayar',
				1 => 'Nyicil',
				2 => 'Lunas',
				3 => 'Batal'
			];

			$items = data_faktur::daftar($req->all())->paginate($req->limit);
			$total = $items->total();
			if($total > 0){
				$no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
				foreach($items as $item){
					 if(($item->status_faktur < 1)&& ($item->status < 1)):
					 	$edit='<a href="' . url('/fakturpembelian/edit/' . $item->id_faktur) . '">Edit</a>';
                       $warna='<a href="#" onclick="newtukar('. $item->id_faktur .');" data-toggle="modal" data-target="#detail"  class="label label-important">Tukar Faktur</a>';
                    else:
                        $warna='<span class="label label-success">Selesai TF</span>';
                    	$edit='';
                    endif;
					$out .= '
						<tr>
							<td>' . $no . '</td>
							<td>
								' . $item->nomor_faktur . '
								<div class="link">
									<small>
										[
											<a href="' . url('/fakturpembelian/view/' . $item->id_faktur) . '">Lihat</a> |
											'.$edit.' |
											<a target="_blank" href="' . url('/fakturpembelian/print/' . $item->id_faktur) . '">Print</a> |
											<a href="javascript:void(0);" onclick="hapus(' . $item->id_faktur . ');" class="text-danger">Batal</a>
										]
									</small>
								</div>
							</td>
							<td class="text-right">' . number_format($item->total,0,',','.') . '</td>
							<td>' . \Format::indoDate2($item->tgl_faktur) . '<br />&nbsp;</td>
							<td>' . \Format::indoDate2($item->duodate) . '<br />&nbsp;</td>
							<td>' . $status[$item->status] . '</td>
							<td>'.$warna.'</td>
						</tr>
					';
					$no++;
				}

			}else{
				$out = '<tr>
							<td colspan="6">Tidak ditemukan</td>
						</tr>';
			}

			$res['total'] = $total;
			$res['content'] = $out;
			$res['pagin'] = $items->render();

			return json_encode($res);
		}
	}

	public function getBaru(){
		$vendor=data_vendor::orderBy('nm_vendor', 'asc')->get();
		$terms = ref_payment_terms::all();

		return view('Akutansi.FakturPembelian.Baru', [
			'terms' => $terms,
			'vendor' =>$vendor
		]);
	}

	public function getAlamat(Request $req){
		if($req->ajax()){
			$res = [];
			$vendor = data_vendor::find($req->id);
			if($vendor == null)
				$res['alamat'] = '';
			else
				$res['alamat'] = $vendor->alamat;
			return json_encode($res);
		}
	}

	public function postAddsupplier(Request $req){
		if($req->ajax()){
			$arr = $this->dispatch(new AddVendorJob($req->all()));
			return json_encode($arr);
		}
	}

	public function getLoaditems(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';
			$items = data_barang::active($req->all())->paginate(5);
			$total = $items->total();

			if($total > 0):
				foreach($items as $item){
					$out .= '
						<tr class="barang-' . $item->id_barang . '">
							<td>' . $item->kode . '</td>
							<td>' . $item->nm_barang . ' <small class="pull-right hide item-loading-' . $item->id_barang . '">Memuat...</small></td>
							<td class="text-right"><button class="btn btn-white btn-small btn-item-' . $item->id_barang . '" onclick="add_item(' . $item->id_barang . ');"><i class="fa fa-plus"></i></button></td>
						</tr>
					';
				}
			else:
				$out = '
					<tr>
						<td colspan="3">Tidak ditemukan</td>
					</tr>
				';
			endif;

			$res['total'] = $total;
			$res['content'] = $out;
			$res['pagin'] = $items->render();

			return json_encode($res);
		}
	}

	public function getLoadpo(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';
			$items = data_po::active($req->all())->paginate(5);
			$total = $items->total();

			$status = [
				1 => 'Baru',
				2 => 'Proses',
				3 => 'Selesai'
			];

			if($total > 0):
				foreach($items as $item){
					$out .= '
						<tr class="po-' . $item->id_po . '">
							<td>' . $item->no_po . '</td>
							<td>' . \Format::hari($item->created_at) . ', ' . \Format::indoDate2($item->created_at) . '</td>
							<td>' . $status[$item->status] . '</td>
							<td class="text-right"><button onclick="add_itempo(' . $item->id_po . ');" class="btn btn-po-' . $item->id_po . ' btn-white btn-small"><i class="fa fa-plus"></i></button></td>
						</tr>
					';
				}
			else:
				$out = '
					<tr>
						<td colspan="4">Tidak ditemukan</td>
					</tr>
				';
			endif;

			$res['total'] = $total;
			$res['content'] = $out;
			$res['pagin'] = $items->render();


			return json_encode($res);
		}

	}

	/* Mengambil Barang berdasarkan ID */
	public function getAdditem(Request $req){
		if($req->ajax()){
			$item = data_barang::join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_barang.id_satuan')
				->join('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
				->where('data_barang.id_barang', $req->id)
				->select(
					'data_barang.id_barang',
					'data_barang.id_satuan',
					'data_barang.kode',
					'data_barang.nm_barang',
					'data_barang.harga_beli',
					'ref_satuan.nm_satuan',
					'ref_kategori.id_coa'
				)
				->first();

			return json_encode($item);
		}
	}

	/* Menambahkan Barang berdasarkan PO */
	public function getAdditempo(Request $req){
		if($req->ajax()){
			$res = [];
			$out = [];
			$po = data_po::find($req->id);
			$items = data_po_item::allpo($req->id)->get();
			foreach($items as $item){

				/* MATEMATIKA */
				$diskon = $item->harga * $item->diskon / 100;
				$aftdiskon = $item->harga - $diskon;
				$ppn = $aftdiskon + $item->ppn;
				$pph = $aftdiskon + $item->pph;
				$harga = $aftdiskon + $ppn + $pph;

				$out[] = [
					'id_barang' => $item->id_item,
					'kode' => $item->kode,
					'nm_barang' => $item->nm_barang,
					'id_satuan' => $item->id_satuan,
					'nm_satuan' => $item->nm_satuan,
					'qty' => $item->req_qty,
					'diskon' => $item->diskon,
					'ppn' => $item->ppn,
					'pph' => $item->pph,
					'id_coa' => $item->id_coa,
					'harga' => $item->harga,
					'total' => $harga * $item->req_qty
				];
			}
			$res['po'] = $po;
			$res['items'] = $out;
			return json_encode($res);
		}
	}



	public function postBaru(Request $req){

		if(count($req->id_barang) == 0)
			return redirect()->back()->withNotif([
				'label' => 'danger',
				'err' => '<center>OOps!, Item tidak ditemukan</center>'
			]);

		$arr = $this->dispatch(new CreateFakturJob($req->all()));
		if($arr['res'])
			return redirect('/fakturpembelian')->withNotif([
					'label' => $arr['label'],
					'err' => $arr['err']
				]);
		else
			return redirect()->back()->withNotif([
					'label' => $arr['label'],
					'err' => $arr['err']
				]);
	}

	public function postNewtukar(Request $req){
		// dd($req->all());
		if($req->ajax()){
            $result = [];
            $out = '';

		// if(empty($id))
		// 	return redirect('/fakturpembelian');

		$faktur = data_faktur::views($req->id)->first();
		$items = data_faktur_item::byfaktur($req->id)->get();

		// if($faktur == null)
		// 	return redirect('/fakturpembelian')->withNotif([
		// 		'label' => 'danger',
		// 		'err' => 'Faktur tidak ditemukan'
		// 	]);

		// if($faktur->status == 3)
		// 	return redirect('/fakturpembelian');

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

		$jurnals = data_jurnal::faktur($req->id)->get();

		$total_bayar = 0;
		foreach($jurnals as $ju){
			$total_bayar += $ju->total;
		}
			$out .='
			<div class="grid simple header-status">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">

				<div class="row">
					<div class="col-sm-7">

						<p>
							<div class="status-faktur">
								<span class="label label-'. $status[$faktur->status]['label'] .'">
									'. $status[$faktur->status]['err'] .'
								</span>
							</div>
						</p>

						<address>
							<strong>Terima dari.</strong>
							<h4>'. $faktur->nm_vendor .'</h4>
							<p>KODE #'. $faktur->kode .'</p>
							<p>
								'. $faktur->alamat .'<br />
								Telpon :'. $faktur->telpon .'<br />
								Email :'. $faktur->email .'
							</p>
						</address>
						<p><em>"'. $faktur->keterangan .'"</em></p>
					</div>
					<div class="col-sm-5 text-right">
						<address>

							<strong>Tanggal</strong>
							<p>'. \Format::indoDate($faktur->tgl_faktur) .'</p>
							<strong>Tanggal Jatuh Tempo</strong>
							<p>'. \Format::indoDate($faktur->duodate) .'</p>
							<strong>Total</strong>
							<h4>'. \number_format($faktur->total,2,',','.') .'</h4>
							<strong>Amount Due</strong>
							<h4>'. \number_format(($faktur->total - $faktur->amount_due),2,',','.') .'</h4>
						</address>
					</div>
				</div>

		</div>
	</div>
	';
	$out .='

	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">

			<table class="table">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th width="30%">Barang</th>
						<th class="text-right" width="15%">Qty</th>
						<th class="text-right" width="10%">Diskon</th>
						<th class="text-right" width="20%">Harga</th>
						<th class="text-right" width="20%">Total</th>
					</tr>
				</thead>

				<tbody>
				';
					$no=1;
					foreach($items as $item){
				$out .='
					<tr>
						<td>'.$no.'</td>
						<td>'. $item->deskripsi .'</td>
						<td align="right">'. $item->qty .' '. $item->nm_satuan .'</td>
						<td align="right">'. \number_format($item->diskon,0,',','.') .'%</td>
						<td align="right">'. \number_format($item->harga,2,',','.') .'</td>
						<td align="right">'. \number_format($item->total,2,',','.') .'</td>
					</tr>
					';
					$no++;
					}


					/* Matematika */
					$disikon = ($faktur->subtotal * $faktur->diskon) / 100;
					$aftdiskon = $faktur->subtotal - $disikon;
					$ppn = ($aftdiskon * $faktur->ppn) / 100;

					$out .='
					<tr>
						<td colspan="4" rowspan="7"></td>
						<td align="right" class="bold">Subtotal</td>
						<td align="right">'. number_format($faktur->subtotal,2,',','.') .'</td>
					</tr>
					<tr>
						<td align="right" class="bold">Diskon '. number_format($faktur->diskon,1,',','.') .'%</td>
						<td align="right">'. \number_format($disikon,2,',','.') .'</td>
					</tr>
					<tr>
						<td align="right" class="bold">PPN '. \number_format($faktur->ppn,1,',','.') .'%</td>
						<td align="right">'. \number_format($ppn,2,',','.') .'</td>
					</tr>
					<tr>
						<td align="right" class="bold">Adjustment</td>
						<td align="right">'. \number_format($faktur->adjustment,2,',','.') .'</td>
					</tr>
					<tr>
						<td align="right" class="bold">Total</td>
						<td align="right" class="bold">'. \number_format($faktur->total,2,',','.') .'</td>
					</tr>

					<tr>
						<td align="right" class="bold"><h5><strong>Total Bayar</strong></h5></td>
						<td align="right" class="bold"><h5><strong>'. number_format($total_bayar,2,',','.') .'</strong></h5></td>
					</tr>

					<tr>
						<td align="right" class="bold">Amount Due</td>
						<td align="right" class="bold">'. number_format(($faktur->total - $faktur->amount_due),2,',','.') .'</td>
					</tr>

				</tbody>

			</table>

		</div>
	</div>';

		$btn ='<button data-loading-text="<i class=\'fa fa-circle-o-notch fa-spin\'></i> Proses..." class="btn btn-primary btn-acc" onclick="acc(' . $req->id . ');"><i class="fa fa-check"></i> Tukar Faktur</button>';
			  $result['no_faktur']     = $faktur->nomor_faktur;
			 $result['content']  = $out;
		    $result['button']   = $btn;

	    return json_encode($result);

	}
	}
	public function postAcc(Request $req){
	    if($req->ajax()){
	        $tukar = data_faktur::find($req->id);
	        $tukar->update([
	        	'status_faktur'=>1,
	        	'id_acc_faktur' => \Me::data()->id_karyawan,
            	'tgl_tukar_faktur' => date('Y-m-d H:i:s')
	        ]);


	        \Loguser::create('Anda Berhasil Melakukan Tukar Faktur. ' );
	    }
    }

	public function getView($id){

		if(empty($id))
			return redirect('/fakturpembelian');

		$faktur = data_faktur::views($id)->first();
		$items = data_faktur_item::byfaktur($id)->get();

		if($faktur == null)
			return redirect('/fakturpembelian')->withNotif([
				'label' => 'danger',
				'err' => 'Faktur tidak ditemukan'
			]);

		if($faktur->status == 3)
			return redirect('/fakturpembelian');

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

		$config = Config::active()->first();
		$nm_coa_hutang = ref_coa::find($config->coa_jumlah_sebelum_dibayar);

		return view('Akutansi.FakturPembelian.view', [
			'faktur' => $faktur,
			'items' => $items,
			'status' => $status,
			'methods' => $methods,
			'jurnals' => $jurnals,
			'select_coa' => $select_coa,
			'total_bayar' => $total_bayar,
			'akun_bank' => $akun_bank,
			'coa_jumlah_sebelum_dibayar' => $config->coa_jumlah_sebelum_dibayar,
			'coa_hutang' => $nm_coa_hutang->nm_coa
		]);
	}

	public function postSavejurnal(Request $req){
		$arr = $this->dispatch(new SaveJurnalJob($req->all()));
		return redirect()->back()->withNotif([
			'label' => $arr['label'],
			'err' => $arr['err']
		]);
	}

	public function getEdit($id = 0){

		if(empty($id))
			return redirect('/fakturpembelian');

		$faktur = data_faktur::find($id);
		$items = data_faktur_item::byfaktur($id)->get();

		if($faktur->status == 3)
			return redirect('/fakturpembelian');

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

		$terms = ref_payment_terms::all();
		return view('Akutansi.FakturPembelian.edit', [
			'faktur' => $faktur,
			'items' => $items,

			'terms' => $terms,
			'status' => $status
		]);
	}

	public function postEdit(Request $req){

		$arr = $this->dispatch(new EditFaktur($req->all()));

		return redirect()->back()->withNotif([
			'label' => $arr['label'],
			'err' => $arr['err']
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

	public function getPrint($id){

		if(empty($id))
			return redirect('/fakturpembelian');

		$faktur = data_faktur::views($id)->first();
		$items = data_faktur_item::byfaktur($id)->get();

		if($faktur->status == 3)
			return redirect('/fakturpembelian');

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

		return view('Akutansi.FakturPembelian.print', [
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


	public function getConfig(){

		$coas = [];
		foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
			$coas[$coa->parent_id][] = $coa;
		}

		$config = Config::active()->first();
		// PPN
		$data['coa_ppn'] = \Format::select_coa($coas, 0, $config->coa_ppn);
		// adjustment
		$data['coa_adjustment'] = \Format::select_coa($coas, 0, $config->coa_adjustment);
		// coa_jumlah_sebelum_dibayar
		$data['coa_jumlah_sebelum_dibayar'] = \Format::select_coa($coas, 0, $config->coa_jumlah_sebelum_dibayar);
		// coa_penambahan_item
		$data['coa_penambahan_item'] = \Format::select_coa($coas, 0, $config->coa_penambahan_item);
		// coa_pembayaran_cash
		$data['coa_pembayaran_cash'] = \Format::select_coa($coas, 0, $config->coa_pembayaran_cash);

		return view('Akutansi.FakturPembelian.config', $data);
	}

	public function postConfig(Request $req){
		$data = $req->all();
		unset($data['_token']);
		$data['aktif'] = 1;
		Config::where('aktif', 1)->update([
			'aktif' => 0
		]);
		Config::create($data);

		return redirect()->back()->withNotif([
			'label' => 'success',
			'err' => 'Seting Akun Pembelian berhasil diperbaharui'
		]);
	}
	public function getReport(Request $req){
		return view('Akutansi.Report.Print.pembelian');
	}
	public function getRekappembelian(Request $req){
        if($req->ajax()){
            $res = [];
            $out = '';
            $item = view_report_faktur_pembelian::pembelian($req->all())->get();
			$client = data_client::first();
			// dd($client);
            $total= count($item);
            if($total > 0){
                $no=1;
                foreach ($item as $r) {
					$persen= $r->total/100;
					$kali = $persen * $r->diskon;
					$dpp=$r->total - $kali;
					//uuntuk mencari ppn
					$kali_ppn = $persen * $r->ppn;
					$ppn_dpp = $kali_ppn + $dpp;
                    $out .='
                            <tr>
								<td>'.$no.'</td>
                                <td>'.$r->tgl_faktur.'</td>
                                <td>'.$r->nomor_faktur.'</td>
                                <td>'.$r->nm_vendor.'</td>
                                <td>'.$r->no_npwp.'</td>
								<td>'.$client->nm_client.'</td>
								<td>'.$client->alamat.'</td>
								<td>'.$client->no_npwp.'</td>
								<td>'.number_format($r->total,0,',','.').'</td>
								<td>'.$r->diskon.'(%)</td>
								<td>'.number_format($dpp,0,',','.').'</td>
								<td>'.$r->ppn.' (%)</td>
								<td>'.number_format($ppn_dpp,0,',','.').'</td>
                            </tr>
                            ';
                        $no++;
                }

            }else{
              $out  ='
                <tr>
                    <td colspan="6">Tidak di Temukan</td>
                </tr>
                ';
             }
        }
    $res['content'] = $out;
    return json_encode($res);
    }
	public function getPrintpembelian(Request $req){
	$client = data_client::first();
    $medis = view_report_faktur_pembelian::pembelian($req->all())->get();
    return view('Akutansi.Report.Print.report_p_index',[
        'medis' => $medis,
		'client' => $client,
        'req'   => $req
        ]);
    }

}

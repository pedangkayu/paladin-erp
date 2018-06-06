<?php

namespace App\Http\Controllers\Biling;

use App\Models\ref_coa;
use App\Models\ref_bank;
use App\Models\ref_kelas;
use App\Models\data_resep;
use App\Models\data_jurnal;
use App\Models\data_faktur;
use App\Models\data_deposit;
use App\Models\ref_asuransi;
use App\Models\data_treatment;
use App\Models\data_log_pasien;
use App\Models\data_rawat_inap;
use App\Models\data_faktur_item;
use App\Models\data_shift_kasir;
use App\Models\ref_payment_terms;
use App\Models\data_voucer_jurnal;
use App\Models\data_faktur_pasien;
use App\Models\ref_payment_method;
use App\Models\data_rawat_inap_pakai;
use App\Models\data_jurnal_pembayaran;
use App\Models\ref_payment_method_item;
use App\Models\data_vendor;
use App\Models\data_pasien;
use App\Models\data_karyawan;

use App\Jobs\Biling\CreateBilingJob;
use App\Jobs\Biling\PaymentMethodJobs;
use App\Jobs\Biling\InputCoaJob;
use App\Jobs\Biling\editBilingJob;
use App\Jobs\Biling\getPaymentJob;
use App\Jobs\Biling\UpdateConfigurasiJob;
use App\Jobs\Deposit\UpdateConfigurasiDeposit;
use App\Jobs\Pinjaman\UpdateconfigCoaLoanJob;
use App\Jobs\Pinjaman\UpdateconfigCoaPembelianJob;
use App\Jobs\Pinjaman\UpdateconfigCoaPendapatanJob;

use App\Models\data_config_coa_biling as Config;
use App\Models\data_config_coa_deposit as configdep;
use App\Models\data_config_coa_loan as configloan;
use App\Models\data_config_coa_pembelian as configpembelian;
use App\Models\data_config_coa_pendapatan as configpendapatan;

use App\Models\Views\view_laporan_shift;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BilingController extends Controller {

	public function getIndex(){

		$data['items'] = data_faktur::daftarbiling(['status' => '-'])->paginate(10);
		$data['total'] = $data['items']->total();
		$data['status'] = [
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
		return view('Biling.Index', $data);
	}

	public function getItemsfaktur(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';

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

			$items = data_faktur::daftarbiling($req->all())->paginate($req->limit);
			$total = $items->total();
			if($total > 0){
				$no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;
				foreach($items as $item){
					$out .= '
						<tr>
							<td>' . $no . '</td>
							<td>
								<div>' . $item->nomor_faktur . '</div>
								<small>[
									<a href="' . url('/biling/view/' . $item->id_faktur) . '">Lihat</a> |
									<a href="' . url('/biling/edit/' . $item->id_faktur) . '">Edit</a>
								]</small>
							</td>
							<td>' . $item->nama_pasien . '</td>
							<td>
								' . \Format::indoDate2($item->tgl_faktur) . '
								<div><small class="text-muted">' . \Format::hari($item->tgl_faktur) . ', ' . \Format::jam($item->craeted_at) . '</small></div>
							</td>
							<td class="text-center">
								<span class="label label-' . $status[$item->status]['label'] . '">
									' . $status[$item->status]['err'] . '
								</span>
							</td>
							<td class="text-right">' . number_format($item->total,0,',','.') . '</td>
						</tr>
					';
					$no++;
				}

			}else{
				$out = '<tr><td colspan="6">Tidak ada</td></tr>';
			}

			$res['total'] = $total;
			$res['content'] = $out;
			$res['pagin'] = $items->render();

			return json_encode($res);
		}
	}


	public function getView($id = 0){

		$data['status'] = [
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

		if(empty($id))
			return redirect('/biling');
		$biling = data_faktur::biling($id)->first();

		$total = 0;
		$id_deposit = 0;
		$saldo = data_deposit::where('id_pasien', $biling->id_pasien);
		if($saldo->count() > 0){
			$sl = $saldo->first();
			$total = $sl->saldo;
			$id_deposit = $sl->id_deposit;
		}

		$unverified = data_log_pasien::where('id_pasien', $biling->id_pasien)->where('status_validasi', 0)->count();


		$data['jenis_bayar'] = ref_payment_method::where('status', 1)->get();
		$data['biling'] = $biling;
		$data['tambahan'] = data_faktur_item::biling($id)->get();
		$data['reseps'] = data_faktur_pasien::resep($id)->get();
		$data['treatments'] = data_faktur_pasien::treatment($id)->get();
		$data['rinaps'] = data_faktur_pasien::rinaps($id)->get();
		$data['banks'] = ref_bank::all();
		$data['asuransi'] = ref_asuransi::all();
		$data['payments'] = data_jurnal_pembayaran::where('id_faktur', $id)->get();
		$data['total_payment'] = 0;
		$data['deposit'] = $total;
		$data['id_deposit'] = $id_deposit;
		$data['unverified'] = $unverified;
		//dd($data);
		return view('Biling.View', $data);
	}


	public function getEdit($id = 0){

		$data['status'] = [
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

		if(empty($id))
			return redirect('/biling');

		$data['terms'] = ref_payment_terms::all();

		$data['biling'] = data_faktur::biling($id)->first();
		$data['tambahan'] = data_faktur_item::biling($id)->get();
		$data['reseps'] = data_faktur_pasien::resep($id)->get();
		$data['treatments'] = data_faktur_pasien::treatment($id)->get();
		$data['rinaps'] = data_faktur_pasien::rinaps($id)->get();
		$data['banks'] = ref_bank::all();
		$data['asuransi'] = ref_asuransi::all();
		$data['payments'] = data_jurnal_pembayaran::where('id_faktur', $id)->get();
		$data['total_payment'] = 0;
		//dd($data);
		return view('Biling.edit', $data);
	}

	public function getPrint($id = 0, Request $req){

		if(empty($id))
			return redirect('/biling');

		$paper = $req->paper;


		$resep = data_faktur_pasien::resep($id)->get();
		$treatment = data_faktur_pasien::treatment($id)->get();
		$data['rinaps'] = data_faktur_pasien::rinaps($id)->get();

		$data['biling'] = data_faktur::biling($id)->first();
		$data['tambahan'] = data_faktur_item::biling($id)->get();
		$data['reseps'] = $resep;
		$data['treatments'] = $treatment;

		$data['first_resep'] = count($resep) > 0 ? $resep[0] : [];
		$data['first_treatment'] = count($treatment) > 0 ? $treatment[0] : [];

		$data['total_resep'] = 0;
		$data['halaman'] = 0;


		if($paper == 'a5')
			$data['perhalaman'] = 8;
		else
			$data['perhalaman'] = 13;

		if($paper == 'a5')
			return view('Biling.printA5', $data);
		else
			return view('Biling.printA4', $data);
	}

	public function getCreate($id_pasien = ''){

		$data['terms'] = ref_payment_terms::all();
		$data['id_pasien'] = $id_pasien;
		//dd($data);
		return view('Biling.Create', [
			'data' => $data
		]);
	}

	public function postCreate(Request $req){
		// dd($req->all());
		$err = $this->dispatch(new CreateBilingJob($req->all()));

		if($err['result'])
			return	redirect('/biling/view/' . $err['id_faktur'])->withNotif([
					'label' => $err['label'],
					'err' => $err['err']
			]);
		else
			return	redirect()->back()->withNotif([
					'label' => $err['label'],
					'err' => $err['err']
			]);
	}

	public function getPasiens(Request $req){
		if($req->ajax()){

			$res = [];
			$items = data_log_pasien::checkin($req->all())->get();
			foreach($items as $item){
				$out[] = [
					'id' => $item->id_pasien,
					'name' => $item->nama_pasien,
					'alamat' => $item->alamat_pasien,
					'tgl_lahir' => \Format::indoDate($item->tgllahir_pasien)
				];
			}


			return json_encode($out);

		}
	}

	public function getLoadinvoice(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '';
			$list = '';

			$total = 0;
			$urut = [];

			$id_resep = data_log_pasien::resep($req->id);
			$id_treatment = data_log_pasien::treatment($req->id);
			$id_rinap = data_log_pasien::rinap($req->id);

			$unverified = data_log_pasien::where('id_pasien', $req->id)->where('status_validasi', 0)->count();
			$verified = data_log_pasien::where('id_pasien', $req->id)->where('status_validasi', 1)->count();


			// Resep obat
			$resep = data_resep::bilingpasien($id_resep['result'])->get();
			if(count($resep) > 0): // section 1
				foreach($resep as $item):
					$list .= '';

					$out .= '
						<div class="grid simple resep-' . $item->id_resep . '">
							<div class="grid-title text-left no-border">
								 <button type="button" class="close" onclick="hapus_pake(\'resep-' . $item->id_resep . '\');"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 style="margin:0;"><strong><i>#' . $item->nomor_resep . '</i></strong></h4>
								<div><span class="text-muted">' . $item->catatan . '</span></div>
							</div>
							<div class="grid-body no-border">
								<input type="hidden" name="id_resep[]" value="' . $item->id_resep . '" />
								<table class="table table-bordered">
									<thead>
										<tr>
											<th width="5%">No</th>
											<th width="35%">Uraian</th>
											<th width="15%">Jumlah</th>
											<th width="15%">Biaya</th>
											<th width="15%">Diskon</th>
											<th width="15%">Total</th>
										</tr>
									</thead>
									<tbody class="resep-obat">';

									$items = $item->obat()
										->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
										->leftJoin('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
										->select('data_barang.nm_barang', 'data_barang.id_satuan', 'data_barang.harga_beli', 'data_resep_item.*', 'ref_kategori.id_coa', 'ref_kategori.coa_pembelian')
										->get();

									$no = 1;
									$u = 1;
									foreach($items as $obat){

										if($obat->id_barang > 0): // obat paten

										$sub_total = $obat->qty * $obat->harga_jual;
										$total += $sub_total;
										$urut[] = $obat->harga_jual;
										$out .= '
											<tr>
												<td>' . $no . '</td>
												<td>
													' . $obat->nm_barang . '
													<input type="hidden" name="id_resep_item[' . $item->id_resep . '][]" value="' . $obat->id_resep_item . '" />
													<input type="hidden" name="nama_barang[' . $item->id_resep . '][]" value="' . $obat->nm_barang . '" />
													<input type="hidden" name="harga_jual_resep_item[' . $item->id_resep . '][]" value="' . $obat->harga_jual . '" />
													<input type="hidden" name="harga_beli_resep_item[' . $item->id_resep . '][]" value="' . $obat->harga_beli . '" />
													<input type="hidden" name="id_item_obat_resep[' . $item->id_resep . '][]" value="' . $obat->id_barang . '"/>

													<input type="hidden" name="resep_coa_persediaan[' . $item->id_resep . '][]" value="' . $obat->id_coa . '"/>
													<input type="hidden" name="resep_coa_biaya[' . $item->id_resep . '][]" value="' . $obat->coa_pembelian . '"/>
												</td>
												<td class="text-center">
													<input type="number" style="width:100%;" onkeyup="hitung()" data-nilai="qty" name="qty_resep_item[' . $item->id_resep . '][]" value="' . $obat->qty . '" />
													<input type="hidden" name="id_satuan_resep_item[' . $item->id_resep . '][]" value="' . $obat->id_satuan . '" />
												</td>
												<td class="text-right">' . number_format($obat->harga_jual,0,',','.') . '</td>

												<td>
													<div class="input-group">
														<input type="text" onkeyup="diskon();" style="width:100%;" data-nilai="diskon" class="text-right" name="diskon_resep_item[' . $item->id_resep . '][]" value="0" />
														<span class="input-group-addon">%</span>
													</div>
												</td>
												<td class="text-right">
													<input type="hidden" data-nilai="tarif_dasar" value="' . $obat->harga_jual . '"/>

													<input type="text" readonly="readonly" value="' . number_format($sub_total,0,',','.') . '" style="width:100%;" class="text-right" data-view="subtotal" />
													<input type="hidden" name="subtotal_resep_item[' . $item->id_resep . '][]" value="' . $sub_total . '" data-nilai="subtotal"/>
													<input type="hidden" data-nilai="persen_dr" value="50"/>
													<input type="hidden" data-nilai="persen_rs" value="50"/>
													<input type="hidden" data-nilai="tarif_dr" value="0" />

													<input type="hidden" data-view="tarif_rs">
													<input type="hidden" data-view="tarif_dr">
												</td>
											</tr>
										';
										$no++;

										endif; // end Obat paten

										if($obat->id_barang < 1): // CAmpur

											$out .= '<tr>
												<td colspan="5">
													<h5 class="semi-bold">Obat Campur ' . $u . '</h5>

													<input type="hidden" name="id_resep_item[' . $item->id_resep . '][]" value="' . $obat->id_resep_item . '" />
													<input type="hidden" name="id_item_obat_resep[' . $item->id_resep . '][]" value="' . $obat->id_barang . '"/>
													<input type="hidden" name="harga_jual_resep_item[' . $item->id_resep . '][]" value="0" />
													<input type="hidden" name="id_coa_resep[' . $item->id_resep . '][]" value="0"/>
													<input type="hidden" name="qty_resep_item[' . $item->id_resep . '][]" value="0" />
													<input type="hidden" name="id_satuan_resep_item[' . $item->id_resep . '][]" value="0" />
													<input type="hidden" name="diskon_resep_item[' . $item->id_resep . '][]" value="0" />
													<input type="hidden" name="subtotal_resep_item[' . $item->id_resep . '][]" value="0"/>
													<input type="hidden" name="harga_beli_resep_item[' . $item->id_resep . '][]" value="0" />
													<input type="hidden" name="nama_barang[' . $item->id_resep . '][]" value="" />
													<input type="hidden" name="resep_coa_persediaan[' . $item->id_resep . '][]" value="0"/>
													<input type="hidden" name="resep_coa_biaya[' . $item->id_resep . '][]" value="0"/>

												</td>
											</tr>';

											$campurs = $obat->campur()
												->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
												->leftJoin('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
												->select('data_resep_campur.*','data_barang.nm_barang', 'data_barang.harga_beli','data_barang.id_barang', 'data_barang.id_satuan', 'ref_kategori.id_coa', 'ref_kategori.coa_pembelian')
												->get();
											$n = 1;
											foreach($campurs as $campur):
												$sub = $campur->qty * $campur->harga_jual;
												$total += $sub;
												$urut[] = $campur->harga_jual;
												$out .= '
													<tr>
														<td>' . $n . '</td>
														<td>
															' . $campur->nm_barang . '
															<input type="hidden" name="nm_barang[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $campur->nm_barang . '"/>
														</td>
														<td class="text-center">
															<input onkeyup="hitung()" data-nilai="qty" type="number" name="qty_item_campur[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $campur->qty . '"/>
														</td>
														<td class="text-right">
															' . number_format($campur->harga_jual,0,',','.') . '
															<input type="hidden" name="id_barang_item_campur[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $campur->id_barang . '"/>
															<input type="hidden" name="harga_item_campur[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $campur->harga_jual . '"/>
															<input type="hidden" name="harga_beli_item_campur[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $campur->harga_beli . '"/>

															<input type="hidden" name="resep_campur_coa_persediaan[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $obat->id_coa . '"/>
															<input type="hidden" name="resep_campur_coa_biaya[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $obat->coa_pembelian . '"/>
														</td>

														<td>
															<div class="input-group">
																<input type="text" onkeyup="diskon();" style="width:100%;" data-nilai="diskon" class="text-right" name="diskon_campur_item[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="0" />
																<span class="input-group-addon">%</span>
															</div>
														</td>
														<td class="text-right">
															<input type="hidden" value="' . $campur->id_resep_campur . '" name="id_resep_campur[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" />

															<input type="hidden" data-nilai="tarif_dasar" value="' . $campur->harga_jual . '"/>

															<input type="text" readonly="readonly" value="' . ($sub) . '" style="width:100%;" class="text-right" data-view="subtotal" />
															<input type="hidden" name="id_satuan_item_campur[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $campur->id_satuan . '"/>
															<input type="hidden" name="subtotal_item_campur[' . $item->id_resep . '][' . $obat->id_resep_item . '][]" value="' . $sub . '" data-nilai="subtotal"/>
															<input type="hidden" data-nilai="persen_dr" value="50"/>
															<input type="hidden" data-nilai="persen_rs" value="50"/>
															<input type="hidden" data-nilai="tarif_dr" value="0" />

															<input type="hidden" data-view="tarif_rs">
															<input type="hidden" data-view="tarif_dr">
														</td>
													</tr>
												';
												$n++;
											endforeach;
											$u++;
										endif; // End campur

									}

					$out .= '
									</tbody>
								</table>
							</div>
						</div>
					';
				endforeach;
			else: // else section 1
				$out .= '<div class="well"><h5>Resep Tidak ada!</h5></div>';
			endif; // end section 1
			// End Resep obat


			$treatments = data_treatment::bilingtreatment($id_treatment['result'])->get();
			if(count($treatments) > 0): // star treatment

			foreach($treatments as $tr): // treatment
				$out .= '<div class="grid simple treatment-' . $tr->id_treatment . '">
							<div class="grid-title text-left no-border">
								 <button type="button" class="close" onclick="hapus_pake(\'treatment-' . $tr->id_treatment . '\');"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 style="margin:0;"><strong><i>#' . $tr->nomor_treatment . '</i></strong></h4>
								<div><span class="text-muted">' . $tr->nm_gudang . '</span></div>
								<div><span class="text-muted">' . $tr->catatan . '</span></div>
							</div>
							<div class="grid-body no-border">
							<input type="hidden" name="id_treatment[]" value="' . $tr->id_treatment . '" />

								<table class="table table-bordered">
									<thead>
										<tr>
											<th width="5%">No</th>
											<th width="20%" colspan="2">Uraian</th>
											<th width="10%" colspan="4">Tarif Dasar</th>

											<th width="15%">Diskon Dokter</th>
											<th width="15%">Subtotal</th>
										</tr>
									</thead>
									<tbody class="resep-obat">';
					$trs = $tr->items()
						->leftJoin('ref_service', 'ref_service.id_service', '=', 'data_treatment_item.id_service')
						->leftJoin('ref_service_grup', 'ref_service_grup.id_grup', '=','ref_service.id_grup')
						//->join('ref_service_kode', 'ref_service_kode.id_tindakan', '=','ref_service.parend_id')
						->leftJoin('ref_service_kode', 'ref_service_kode.service_kode', '=','data_treatment_item.service_kode')
						->select(
							'ref_service_grup.grup',
							'ref_service_kode.nm_service',
							'ref_service_kode.persen_rs',
							'ref_service_kode.coa_pendapatan',
							'ref_service_kode.persen_dr',
							'ref_service_kode.nm_service AS tindakan',
							'data_treatment_item.tarif_dasar',
							'data_treatment_item.tipe',
							'data_treatment_item.id_treatment_item',
							'ref_service_kode.coa_rs',
							'ref_service_kode.coa_dr',
							'ref_service_kode.coa as id_coa'
						)
						//->orderby('data_treatment_item.id_treatment_item', 'desc')
						->get();

					$nt = 1;
					//dd($trs);
					foreach($trs as $tritem): // items treatment

						$tarif_dasar = empty($tritem->tarif_dasar) ? 0 : $tritem->tarif_dasar;


						$harga_rs = $tarif_dasar * $tritem->persen_rs /100;
						$harga_dr = $tarif_dasar * $tritem->persen_dr /100;

						$total += $harga_rs + $harga_dr;
						$urut[] = $tarif_dasar;

						/* RUMUS KELAS
							$kelas = [];
							$kelass = ref_kelas::whereStatus(1)->orderby('seri', 'asc')->get();
							$dasar = $tritem->tarif_dasar;
							foreach($kelass as $kls){

								$tambah = $kls->tambah_nominal;
								$persen = $kls->persen;

								$a = $dasar + $tambah;
								$b = ($a * $persen) / 100;
								$subtotal = ($a + $b);
								$kelas[$kls->kode_kelas] = $subtotal;
								$dasar += $subtotal * $kls->x;

							}

							dd($kelas);
						 END RUMUS KELAS */

						 if($tritem->tipe == 1):

						 	$out .= '
								<tr>
									<td>' . $nt . '</td>
									<td colspan="8">
										<div>
											' . $tritem->nm_service  . '
											<input type="hidden" name="nama_treatment_item[' . $tr->id_treatment . '][]" value="' . $tritem->nm_service . '" />
										</div>
										<small>' . $tritem->grup . ' &raquo; ' . $tritem->tindakan . '</small>

										<input type="hidden" name="id_treatment_item[' . $tr->id_treatment . '][]" value="' . $tritem->id_treatment_item . '" />

										<input type="hidden" onkeyup="hitung()" name="tarif_dasar_treatment[' . $tr->id_treatment . '][]" data-nilai="tarif_dasar" value="' . $tritem->tarif_dasar . '"/>

										<input type="hidden" class="text-right"  style="width:100%;"readonly data-view="tarif_dr" value="' . number_format($harga_dr,0,',','.') . '" />
										<input type="hidden" name="tarif_dr_treatment[' . $tr->id_treatment . '][]" data-nilai="tarif_dr" value="' . $harga_dr . '" />
										<input type="hidden" name="coa_dr_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->coa_dr . '" />
										<input type="hidden" data-nilai="qty" value="1">

										<div class="input-group">
											<input type="hidden" onkeyup="persen_dr();" style="width:100%;" class="text-right" data-nilai="persen_dr" name="persen_dr_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->persen_dr . '" />
											<input type="hidden" class="text-right" data-nilai="persen_dr_real" name="persen_dr_real[' . $tr->id_treatment . '][]" value="' . $tritem->persen_dr . '" /> <!-- Untuk mengetahui bahwa dokter itu memberikan diskon terhadap pasiennya -->
										</div>

										<input type="hidden" class="text-right" style="width:100%;" data-view="tarif_rs" value="' . number_format($harga_rs,0,',','.') . '" readonly>
										<input type="hidden" name="tarif_rs_treatment[' . $tr->id_treatment . '][]" data-nilai="tarif_rs" value="' . $harga_rs . '"/>
										<input type="hidden" name="persen_rs_treatment[' . $tr->id_treatment . '][]" data-nilai="persen_rs" value="' . $tritem->persen_rs . '"/>
										<input type="hidden" name="coa_rs_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->coa_rs . '" />
										<input type="hidden" name="coa_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->id_coa . '" />

										<input type="hidden" name="tipe_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->tipe . '" />

										<div class="input-group">
											<input type="hidden" onkeyup="diskon();" style="width:100%;" data-nilai="diskon" class="text-right" name="diskon_treatment_item[' . $tr->id_treatment . '][]" value="0" />
										</div>

										<input type="hidden" readonly="readonly" value="' . number_format($tarif_dasar,0,',','.') . '" style="width:100%;" class="text-right" data-view="subtotal" />
										<input type="hidden" name="subtotal_treatment[' . $tr->id_treatment . '][]" value="' . $tarif_dasar . '" data-nilai="subtotal"/>
									</td>
								</tr>
							';

						 else:

						 	$out .= '
								<tr>
									<td>' . $nt . '</td>
									<td colspan="2">
										<div>' . $tritem->nm_service  . '</div>
										<small>' . $tritem->grup . ' &raquo; ' . $tritem->tindakan . '</small>
										<input type="hidden" name="nama_treatment_item[' . $tr->id_treatment . '][]" value="' . $tritem->nm_service . '" />
										<input type="hidden" name="id_treatment_item[' . $tr->id_treatment . '][]" value="' . $tritem->id_treatment_item . '" />
									</td>
									<td class="text-right" colspan="4">
										<input type="number" onkeyup="hitung()" name="tarif_dasar_treatment[' . $tr->id_treatment . '][]" data-nilai="tarif_dasar" value="' . $tritem->tarif_dasar . '"/>

										<input type="hidden" class="text-right"  style="width:100%;"readonly data-view="tarif_dr" value="' . number_format($harga_dr,0,',','.') . '" />
										<input type="hidden" name="tarif_dr_treatment[' . $tr->id_treatment . '][]" data-nilai="tarif_dr" value="' . $harga_dr . '" />
										<input type="hidden" name="coa_dr_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->coa_dr . '" />
										<input type="hidden" data-nilai="qty" value="1">

										<div class="input-group">
											<input type="hidden" onkeyup="persen_dr();" style="width:100%;" class="text-right" data-nilai="persen_dr" name="persen_dr_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->persen_dr . '" />
											<input type="hidden" class="text-right" data-nilai="persen_dr_real" name="persen_dr_real[' . $tr->id_treatment . '][]" value="' . $tritem->persen_dr . '" /> <!-- Untuk mengetahui bahwa dokter itu memberikan diskon terhadap pasiennya -->
										</div>

										<input type="hidden" class="text-right" style="width:100%;" data-view="tarif_rs" value="' . number_format($harga_rs,0,',','.') . '" readonly>
										<input type="hidden" name="tarif_rs_treatment[' . $tr->id_treatment . '][]" data-nilai="tarif_rs" value="' . $harga_rs . '"/>
										<input type="hidden" name="persen_rs_treatment[' . $tr->id_treatment . '][]" data-nilai="persen_rs" value="' . $tritem->persen_rs . '"/>
										<input type="hidden" name="coa_rs_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->coa_rs . '" />
										<input type="hidden" name="coa_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->id_coa . '" />

										<input type="hidden" name="tipe_treatment[' . $tr->id_treatment . '][]" value="' . $tritem->tipe . '" />
									</td>
									<td>
										<div class="input-group">
											<input type="text" onkeyup="diskon();" style="width:100%;" data-nilai="diskon" class="text-right" name="diskon_treatment_item[' . $tr->id_treatment . '][]" value="0" />
											<span class="input-group-addon">%</span>
										</div>
									</td>
									<td class="text-right">
										<input type="text" readonly="readonly" value="' . number_format($tarif_dasar,0,',','.') . '" style="width:100%;" class="text-right" data-view="subtotal" />
										<input type="hidden" name="subtotal_treatment[' . $tr->id_treatment . '][]" value="' . $tarif_dasar . '" data-nilai="subtotal"/>
									</td>
								</tr>
							';

						 endif;



						// BHP
						$obats = $tritem->bhp()
							->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
							->join('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
							->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
							->select('data_barang.nm_barang', 'data_barang.id_satuan', 'data_barang.harga_beli','ref_satuan.nm_satuan', 'data_resep_item.*', 'ref_kategori.id_coa', 'ref_kategori.coa_pembelian')
							->get();
						if(count($obats) > 0):
							$out .= '
								<tr>
									<th colspan="5">barang</th>
									<th>Qty</th>
									<th>Total</th>
									<th>Diskon</th>
									<th>Subtotal</th>
								</tr>
							';
						foreach($obats as $obat){

							// Dat yang tidak flat
							if($obat->flat == 0):
								$total += $obat->harga_jual * $obat->qty;
								$urut[] = $obat->harga_jual;
								$out .= '
									<tr>
										<td colspan="5">
											' . $obat->nm_barang . '
											<input type="hidden" name="treatment_item_nm_barang[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->nm_barang . '" />
											<input type="hidden" name="id_bhp[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->id_resep_item . '" />
											<input type="hidden" name="id_barang[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->id_barang . '" />
											<input type="hidden" name="id_satuan_bhp[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->id_satuan . '" />

											<input type="hidden" name="treatment_item_coa_persediaan[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->id_coa . '" />
											<input type="hidden" name="treatment_item_coa_biaya[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->coa_pembelian . '" />
											<input type="hidden" name="treatment_item_coa_pendapatan[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $tritem->coa_pendapatan . '" />
										</td>
										<td class="text-center">' . $obat->qty . ' ' . $obat->nm_satuan . '</td>
										<td class="text-right">
											' . number_format($obat->harga_jual,0,',','.') . '
											<input type="hidden" name="harga_bhp[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->harga_jual . '"/>
											<input type="hidden" name="harga_beli_bhp[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->harga_beli . '"/>
											<input type="hidden" name="qty_bhp[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . $obat->qty . '"/>
										</td>
										<td>
											<div class="input-group">
												<input type="text" onkeyup="diskon();" style="width:100%;" data-nilai="diskon" class="text-right" name="diskon_bhp[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="0" />
												<span class="input-group-addon">%</span>
											</div>
										</td>
										<td class="text-right">
											<input type="hidden" data-nilai="tarif_dasar" value="' . $obat->harga_jual . '"/>

											<input type="text" readonly="readonly" value="' . number_format(( $obat->harga_jual * $obat->qty ),0,',','.') . '" style="width:100%;" class="text-right" data-view="subtotal" />
											<input type="hidden" name="subtotal_bhp[' . $tr->id_treatment . '][' . $tritem->id_treatment_item . '][]" value="' . ( $obat->harga_jual * $obat->qty ) . '" data-nilai="subtotal"/>
											<input type="hidden" data-nilai="persen_dr" value="50"/>
											<input type="hidden" data-nilai="persen_rs" value="50"/>
											<input type="hidden" data-nilai="tarif_dr" value="0" />

											<input type="hidden" data-view="tarif_rs">
											<input type="hidden" data-view="tarif_dr">
											<input type="hidden" data-nilai="qty" value="1">
										</td>
									</tr>
								';
							endif;
							// end data yang tidak flat
						}
						endif;

						$nt++;
 					endforeach; // end items treatment



				$out .= '
									</tbody>
								</table>
							</div>
						</div>
					';


			endforeach; // end treatmet

			else:

				$out .= '<div class="well"><h5>Treatment Tidak ada!</h5></div>';

			endif; // end treatment


			// Mengambil data rawat inap
			$rinaps = data_rawat_inap::loadinvoice($id_rinap['result'])->get();
			if(count($rinaps) > 0):

				foreach($rinaps as $item):

					// Menghitung hari
					$mulai = strtotime($item->daftar_rinap);
					$selesai = empty($item->selesai_rinap) ? time() : strtotime($item->selesai_rinap);
					$selisih = $selesai - $mulai;
					$jam = round(abs($selisih / (60 * 60)));
					$subtotalrinap = 0;
					$perhari = 24;
					// Perhitungan trif di bawah 6 jam
					if($jam < 6){
						$subtotalrinap = ($item->tarif * 50) / 100;
					}else{
						// Perhitungan di atas 6 jam
						if($jam > 24){
							$hari = round($jam / $perhari, 1);
							$split = explode('.', $hari);
							$haritotal = $split[0] * $item->tarif;
							$subtotalrinap = $haritotal;

							// Kelebihan hitungan di bawah 6 jams
							if(!empty($split[1])){
								if($split[1] < 6)
									$subtotalrinap += ($item->tarif * 50) / 100;
								else
									$subtotalrinap += $item->tarif * $split[1];
							}

						}else{
							$subtotalrinap = $item->tarif;
						}
					}

					$selesai_rinap = empty($item->selesai_rinap) ? date('Y-m-d\TH:i') : date('Y-m-d\TH:i', strtotime($item->selesai_rinap));
					$out .= '
						<div class="grid simple rinap-' . $item->id_rinap . '">
							<div class="grid-title text-left no-border">
								 <button type="button" class="close" onclick="hapus_pake(\'rinap-' . $item->id_rinap . '\');"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 style="margin:0;"><strong><i>#' . $item->kode_kamar . '</i></strong></h4>
								<div><span class="text-muted">' . $item->nm_kamar . '</span></div>
							</div>
							<div class="grid-body no-border">
								<input type="hidden" name="id_rinap[]" value="' . $item->id_rinap . '" />
								<input type="hidden" name="id_kamar[]" value="' . $item->id_kamar . '" />
								<input type="hidden" name="nm_kamar[]" value="' . $item->nm_kamar . '" />
								<table class="table table-bordered">
									<thead>
										<tr>
											<th width="15%">Tanggal masuk</th>
											<th width="15%">Tanggal Keluar</th>
											<th width="15%" class="text-right">Tarif / Hari</th>
											<th width="15%" class="text-center">Waktu Sewa</th>
											<th width="20%" class="text-center">Diskon</th>
											<th width="20%" class="text-right">Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><input name="check_in[]" onchange="update_rinap_in_out(' . $item->id_rinap . ')" data-cekin="' . $item->id_rinap . '" style="width:100%;" value="' . date('Y-m-d\TH:i', strtotime($item->daftar_rinap)) . '" type="datetime-local" name="daftar_rinap" /></td>
											<td><input name="check_out[]" onchange="update_rinap_in_out(' . $item->id_rinap . ')" data-cekout="' . $item->id_rinap . '" style="width:100%;" value="' . $selesai_rinap . '" type="datetime-local" name="selesai_rinap" /></td>
											<td class="text-right">' . number_format($item->tarif,0,',','.') . '</td>
											<td class="text-center">' . $jam . ' jam</td>
											<td>
												<div class="input-group">
													<input type="text" onkeyup="diskon();" style="width:100%;" data-nilai="diskon" class="text-right" name="diskon_rinap[]" value="0" />
													<span class="input-group-addon">%</span>
												</div>
											</td>
											<td>
												<input type="hidden" data-nilai="tarif_dasar" value="' . $subtotalrinap . '">
												<input type="hidden" data-nilai="persen_dr" value="50">
												<input type="hidden" data-nilai="persen_rs" value="50">
												<input type="hidden" data-nilai="tarif_dr" value="0">
												<input type="hidden" name="tarif_kamar[]" data-nilai="subtotal" value="' . $subtotalrinap . '">
												<input style="width:100%;" type="text" class="text-right" readonly data-view="subtotal" value="' . number_format($subtotalrinap,0,',','.') . '">

												<input type="hidden" name="tarif_dasar_rinap[]" value="' . $item->tarif . '">
												<input type="hidden" name="id_layanan[]" value="' . $item->id_layanan_rs . '">
												<input type="hidden" name="total_sewa[]" value="' . $jam . '">

												<input type="hidden" data-view="tarif_rs">
												<input type="hidden" data-view="tarif_dr">
												<input type="hidden" data-nilai="qty" value="1">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					';
					$total += $subtotalrinap;
					$urut[] = $subtotalrinap;
				endforeach;




			endif;



			$res['content'] = $out;
			$res['total'] = 'RP ' . number_format($total,0,',','.');
			$res['grandtotal'] = $total;
			$res['bilang'] = ucfirst(\Format::terbilang($total)) . ' rupiah';
			$res['urut'] = $urut;
			$res['unverified'] = $unverified;
			$res['verified'] = $verified;
			return response()->json($res);

		}
	}


	public function postUpdateinoutrinap(Request $req){
		if($req->ajax()){
			$res = [];

			$in = date('Y-m-d H:i:s', strtotime($req->in));
			$cekout = date('Y-m-d H:i:s', strtotime($req->out));

			data_rawat_inap_pakai::where('id_rinap', $req->id_rinap)
				->update([
					'daftar_rinap' => $in,
					'selesai_rinap' => $cekout
				]);


			// Mengambil data rawat inap
			$item = data_rawat_inap::ambil($req->id_rinap)->first();


			// Menghitung hari
			$mulai = strtotime($item->daftar_rinap);
			$selesai = empty($item->selesai_rinap) ? time() : strtotime($item->selesai_rinap);
			$selisih = $selesai - $mulai;
			$jam = round(abs($selisih / (60 * 60)));
			$subtotalrinap = 0;
			$perhari = 24;
			// Perhitungan trif di bawah 6 jam
			if($jam < 6){
				$subtotalrinap = ($item->tarif * 50) / 100;
			}else{
				// Perhitungan di atas 6 jam
				if($jam > 24){
					$hari = round($jam / $perhari, 1);
					$split = explode('.', $hari);
					$haritotal = $split[0] * $item->tarif;
					$subtotalrinap = $haritotal;

					// Kelebihan hitungan di bawah 6 jams
					if(!empty($split[1])){
						if($split[1] < 6)
							$subtotalrinap += ($item->tarif * 50) / 100;
						else
							$subtotalrinap += $item->tarif * $split[1];
					}

				}else{
					$subtotalrinap = $item->tarif;
				}
			}
			$out = '';
			$selesai_rinap = empty($item->selesai_rinap) ? date('Y-m-d\TH:i') : date('Y-m-d\TH:i', strtotime($item->selesai_rinap));
			$out .= '
				<div class="grid-title text-left no-border">
					 <button type="button" class="close" onclick="hapus_pake(\'rinap-' . $item->id_rinap . '\');"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 style="margin:0;"><strong><i>#' . $item->kode_kamar . '</i></strong></h4>
					<div><span class="text-muted">' . $item->nm_kamar . '</span></div>
				</div>
				<div class="grid-body no-border">
					<input type="hidden" name="id_rinap[]" value="' . $item->id_rinap . '" />
					<input type="hidden" name="id_kamar[]" value="' . $item->id_kamar . '" />
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="15%">Tanggal masuk</th>
								<th width="15%">Tanggal Keluar</th>
								<th width="15%" class="text-right">Tarif / Hari</th>
								<th width="15%" class="text-center">Waktu Sewa</th>
								<th width="20%" class="text-center">Diskon</th>
								<th width="20%" class="text-right">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input name="check_in[]" onchange="update_rinap_in_out(' . $item->id_rinap . ')" data-cekin="' . $item->id_rinap . '" style="width:100%;" value="' . date('Y-m-d\TH:i', strtotime($item->daftar_rinap)) . '" type="datetime-local" name="daftar_rinap" /></td>
								<td><input name="check_out[]" onchange="update_rinap_in_out(' . $item->id_rinap . ')" data-cekout="' . $item->id_rinap . '" style="width:100%;" value="' . $selesai_rinap . '" type="datetime-local" name="selesai_rinap" /></td>
								<td class="text-right">' . number_format($item->tarif,0,',','.') . '</td>
								<td class="text-center">' . $jam . ' jam</td>
								<td>
									<div class="input-group">
										<input type="text" onkeyup="diskon();" style="width:100%;" data-nilai="diskon" class="text-right" name="diskon_rinap[]" value="0" />
										<span class="input-group-addon">%</span>
									</div>
								</td>
								<td>
									<input type="hidden" data-nilai="tarif_dasar" value="' . $subtotalrinap . '">
									<input type="hidden" data-nilai="persen_dr" value="50">
									<input type="hidden" data-nilai="persen_rs" value="50">
									<input type="hidden" data-nilai="tarif_dr" value="0">
									<input type="hidden" name="tarif_kamar[]" data-nilai="subtotal" value="' . $subtotalrinap . '">
									<input style="width:100%;" type="text" class="text-right" readonly data-view="subtotal" value="' . number_format($subtotalrinap,0,',','.') . '">

									<input type="hidden" name="tarif_dasar_rinap[]" value="' . $item->tarif . '">
									<input type="hidden" name="id_layanan[]" value="' . $item->id_layanan_rs . '">
									<input type="hidden" name="total_sewa[]" value="' . $jam . '">

									<input type="hidden" data-view="tarif_rs">
									<input type="hidden" data-view="tarif_dr">
									<input type="hidden" data-nilai="qty" value="1">

								</td>
							</tr>
						</tbody>
					</table>
				</div>
			';

			$res['out']  = $out;
			$res['param'] = $req->all();
			return response()->json($res);

		}
	}


	public function getListpasien(){

		$data['items'] = data_log_pasien::pasiens()->paginate(10);
		$data['status'] = [
			1 => 'Belum Lunas',
			2 => "Lunas"
		];
		return view('Biling.pasiens', $data);
	}

	public function getAnypasiens(Request $req){
		if($req->ajax()):

			$res = [];
			$out = '';
			$status = [
				1 => 'Belum Lunas',
				2 => "Faktur Selesai"
			];
			$items = data_log_pasien::pasiens($req->all())->paginate($req->limit);
			$no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1;

			foreach($items as $item):
				if($item->status == 1)
					$bayar = '<td><a href="' . url('/biling/create/' . $item->id_pasien) . '" class="btn btn-white btn-md">Bayar</a></td>';
				else
					$bayar = '';
				$out .= '
					<tr>
						<td>' . $no . '</td>
						<td>' . $item->nama_pasien . '</td>
						<td>' . \Format::indoDate2($item->waktu_transaksi) . '</td>
						<td>' . $status[$item->status] . '</td>
						<td>' . $bayar . '<td>
					</tr>
				';
				$no++;
			endforeach;

			$res['items'] = $out;
			$res['total'] = $items->total();
			$res['pagin'] = $items->render();
			return response()->json($res);

		endif;
	}


	public function postPaymentmethod(Request $req){

		$err = $this->dispatch(new PaymentMethodJobs($req->all()));
		return	redirect()->back()->withNotif([
					'label' => $err['label'],
					'err' => $err['err']
			]);

	}

	public function postDelpaymentmethod(Request $req){
		if($req->ajax()){

			data_jurnal_pembayaran::find($req->id)->delete();

			return response()->json([
				'result' => true
			]);

		}
	}


	public function getPayment($id = 0){
		if(empty($id))
			return redirect('/biling');

		$data['biling'] = data_faktur::biling($id)->first();
		$data['tambahan'] = data_faktur_item::biling($id)->get();
		$data['reseps'] = data_faktur_pasien::resep($id)->get();
		$data['treatments'] = data_faktur_pasien::treatment($id)->get();
		$data['rinaps'] = data_faktur_pasien::rinaps($id)->get();
		$data['banks'] = ref_bank::all();
		$data['asuransi'] = ref_asuransi::all();
		$data['payments'] = data_jurnal_pembayaran::where('id_faktur', $id)->get();
		$data['total_payment'] = 0;
		$data['accounts'] = ref_coa::where('cash', 1)->get();

		return view('Biling.payment', $data);
	}

	// public function postPayment(Request $req){

	// 	$err = $this->dispatch(new InputCoaJob($req->all()));

	// 	return redirect()->back()->withNotif([
	// 		'label' => $err['label'],
	// 		'err' => $err['err']
	// 	]);

	// }


	public function getLaporan(){

		return view('Biling.Laporan.Index');
	}

	public function getLoadlaporan(Request $req){
		if($req->ajax()):

			$res = [];
			$out = '';
			$no = 1;
			$laporan = data_voucer_jurnal::laporanbiling($req->all())->get();

			foreach($laporan as $lap){
				$out .= '
					<tr>
						<td>' . $no . '</td>
						<td>'  . \Format::indoDate2($lap->tanggal) . '</td>
						<td colspan="5">' . $lap->keterangan . '</td>
					</tr>
				';

				$jus = data_jurnal::laporanbiling($lap->id_voucer_jurnal, $req->all())->get();
				foreach($jus as $ju){
					$out .= '
						<tr>
							<td colspan="2"></td>
							<td>' . $ju->kode . '</td>
							<td>' . $ju->nm_coa . '</td>
							<td>'  . $ju->deskripsi . '</td>
							<td class="text-right">'  . number_format($ju->debit,0,',','.') . '</td>
							<td class="text-right">'  . number_format($ju->kredit,0,',','.') . '</td>
						</tr>
					';
				}


				$no++;
			}


			$res['total'] = count($laporan);
			$res['content'] = $out;

			return response()->json($res);

		endif;
	}

	public function getPrintlaporan(Request $req){
		$res = [];
			$out = '';
			$no = 1;
			$laporan = data_voucer_jurnal::laporanbiling($req->all())->get();

			foreach($laporan as $lap){
				$out .= '
					<tr>
						<td align="center">' . $no . '</td>
						<td>'  . date('d/m/Y', strtotime($lap->tanggal)) . '</td>
						<td colspan="5">' . $lap->keterangan . '</td>
					</tr>
				';

				$jus = data_jurnal::laporanbiling($lap->id_voucer_jurnal, $req->all())->get();
				foreach($jus as $ju){

					$spasi = $ju->kredit > 0 ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '';
					$out .= '
						<tr>
							<td colspan="2"></td>
							<td align="right">' . $ju->kode . '</td>
							<td>' . $spasi . $ju->nm_coa . '</td>
							<td>' . $spasi . $ju->deskripsi . '</td>
							<td class="text-right">'  . number_format($ju->debit,0,',','.') . '</td>
							<td class="text-right">'  . number_format($ju->kredit,0,',','.') . '</td>
						</tr>
					';
				}


				$no++;
			}

			$header = '';
			switch($req->jurnal){
				case "1":
					if($req->id_vendor > 0){
						$vendor = data_vendor::find($req->id_vendor);
						$header = 'SUPPLIER ' . $vendor->nm_vendor;
					}else{
						$header = 'SEMUA SUPPLIER';
					}
				break;

				case "2":
					$pasien = data_pasien::where('id_pasien_hc', $req->id_pasien)->first();
						$header = empty($pasien->nama_pasien) ? 'SEMUA PASIEN' : 'PASIEN ' . $pasien->nama_pasien;
				break;

				case "3":
					$coa = ref_coa::find($req->id_coa);
						$header = 'AKUN ' . strtoupper($coa->nm_coa);
				break;
			}


			$data['total'] = count($laporan);
			$data['content'] = $out;
			$data['req'] = $req;
			$data['header'] = $header;

		return view('Biling.Laporan.Print', $data);
	}

	public function postEdit(Request $req){
		$err = $this->dispatch(new editBilingJob($req->all()));
		return redirect()->back()->withNotif([
			'label' => $err['label'],
			'err' => $err['err']
		]);
	}


	public function postPayment(Request $req){
		$err = $this->dispatch(new getPaymentJob($req->all()));

			return redirect()->back()->withNotif([
				'label' => $err['label'],
				'err' => $err['err']
			]);

	}

	public function getTipemethod(Request $req){

		if($req->ajax()){
			$out = '';
			$items = ref_payment_method_item::where('id_payment_method', $req->id_payment_method)->get();
			foreach($items as $item){
				$out .= '<option value="' . $item->id_payment_method_item . '">' . $item->keterangan . '</option>';
			}

			return response()->json([
				'content' => $out,
				'req' => $req->all()
			]);
		}
	}

	public function getConfig(){

		$coas = [];
		foreach(ref_coa::orderby('kode', 'asc')->get() as $coa){
			$coas[$coa->parent_id][] = $coa;
		}

		$config     = Config::active()->first();
		$deposit    = configdep::active()->first();
		$loan       = configloan::active()->first();
		$pembelian  = configpembelian::active()->first();
		$pendapatan = configpendapatan::active()->first();
		// dd($deposit);

		$data['coa_sebelum_dibayar'] 	= \Format::select_coa($coas, 0, $config->coa_sebelum_dibayar);
		$data['coa_adjustment'] 		= \Format::select_coa($coas, 0, $config->coa_adjustment);
		$data['coa_item_tambahan'] 		= \Format::select_coa($coas, 0, $config->coa_item_tambahan);
		$data['coa_rawat_inap'] 		= \Format::select_coa($coas, 0, $config->coa_rawat_inap);
		$data['coa_saldo'] 				= \Format::select_coa($coas, 0, $config->coa_saldo);
		$data['coa_pendapatan_resep'] 	= \Format::select_coa($coas, 0, $config->coa_pendapatan_resep);

		$data['coa_deposit']         = \Format::select_coa($coas, 0, $deposit->coa_deposit);
		$data['coa_pembayaran_cash'] = \Format::select_coa($coas, 0, $deposit->coa_pembayaran_cash);

		$data['coa_loan']         = \Format::select_coa($coas, 0, $loan->coa_loan);
		$data['coa_pembayaran_cash_loan'] = \Format::select_coa($coas, 0, $loan->coa_pembayaran_cash);
		//pembelian
		$data['coa_ppn_pembelian']                    = \Format::select_coa($coas, 0, $pembelian->coa_ppn);
		$data['coa_adjustment_pembelian']             = \Format::select_coa($coas, 0, $pembelian->coa_adjustment);
		$data['coa_jumlah_sebelum_dibayar_pembelian'] = \Format::select_coa($coas, 0, $pembelian->coa_jumlah_sebelum_dibayar);
		$data['coa_penambahan_item_pembelian']        = \Format::select_coa($coas, 0, $pembelian->coa_penambahan_item);
		$data['coa_pembayaran_cash_pembelian']        = \Format::select_coa($coas, 0, $pembelian->coa_pembayaran_cash);
		// pendapatan
		$data['coa_pendapatan_lainnya']         = \Format::select_coa($coas, 0, $pendapatan->coa_pendapatan_lainnya);
		$data['coa_adjustment_pendapatan']      = \Format::select_coa($coas, 0, $pendapatan->coa_adjustment);
		$data['coa_piutang']                    = \Format::select_coa($coas, 0, $pendapatan->coa_piutang);
		$data['coa_ppn_pendapatan']             = \Format::select_coa($coas, 0, $pendapatan->coa_ppn);
		$data['coa_pembayaran_cash_pendapatan'] = \Format::select_coa($coas, 0, $pendapatan->coa_pembayaran_cash);

		return view('Biling.Config', $data);
	}

	public function postConfig(Request $req){

		$err = $this->dispatch(new UpdateConfigurasiJob($req->all()));
		return redirect()->back()->withNotif([
			'label' => $err['label'],
			'err' => $err['err']
		]);

	}
	public function postDeposit(Request $req){
		// dd($req->all());
		$err = $this->dispatch(new UpdateConfigurasiDeposit($req->all()));
        return redirect('/biling/config')->withNotif([
			'label' => $err['label'],
			'err' => $err['err']
		]);
	}
 	public function postLoan(Request $req){
		$err = $this->dispatch(new UpdateconfigCoaLoanJob($req->all()));
        return redirect('/biling/config')->withNotif([
			'label' => $err['label'],
			'err' => $err['err']
		]);
	}
	public function postPembelian(Request $req){
		$err = $this->dispatch(new UpdateconfigCoaPembelianJob($req->all()));
        return redirect('/biling/config')->withNotif([
			'label' => $err['label'],
			'err' => $err['err']
		]);
	}
	public function postPendapatan(Request $req){
		$err = $this->dispatch(new UpdateconfigCoaPendapatanJob($req->all()));
        return redirect('/biling/config')->withNotif([
			'label' => $err['label'],
			'err' => $err['err']
		]);
	}


	public function getVendors(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '<select name="id_vendor" style="width:100%;">';
			$out .= '<option value="0">- Semua Supplier -</option>';
			$items = data_vendor::listbiling()->get();
			foreach($items as $item){
				$out .= '<option value="' . $item->id_vendor . '">' . $item->nm_vendor . '</option>';
			}
			$out .= '</select>';
			$res['content'] = $out;
			return response()->json($res);

		}
	}


	public function getSelectpasien(Request $req){
		if($req->ajax()){
			$res = [];
			$out = '<select name="id_pasien" style="width:100%;">';
			$out .= '<option value="0">- Semua Pasien -</option>';
			$items = data_pasien::listbiling()->get();
			foreach($items as $item){
				$out .= '<option value="' . $item->id_pasien_hc . '">' . $item->nama_pasien . '</option>';
			}
			$out .= '</select>';
			$res['content'] = $out;
			return response()->json($res);

		}
	}

	public function getSelectakun(Request $req){
		if($req->ajax()){
			$res = [];

			$coas = [];
			foreach(ref_coa::orderby('seri', 'asc')->get() as $coa){
				$coas[$coa->parent_id][] = $coa;
			}

			$options = \Format::select_coa($coas);

			$out = '<select name="id_coa" style="width:100%;">';
			$out .= $options;
			$out .= '</select>';

			$res['content'] = $out;
			return response()->json($res);
		}
	}

	public function postValidasi(Request $req){
	    if($req->ajax()){
	        $tukar = data_log_pasien::where('id_pasien', $req->id);
	        $tukar->update([
						'status_validasi' => 1,
						'user_validasi'   => \Me::data()->id_karyawan,
						'waktu_validasi'  => date('Y-m-d H:i:s')
	        ]);

	        \Loguser::create('Anda Berhasil Validasi Tagihan Pasien. ' );
	    }
    }

		public function getLaporanshift(Request $req){
			$headers = view_laporan_shift::src($req->all())->groupby('keterangan')->get();
			$items = view_laporan_shift::src($req->all())->get();
			$kasir = data_shift_kasir::bytangal($req->all())->get();

			$data['shifts'] = [
				1 => 'Kassa 1',
				2 => 'Kassa 2',
				3 => 'Kassa 3',
				4 => 'Kassa 4'
			];
			$data['status'] = [
				0 => 'Aktif',
				1 => 'Selesai',
			];

			$res = [];
			foreach($items as $item){
				$res[$item->keterangan][] = $item;
			}
			$data['kasir'] = $kasir;
			$data['header'] = $headers;
			$data['items'] = $res;
			$data['req'] = $req->all();
			// $data['link'] = $req ? url('/biling/laporanshiftprint?tanggal=' . $req->tanggal . '&dari=' . $req->dari . '&sampai=' . $req->sampai . '&id_shift_kasir=' . $req->id_shift_kasir) : '';

			$data['link'] = $req ? url('/biling/laporanshiftprint?tanggal=' . $req->tanggal . '&dari=' . $req->dari . '&sampai=' . $req->sampai . '&id_shift_kasir=' . $req->id_shift_kasir . '&shift=' . $req->shift) : '';
			//dd($data);
			return view("Biling.Laporan.LaporanShift", $data);
		}

		public function getLaporanshiftprint(Request $req){
			$headers = view_laporan_shift::src($req->all())->groupby('keterangan')->get();
			$items = view_laporan_shift::src($req->all())->get();
			$kasir = data_shift_kasir::bytangal($req->all())->get();

			$data['shifts'] = [
				1 => 'Kassa 1',
				2 => 'Kassa 2',
				3 => 'Kassa 3',
				4 => 'Kassa 4'
			];
			$data['status'] = [
				0 => 'Aktif',
				1 => 'Selesai',
			];

			$res = [];
			foreach($items as $item){
				$res[$item->keterangan][] = $item;
			}
			$data['kasir'] = $kasir;
			$data['header'] = $headers;
			$data['items'] = $res;
			$data['req'] = $req->all();
			//dd($data);
			return view("Biling.Laporan.LaporanShiftPrint", $data);
		}


		public function getOvershift($id = 0){
			$me = \Me::data();
			$level = \Me::level();
			$items = data_shift_kasir::items()->paginate(10);
			$data['shifts'] = [
				1 => 'Kassa 1',
				2 => 'Kassa 2',
				3 => 'Kassa 3',
				4 => 'Kassa 4'
			];
			$data['status'] = [
				0 => 'Aktif',
				1 => 'Selesai',
			];
			$user = (object) [
				'id_shift_kasir' => 0,
				'name' => $me->nm_depan . ' ' . $me->nm_belakang,
				'id_karyawan' => $me->id_karyawan,
				'shift' => '',
				'status' => 0,
				'saldo_awal' => 0,
				'saldo_kembali' => 0,
				'pendapatan_kassa' => 0
			];

			if(!empty($id)){
					$user = data_shift_kasir::user($id)->first();
					$kar = data_karyawan::find($user->id_karyawan);
					$user['name'] = $kar->nm_depan . ' ' . $kar->nm_belakang;
					if($user == NULL)
						return redirect('/biling/overshift')->withNotif([
							'label' => 'danger',
							'err' => 'Data tidak ditemukan'
						]);
			}

			$data['me'] = $me;
			$data['level'] = $level;
			$data['items'] 	= $items;
			$data['user'] 	= $user;
			return view('Biling.Overshift.index', $data);
		}

		public function postOvershift(Request $req){
			$id = $req->id_shift_kasir;
			unset($req['_token']);
			if($req->id_shift_kasir < 1){
				unset($req['id_shift_kasir']);
				$new = data_shift_kasir::create($req->all());
				$id = $new->id_shift_kasir;
			}else{
				data_shift_kasir::find($req->id_shift_kasir)->update($req->all());
			}
			return redirect('/biling/overshift/' . $id)->withNotif([
				'label' => 'success',
				'err' => 'Berhasil tersimpan'
			]);

		}

}

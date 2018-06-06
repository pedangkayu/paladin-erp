@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/biling/edit.js') }}"></script>
@endsection

@section('title')
	#{{ $biling->nomor_faktur }}
@endsection

@section('content')
	
	<form method="post" action="">
		{{ csrf_field() }}
		<input type="hidden" name="id_faktur" value="{{ $biling->id_faktur }}">

		<div class="row">
			<!-- left -->
			<div class="col-sm-9">

					<!-- daftar tambahan -->
					@if(count($tambahan) > 0)
					<div class="grid simple">
						<div class="grid-title no-border">
							<h4>Tambahan</h4>
						</div>
						<div class="grid-body no-border">
							<table class="table table-striped">
							<thead>
								<tr>
									<th width="40%">Uraian</th>
									<th width="15%">Qty</th>
									<th width="15%">Biaya</th>
									<th width="15%">Diskon</th>
									<th width="15%">Jumlah</th>
								</tr>
							</thead>
							<tbody>
								@foreach($tambahan as $tambah)
									<tr>
										<td>{{ $tambah->deskripsi }}</td>
										<td>{{ $tambah->qty }}</td>
										<td>{{ $tambah->harga }}</td>
										<td>{{ $tambah->diskon }}%</td>
										<td>{{ $tambah->total }}</td>
									</tr>
								@endforeach	
							</tbody>
						</table>
						</div>
					</div>
					@endif

					<!-- daftar resep -->
					@forelse($reseps as $resep)
						<div class="grid simple">
							<div class="grid-title no-border">
								<h4>#{{ $resep->nomor_resep }}</h4>
							</div>
							<div class="grid-body no-border">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="5%">No.</th>
											<th width="35%">Uraian</th>
											<th class="text-right" width="15%">Jumlah</th>
											<th class="text-right" width="15%">Biaya</th>
											<th class="text-right" width="15%">Diskon %</th>
											<th class="text-right" width="15%">Total</th>
										</tr>
									</thead>

									<tbody>
										<?php
											$items = App\Models\data_faktur_pasien::itemresep($resep->id_resep)->get();
											$no = 1;
											$u = 1;
										?>
										@foreach($items as $item)
											@if($item->id_barang > 0)
												<tr>
													<td>{{ $no }}</td>
													<td>
														{{ $item->nm_barang }}
														<input type="hidden" name="id_faktur_pasien[]" value="{{ $item->id_faktur_pasien }}">
														<input type="hidden" value="0" name="tarif_dasar_faktur_pasien[]">
														
														<input type="hidden" name="total_sewa_faktur_pasien[]" value="0">
														<input type="hidden" name="tarif_dasar_rinap_faktur_pasien[]" value="0">
														<input type="hidden" name="diskon_rinap_faktur_pasien[]" value="0">
														<input type="hidden" name="tarif_kamar_faktur_pasien[]" value="0">

														<input type="hidden" name="tipe_jurnal_pendapatan[]" value="9">
														<input type="hidden" name="tipe_jurnal_persediaan[]" value="10">
														<input type="hidden" name="tipe_jurnal_hpp[]" value="11">

														<input type="hidden" name="harga_beli_item[]" value="{{ $item->harga_beli }}">
														
													</td>
													<td>
														<input data-nilai="qty" type="number" value="{{ $item->qty }}" name="qty_faktur_pasien[]" class="form-control text-right">
														<span>{{ $item->nm_satuan }}</span>
													</td>
													<td>
														<input type="number" data-nilai="total" value="{{ $item->harga_jual }}" class="form-control text-right" name="biaya_faktur_pasien[]" />
													</td>
													<td class="text-right">
														<input type="number" data-nilai="diskon" value="{{ $item->diskon }}" class="form-control text-right" name="diskon_faktur_pasien[]" />
													</td>
													<td class="text-right">
														<input type="number" data-nilai="subtotal" value="{{ $item->subtotal }}" class="text-right form-control" name="subtotal_faktur_pasien[]" />
													</td>
												</tr>
											@endif

											@if($item->id_barang < 1)
												<tr>
													<td colspan="6">
														<h4><strong>Obat Campur {{ $u }}</strong></h4>
													</td>
												</tr>
												<?php
													$campurs = $item->campur_bhp()
														->join('data_barang', 'data_barang.id_barang', '=','data_faktur_pasien_item.id_barang')
														->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_faktur_pasien_item.id_satuan')
														->join('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
														->select(
															'data_faktur_pasien_item.id_faktur_pasien_item',
															'data_barang.nm_barang',
															'data_barang.harga_beli',
															'data_faktur_pasien_item.qty',
															'ref_satuan.nm_satuan',
															'data_faktur_pasien_item.harga',
															'data_faktur_pasien_item.diskon',
															'data_faktur_pasien_item.subtotal',
															'ref_kategori.coa_pembelian as id_coa'
														)
														->get();
														$c = 1;
												 ?>
												@foreach($campurs as $campur)
													<tr>
														<td>{{ $c }}</td>
														<td>
															{{ $campur->nm_barang }}
															<input type="hidden" name="id_faktur_pasien_item[]" value="{{ $campur->id_faktur_pasien_item }}">
															
															<input type="hidden" name="tipe_jurnal_pendapatan[]" value="25">
															<input type="hidden" name="tipe_jurnal_persediaan[]" value="26">
															<input type="hidden" name="tipe_jurnal_hpp[]" value="27">
															
															<input type="hidden" name="harga_beli_item[]" value="{{ $campur->harga_beli }}">
															
														</td>
														<td class="text-right">
															<input type="number" data-nilai="qty" value="{{ $campur->qty }}" name="qty_faktur_pasien_campur[]" class="form-control text-right">
															<span>{{ $campur->nm_satuan }}</span>
														</td>
														<td class="text-right">
															<input type="number" data-nilai="total" value="{{ $campur->harga }}" class="form-control text-right" name="biaya_faktur_pasien_campur[]" />
														</td>
														<td class="text-right">
															<input type="number" data-nilai="diskon" value="{{ $campur->diskon }}" class="form-control text-right" name="diskon_faktur_pasien_campur[]" />
														</td>
														<td class="text-right">
															<input type="number" data-nilai="subtotal" value="{{ $campur->subtotal }}" class="text-right form-control" name="total_faktur_pasien_campur[]" />
														</td>
													</tr>
													<?php $c++; ?>
												@endforeach
											@endif

											<?php $no++; $u++; ?>
										@endforeach
									</tbody>
								</table>	

							</div>
						</div>
					@empty
						<!-- Tidak ditemukan Resep -->
					@endforelse
					<!-- End daftar resep -->


					<!-- daftar Treatment -->
					@forelse($treatments as $treat)
						<div class="grid simple">
							<div class="grid-title no-border">
								<h4>No. #{{ $treat->nomor_treatment }}</h4>
							</div>
							<div class="grid-body no-border">
								
							<table class="table table-striped">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th width="20%">Uraian</th>
										<th width="15%" colspan="3">Tarif Dasar</th>
										<!-- <th width="15%">Tarif Dokter</th>
										<th width="15%">Tarif RS</th> -->
										<th width="15%">Diskon Dokter</th>
										<th width="15%">Subtotal</th>
									</tr>
								</thead>

								<tbody>
										<?php
											$items = App\Models\data_faktur_pasien::itemtreatments($treat->id_treatment)->get();
											$no = 1;
										?>
										@foreach($items as $item)
											<tr>
												<td>{{ $no }}</td>
												<td {!! ($item->tipe == 2) ? '' : "colspan=\"6\"" !!}>
													<div>{{ $item->nm_service }}</div>
													<small>{{  $item->grup }} &raquo; {{ $item->tindakan }}</small>
												</td>
												@if($item->tipe == 2)
													<td colspan="3">
														<input type="number" data-nilai="total" value="{{ $item->tarif_dasar }}" name="tarif_dasar_faktur_pasien[]" class="form-control text-right">
														<input type="hidden" name="id_faktur_pasien[]" value="{{ $item->id_faktur_pasien }}">

														<input type="hidden" data-nilai="qty" value="1" name="qty_faktur_pasien[]">

														<input type="hidden" name="total_sewa_faktur_pasien[]" value="0">
														<input type="hidden" name="tarif_dasar_rinap_faktur_pasien[]" value="0">
														<input type="hidden" name="diskon_rinap_faktur_pasien[]" value="0">
														<input type="hidden" name="tarif_kamar_faktur_pasien[]" value="0">


														<input type="hidden" name="tipe_jurnal_pendapatan[]" value="14"> <!-- Grand total pendapatan -->
														<input type="hidden" name="tipe_jurnal_persediaan[]" value="12"> <!-- Pendapatan Dokter -->
														<input type="hidden" name="tipe_jurnal_hpp[]" value="13"> <!-- pendapatan Ruamsakit -->

													</td>
													<td class="text-right">
														<input type="number" data-nilai="diskon" value="{{ $item->diskon }}" class="form-control text-right" name="diskon_faktur_pasien[]" />
														<input type="hidden" value="0" name="biaya_faktur_pasien[]">
													</td>
													<td class="text-right">
														<input type="number" data-nilai="subtotal" value="{{ $item->subtotal }}" class="text-right form-control" name="subtotal_faktur_pasien[]" />
													</td>
												@else
													<input type="hidden" data-nilai="qty" value="1">
													<input type="hidden" data-nilai="total" value="0">
													<input type="hidden" data-nilai="diskon" value="0">
													<input type="hidden" data-nilai="subtotal" value="0">
												@endif
											</tr>
											<?php $no++; ?>

											<?php
												$bhps = $item->campur_bhp()
													->join('data_barang', 'data_barang.id_barang', '=','data_faktur_pasien_item.id_barang')
													->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_faktur_pasien_item.id_satuan')
													->join('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
													->select(
														'data_faktur_pasien_item.id_faktur_pasien_item',
														'data_barang.nm_barang',
														'data_barang.harga_beli',
														'data_faktur_pasien_item.qty',
														'ref_satuan.nm_satuan',
														'data_faktur_pasien_item.harga',
														'data_faktur_pasien_item.diskon',
														'data_faktur_pasien_item.subtotal',
														'ref_kategori.coa_pembelian as id_coa'
													)
													->get();
											?>

											@if(count($bhps) > 0)
												<tr>
													<th colspan="3">barang</th>
													<th class="text-right">Qty</th>
													<th class="text-right">Total</th>
													<th class="text-right">Diskon</th>
													<th class="text-right">Subtotal</th>
												</tr>
												
												@foreach($bhps as $bhp)
													<tr>
														<td colspan="3">
															{{ $bhp->nm_barang }}
															<input type="hidden" name="id_faktur_pasien_item[]" value="{{ $bhp->id_faktur_pasien_item }}">

															<input type="hidden" name="tipe_jurnal_pendapatan[]" value="17">
															<input type="hidden" name="tipe_jurnal_persediaan[]" value="15">
															<input type="hidden" name="tipe_jurnal_hpp[]" value="16">

															<input type="hidden" name="harga_beli_item[]" value="{{ $bhp->harga_beli }}">

														</td>
														<td>
															<input data-nilai="qty" type="number" value="{{ $bhp->qty }}" name="qty_faktur_pasien_campur[]" class="form-control text-right">
															<span>{{ $bhp->nm_satuan }}</span>
														</td>
														<td>
															<input data-nilai="total" type="number"  value="{{ $bhp->harga }}" class="form-control text-right" name="biaya_faktur_pasien_campur[]" />
														</td>
														<td>
															<input type="number" data-nilai="diskon" value="{{ $bhp->diskon }}" class="form-control text-right" name="diskon_faktur_pasien_campur[]" />
														</td>
														<td>
															<input type="number" data-nilai="subtotal" value="{{ $bhp->subtotal }}" class="text-right form-control" name="total_faktur_pasien_campur[]" />
														</td>
													</tr>
												@endforeach

											@endif

										@endforeach
									</tbody>

							</table>

							</div>
						</div>
					@empty
						<!-- Tidak ditemukan Treatment -->
					@endforelse
					<!-- End daftar Treatment -->


					@forelse($rinaps as $rinap)
						<div class="grid simple">
							<div class="grid-title no-border">
								<h4>{{ $rinap->nm_kamar }}</h4>
								<div><span>Kode Kamar #{{ $rinap->kode_kamar }}</span></div>
							</div>
							<div class="grid-body no-border">
								
							<table class="table table-striped">
								<thead>
									<tr>
										<th width="20%">Tanggal Masuk</th>
										<th width="15%">Tanggal Keluar</th>
										<th width="15%">Sewa Kamar</th>
										<th width="15%">Tarif / kamar</th>
										<th width="15%">Diskon</th>
										<th width="15%">Subtotal</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td>
											{{ Format::indoDate2($rinap->check_in) }} {{ Format::jam($rinap->check_in) }}
											<input type="hidden" name="id_faktur_pasien[]" value="{{ $rinap->id_faktur_pasien }}">
											<input type="hidden" value="0" name="tarif_dasar_faktur_pasien[]">
											<input type="hidden" data-nilai="qty" value="0">
											
											<input type="hidden" value="0" name="qty_faktur_pasien[]">
											<input type="hidden" value="0" name="biaya_faktur_pasien[]" />
											<input type="hidden" value="0" name="diskon_faktur_pasien[]" />
											<input type="hidden" value="0" name="subtotal_faktur_pasien[]" />

											<input type="hidden" name="tipe_jurnal_pendapatan[]" value="28"> <!-- Pendapatan Rinap -->
											<input type="hidden" name="tipe_jurnal_persediaan[]" value="0">
											<input type="hidden" name="tipe_jurnal_hpp[]" value="0">

										</td>
										<td>{{ Format::indoDate2($rinap->check_out) }} {{ Format::jam($rinap->check_out) }}</td>
										<td>
											{{ $rinap->total_sewa }} jam
											<input type="hidden" name="total_sewa_faktur_pasien[]" value="{{ $rinap->total_sewa }}">
										</td>
										<td>
											{{ number_format($rinap->tarif_dasar_rinap,0,',','.') }}
											<input type="number" name="tarif_dasar_rinap_faktur_pasien[]" data-nilai="total"  value="{{ $rinap->tarif_dasar_rinap }}">
										</td>
										<td>
											{{ number_format($rinap->diskon,0,',','.') }}
											<input type="number" data-nilai="diskon" name="diskon_rinap_faktur_pasien[]" value="{{ $rinap->diskon }}">
										</td>
										<td>
											{{ number_format($rinap->tarif_kamar,0,',','.') }}
											<input type="number" name="tarif_kamar_faktur_pasien[]" data-nilai="subtotal" value="{{ $rinap->tarif_kamar }}">
										</td>
									</tr>
								</tbody>

							</table>

							</div>
						</div>
					@empty

					@endforelse

			</div>

			<!-- right -->
			<div class="col-sm-3">
				
				<div class="grid simple">
					<div class="grid-title no-border">
						<div class="status-faktur">
							<span class="label label-{{ $status[$biling->status]['label'] }}">
								{{ $status[$biling->status]['err'] }}
							</span>
						</div>
					</div>
					<div class="grid-body no-border">
						<h3>{{ $biling->nama_pasien }}</h3>
						<address>
							<strong>Alamat Pasien</strong>
							<p>{{ $biling->alamat_pasien }}</p>
						</address>
						
						<div class="form-group">

							<div class="btn-group" role="group" style="width:100%;">
								<a style="width:50%;" target="_blank" class="btn btn-primary" href="{{ url('/biling/print/' . $biling->id_faktur) }}?paper=a5"><i class="fa fa-print"></i> Print A5</a>
								<a style="width:50%;" target="_blank" class="btn btn-primary" href="{{ url('/biling/print/' . $biling->id_faktur) }}?paper=a4"><i class="fa fa-print"></i> Print A4</a>
							</div>

						</div>


					</div>
				</div>

				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<label for="terms">Payment Terms *</label>
									<select name="terms" id="terms" class="form-control">
										@foreach($terms as $term)
										<option value="{{ $term->id_payment_terms }}" {!! $biling->id_payment_terms == $term->id_payment_terms ? 'selected="selected"' : '' !!}>{{ $term->payment_terms }}</option>
										@endforeach
									</select>
								</div>
								
								<div class="form-group">
									<label>Prefix</label>
									<input type="text" name="prefix" class="form-control" value="{{ $biling->prefix }}">
								</div>

								<div class="form-group">
									<label for="duodate">Tanggal Jatuh Tempo *</label>
									<div class="input-group transparent">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" value="{{ date('m/d/Y', strtotime($biling->duodate)) }}" name="duodate" id="duodate" class="form-control" readonly="readonly">
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border text-right">
						
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>+/- Penyesuaian</label>
									<input type="number" value="{{ number_format($biling->adjustment,0,'','') }}" class="form-control text-right" name="adjustment">
								</div>
							</div>
						</div>

						<address>
							<strong>Subtotal</strong>
							<h4 class="view-subtotal">{{ number_format($biling->subtotal,0,',','.') }}</h4>
							<strong>Total</strong>
							<h2 class="view-grandtotal">{{ number_format($biling->total,0,',','.') }}</h2>

							<input type="hidden" name="subtotal" value="{{ $biling->subtotal }}">
							<input type="hidden" name="grandtotal" value="{{ $biling->total }}">

						</address>

					</div>
				</div>

				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<button type="submit" class="btn btn-primary btn-block btn-update">Perbaharui</button>
						<button type="button" class="btn btn-primary btn-block btn-hitung hide">Hitung Ulang</button>
						<a class="btn btn-primary btn-block" href="{{ url('/biling') }}">List Biling</a>
					</div>
				</div>

			</div>
			
		</div>

	</form>

@endsection
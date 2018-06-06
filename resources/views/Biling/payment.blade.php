@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/biling/view.js') }}"></script>
@endsection

@section('title')
	#{{ $biling->nomor_faktur }}
@endsection

@section('content')
	<form method="post" action="">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="id_faktur" value="{{ $biling->id_faktur }}">
		<div class="row">
			<!-- left -->
			<div class="col-sm-8">

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
										<td>
											{{ $tambah->total }}
											<input type="hidden" name="id_coa[]" value="262 ">
											<input type="hidden" name="total[]" value="{{ $tambah->total }}">
											<input type="hidden" name="keterangan[]" value="Pembelian {{ $tambah->deskripsi }} dari invoice #{{ $biling->nomor_faktur }}">
											<input type="hidden" name="tipe[]" value="2">
										</td>
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
											<th class="text-right" width="15%">Diskon</th>
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
													<td>{{ $item->nm_barang }}</td>
													<td class="text-right">{{ $item->qty }} {{ $item->nm_satuan }}</td>
													<td class="text-right">{{ number_format($item->harga_jual,0,',','.') }}</td>
													<td class="text-right">{{ $item->diskon }}%</td>
													<td class="text-right">
														{{ number_format($item->subtotal),0,',','.' }}
														<input type="hidden" name="id_coa[]" value="{{ $item->id_coa }}">
														<input type="hidden" name="total[]" value="{{ $item->subtotal }}">
														<input type="hidden" name="keterangan[]" value="Pembelian {{ $item->nm_barang }} dari invoice #{{ $biling->nomor_faktur }}">
														<input type="hidden" name="tipe[]" value="2">
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
														->leftJoin('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
														->select(
															'data_barang.nm_barang',
															'data_faktur_pasien_item.qty',
															'ref_satuan.nm_satuan',
															'data_faktur_pasien_item.harga',
															'data_faktur_pasien_item.diskon',
															'data_faktur_pasien_item.subtotal',
															'ref_kategori.id_coa'
														)
														->get();
														$c = 1;
												 ?>
												@foreach($campurs as $campur)
													<tr>
														<td>{{ $c }}</td>
														<td>{{ $campur->nm_barang }}</td>
														<td class="text-right">{{ $campur->qty }} {{ $campur->nm_satuan }}</td>
														<td class="text-right">{{ number_format($campur->harga,0,',','.') }}</td>
														<td class="text-right">{{ $campur->diskon }}%</td>
														<td class="text-right">
															{{ number_format($campur->subtotal),0,',','.' }}
															<input type="hidden" name="id_coa[]" value="{{ $campur->id_coa }}">
															<input type="hidden" name="total[]" value="{{ $campur->subtotal }}">
															<input type="hidden" name="keterangan[]" value="Pembelian {{ $campur->nm_barang }} dari invoice #{{ $biling->nomor_faktur }}">
															<input type="hidden" name="tipe[]" value="2">
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
													<td class="text-right" colspan="3">{{ number_format($item->tarif_dasar,0,',','.') }}</td>
													<!-- <td class="text-right">{{ number_format($item->tarif_dr,0,',','.') }}</td>
													<td class="text-right">{{ number_format($item->tarif_rs,0,',','.') }}</td> -->
													<td class="text-right">{{ $item->diskon }}%</td>
													<td class="text-right">
														{{ number_format($item->subtotal,0,',','.') }}
														<input type="hidden" name="id_coa[]" value="{{ $item->coa }}">
														<input type="hidden" name="total[]" value="{{ $item->subtotal }}">
														<input type="hidden" name="keterangan[]" value="Pembelian {{ $item->nm_service }} dari invoice #{{ $biling->nomor_faktur }}">
														<input type="hidden" name="tipe[]" value="2">


														<input type="hidden" name="id_coa[]" value="{{ $item->coa_rs }}">
														<input type="hidden" name="total[]" value="{{ $item->tarif_rs }}">
														<input type="hidden" name="keterangan[]" value="tarif RS dari invoice #{{ $biling->nomor_faktur }}">
														<input type="hidden" name="tipe[]" value="2">

														<input type="hidden" name="id_coa[]" value="{{ $item->coa_dr }}">
														<input type="hidden" name="total[]" value="{{ $item->tarif_dr }}">
														<input type="hidden" name="keterangan[]" value="tarif Dokter dari invoice #{{ $biling->nomor_faktur }}">
														<input type="hidden" name="tipe[]" value="2">
													</td>
												@endif
											</tr>
											<?php $no++; ?>

											<?php
												$bhps = $item->campur_bhp()
													->join('data_barang', 'data_barang.id_barang', '=','data_faktur_pasien_item.id_barang')
													->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_faktur_pasien_item.id_satuan')
													->leftJoin('ref_kategori', 'ref_kategori.id_kategori', '=', 'data_barang.id_kategori')
													->select(
														'data_barang.nm_barang',
														'data_faktur_pasien_item.qty',
														'ref_satuan.nm_satuan',
														'data_faktur_pasien_item.harga',
														'data_faktur_pasien_item.diskon',
														'data_faktur_pasien_item.subtotal',
														'ref_kategori.id_coa'
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
														<td colspan="3">{{ $bhp->nm_barang }}</td>
														<td class="text-right">{{ $bhp->qty }} {{ $bhp->nm_satuan }}</td>
														<td class="text-right">{{ number_format($bhp->harga,0,',','.') }}</td>
														<td class="text-right">{{ number_format($bhp->diskon,0,',','.') }}</td>
														<td class="text-right">
															{{ number_format($bhp->subtotal,0,',','.') }}
															<input type="hidden" name="id_coa[]" value="{{ $bhp->id_coa }}">
															<input type="hidden" name="total[]" value="{{ $bhp->subtotal }}">
															<input type="hidden" name="keterangan[]" value="Pembelian {{ $bhp->nm_barang }} dari invoice #{{ $biling->nomor_faktur }}">
															<input type="hidden" name="tipe[]" value="2">
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
										<td>{{ Format::indoDate2($rinap->check_in) }} {{ Format::jam($rinap->check_in) }}</td>
										<td>{{ Format::indoDate2($rinap->check_out) }} {{ Format::jam($rinap->check_out) }}</td>
										<td>{{ $rinap->total_sewa }} jam</td>
										<td>{{ number_format($rinap->tarif_dasar_rinap,0,',','.') }}</td>
										<td>{{ number_format($rinap->diskon,0,',','.') }}%</td>
										<td>
											{{ number_format($rinap->tarif_kamar,0,',','.') }}
											<input type="hidden" name="id_coa[]" value="116">
											<input type="hidden" name="total[]" value="{{ $rinap->tarif_kamar }}">
											<input type="hidden" name="keterangan[]" value="Tarif kamar dari invoice #{{ $biling->nomor_faktur }}">
											<input type="hidden" name="tipe[]" value="2">
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
			<div class="col-sm-4">
				
				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<h3>{{ $biling->nama_pasien }}</h3>
						<address>
							<strong>Alamat Pasien</strong>
							<p>{{ $biling->alamat_pasien }}</p>
						</address>
						<hr />
						
							@if($biling->payment_status_pembayaran == 2)

							<div class="form-group">
								<label>Tanggal *</label>
								<input type="date" class="form-control text-right" name="tanggal" value="{{ date('Y-m-d') }}" required>
							</div>

							<div class="form-group">
								<label>Akun Bank *</label>
								<select style="width:100%;" name="id_coa[]" required>
									<option value="">-Pilih Akun-</option>
									@foreach($accounts as $akun)
										<option value="{{ $akun->id_coa }}">{{ $akun->nm_coa }}</option>
									@endforeach
								</select>
							</div>

							<div class="form-group">
								<label>Total</label>
								<input type="text" class="form-control text-right" readonly="readonly" value="{{ number_format($biling->total,0,'','') }}">
								<input type="hidden" name="total[]" value="{{ $biling->total }}">
								<input type="hidden" name="tipe[]" value="1">

							</div>

							<div class="form-group">
								<label>Deskripsi *</label>
								<textarea class="form-control" name="keterangan[]" rows="5">Pendapatan dari faktur No #{{ $biling->nomor_faktur }}</textarea>
							</div>

							

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Add Payment</button>
							</div>

							@endif

						

					</div>
				</div>

				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border text-right">
						
						<div class="row">
							<div class="col-sm-6">
								<address>
									<strong>Diskon</strong>
									<p>{{ $biling->diskon }}%</p>
								</address>
							</div>
							<div class="col-sm-6">
								<address>
									<strong>+/- Penyesuaian</strong>
									<p>{{ number_format($biling->adjustment,0,',','.') }}</p>

									<input type="hidden" name="id_coa[]" value="272">
									<input type="hidden" name="total[]" value="{{ $biling->adjustment }}">
									<input type="hidden" name="keterangan[]" value="Adjustemt #{{ $biling->nomor_faktur }}">
									<input type="hidden" name="tipe[]" value="{{ $biling->adjustment > 0 ? 1 : 2 }}">

								</address>
							</div>
						</div>

						<address>
							<strong>Subtotal</strong>
							<h4>{{ number_format($biling->subtotal,0,',','.') }}</h4>
							<strong>Total</strong>
							<h2>{{ number_format($biling->total,0,',','.') }}</h2>

						</address>

					</div>
				</div>


				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<a class="btn btn-primary btn-block" href="{{ url('/biling/create') }}">Buat Biling</a>
						<a class="btn btn-primary btn-block" href="{{ url('/biling') }}">List Biling</a>
					</div>
				</div>

			</div>
			
		</div>
	</form>	
@endsection
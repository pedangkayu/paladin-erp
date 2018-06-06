@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/biling/view.js') }}"></script>
	<style>
		.datepicker{z-index:1151 !important;}
	</style>
@endsection

@section('title')
	#{{ $biling->nomor_faktur }}
@endsection

@section('content')
	
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
												<td class="text-right">{{ number_format($item->subtotal),0,',','.' }}</td>
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
													->select(
														'data_barang.nm_barang',
														'data_faktur_pasien_item.qty',
														'ref_satuan.nm_satuan',
														'data_faktur_pasien_item.harga',
														'data_faktur_pasien_item.diskon',
														'data_faktur_pasien_item.subtotal'
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
													<td class="text-right">{{ number_format($campur->subtotal),0,',','.' }}</td>
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
												<td class="text-right">{{ number_format($item->subtotal,0,',','.') }}</td>
											@endif
										</tr>
										<?php $no++; ?>

										<?php
											$bhps = $item->campur_bhp()
												->join('data_barang', 'data_barang.id_barang', '=','data_faktur_pasien_item.id_barang')
												->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_faktur_pasien_item.id_satuan')
												->select(
													'data_barang.nm_barang',
													'data_faktur_pasien_item.qty',
													'ref_satuan.nm_satuan',
													'data_faktur_pasien_item.harga',
													'data_faktur_pasien_item.diskon',
													'data_faktur_pasien_item.subtotal'
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
													<td class="text-right">{{ number_format($bhp->subtotal,0,',','.') }}</td>
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
									<td>{{ number_format($rinap->tarif_kamar,0,',','.') }}</td>
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
				<div class="grid-title no-border">
					<div class="status-faktur">
                        <div style="float: left; width: 25%;">
						    <span class="label label-{{ $status[$biling->status]['label'] }}">
							    {{ $status[$biling->status]['err'] }}
						    </span>
                        </div>
                        <div style="float: left; width: 75%;">
 						        @if($unverified < 1)
							     <span class="label label-warning verified"><i class="fa fa-check-circle"></i> Data Pembayaran Sudah Valid</span>
						        @else
							     <span class="label label-important"><i class="fa fa-ban"></i> Data Pembayaran Belum Valid</span>
						        @endif
                        </div>
                        <div style="clear: both;"></div>
					</div>
				</div>

				<div class="grid-body no-border">


					<h4>{{ $biling->nama_pasien }}</h4>
					<address>
						<p>{{ $biling->alamat_pasien }}</p>
						 <div class="grid-title no-border"></div>
					</address>
					
					@if($unverified > 0)
					<div class="form-group">
						<a href="javascript:void(0);" class="btn btn-danger btn-block" onclick="validasi();">Verifikasi</a>
					</div>
					@endif

					<div class="form-group">
						<div class="btn-group" role="group" style="width:100%;">
							<a style="width:50%;" target="_blank" class="btn btn-primary" href="{{ url('/biling/print/' . $biling->id_faktur) }}?paper=a5"><i class="fa fa-print"></i> Print A5</a>
							<a style="width:50%;" target="_blank" class="btn btn-primary" href="{{ url('/biling/print/' . $biling->id_faktur) }}?paper=a4"><i class="fa fa-print"></i> Print A4</a>
						</div>
					</div>

					<div class="form-group">
						<a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#payment">Payment</a>
					</div>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<div class="row">
						<div class="col-sm-6">
							<address>
								<strong>Payment Terms</strong>
								<p>{{ $biling->payment_terms }}</p>
								<strong>Tanggal</strong>
								<p>{{ Format::indoDate2($biling->tgl_faktur) }}</p>
							</address>	
						</div>
						<div class="col-sm-6">
							<address>
								<strong>Prefix</strong>
								<p>{{ empty($biling->prefix) ? '-' : $biling->prefix }}</p>
								<strong>Jatuh Tempo</strong>
								<p>{{ Format::indoDate2($biling->duodate) }}</p>
							</address>
						</div>
					</div>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>SISA SALDO DEPOSIT</h4>
				</div>
				<div class="grid-body no-border text-right">
					<h2>Rp. {{ number_format($deposit,0,',','.') }}</h2>
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
							</address>
						</div>
					</div>

					<address>
						<strong>Subtotal</strong>
						<h4>{{ number_format($biling->subtotal,0,',','.') }}</h4>
						<strong>Total</strong>
						<h2>{{ number_format($biling->total,0,',','.') }}</h2>
						<strong>Amount Due</strong>
						<h4>{{ number_format($biling->amount_due,0,',','.') }}</h4>

						<input type="hidden" name="grandtotal" value="{{ ($biling->total - $biling->amount_due) }}">
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

@endsection

@section('footer')
	
	<!-- Modal -->
	<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<form method="post" action="{{ url('/biling/payment') }}" id="submit_payment"> <!--  Form submit -->

		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Payment</h4>
		      </div>
		      <div class="modal-body">
		        
		      		<div class="grid simple">
						<div class="grid-title no-border"></div>
						<div class="grid-body no-border">
							
							<div class="row">
								<div class="col-sm-4">
									<strong>Tagihan</strong>
									<h4>RP {{ number_format($biling->total,0,',','.') }}</h4>
								</div>
								<div class="col-sm-4 text-right">
									<strong>Terbayar</strong>
									<h4>RP {{ number_format($biling->amount_due,0,',','.') }}</h4>
								</div>

								<div class="col-sm-4 text-right">
									<strong>Sisa</strong>
									<h4>RP {{ number_format(($biling->total - $biling->amount_due),0,',','.') }}</h4>
								</div>
							</div>

						</div>
					</div>


			      	<div class="grid simple">
						<div class="grid-title no-border"></div>
						<div class="grid-body no-border">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="tanggal">Tanggal</label>
									     <input type="text" class="form-control tgl" name="tanggal" value="{{ date('m/d/Y') }}" id="tanggal" readonly="readonly">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="duodate">Jatuh Tempo</label>
									     <input type="text" class="form-control tgl" name="duodate" value="{{ date('m/d/Y' , strtotime('+1 Month')) }}" id="duodate" readonly="readonly">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="keterangan">Keterangan</label>
								<input type="text" name="keterangan" class="form-control" value="Pembayaran faktur #{{ $biling->nomor_faktur }}">
							</div>

							@if($deposit > 0)
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<div class="checkbox checkbox check-success"> <!-- <a href="#">Trouble login in?</a>&nbsp;&nbsp; -->
										  <input type="checkbox" id="dgn_saldo" name="dgn_saldo" value="1">
										  <label for="dgn_saldo">Bayar dengan <b>Saldo Deposit</b></label>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group dgn_saldo hide">
										<input type="number" name="saldo" value="{{ $deposit }}" class="form-control text-right">
										
									</div>
								</div>
							</div>
							<div class="dgn_saldo hide">
								<small>* Silakan sesuaikan dengan sisa tagihan, jika kurang tambahkan dengan <b>Metode Pembayaran</b> di bawah</small>
							</div>
							@endif

							<table class="table">
								<tbody class="item-payment"></tbody>
							</table>

							<div class="form-group">
								<button type="button" class="btn btn-primary btn-block" onclick="item_pyment();">Tambahkan Metode Pembayaran</button>
							</div>

						</div>
					</div>

					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id_faktur" value="{{ $biling->id_faktur }}">
					<input type="hidden" name="saldo_akhir" value="{{ $deposit }}">
					<input type="hidden" name="id_pasien" value="{{ $biling->id_pasien }}">
					<input type="hidden" name="total" value="{{ $biling->total }}">
					<input type="hidden" name="id_deposit" value="{{ $id_deposit }}">
				

		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary">Payment</button>
		      </div>

	    </form> <!-- end Form submit -->

	    </div>
	  </div>
	</div>

	<template id="tmp-payments">
		<option value="">-Pilih-</option>
		@foreach($jenis_bayar as $payment)
		<option value="{{ $payment->id_payment_method }}">{{ $payment->payment_method }}</option>
		@endforeach
	</template>

@endsection
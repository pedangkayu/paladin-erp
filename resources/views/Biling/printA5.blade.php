<!DOCTYPE html>
<html>
<head>
	<title>#{{ $biling->nomor_faktur }}</title>
	<style type="text/css">
		body{
			margin:0;
		}
		.area-print{
			margin-top: 3cm;
			height: 16cm;
			/*border: solid 1px #ddd;*/
			width: 13cm;
			overflow: hidden;
			font-family: "sans-serif", arial, tahoma;
			font-size: 10pt;
			position: relative;
		}
		.table{
			border: solid 1px #000;
			padding: 0;
		}
		.table tr td{
			border-left: solid 1px #000;
			/*border-bottom: solid 1px #000;*/
			padding: 5px;
		}
		.table tr th{
			border-bottom: solid 1px #000;
			border-top: solid 1px #000;
			padding: 3px;
		}

		.area-print .footer{
			position: absolute;
			bottom: 1cm;
		}
	</style>
</head>
<body>

	<!-- Summary -->
	<section class="area-print">
				<center>
					<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
					<div>#{{ $biling->nomor_faktur }}</div>
				</center>
				<br />
				<table width="97%">
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td>{{ $biling->nama_pasien }}</td>
					</tr>
					<tr valign="top">
						<td>Alamat</td>
						<td>:</td>
						<td>{{ $biling->alamat_pasien }}</td>
					</tr>
				</table>

				<table width="100%" class="table" cellpadding="0" cellspacing="0">
					<tr>
						<th>No. KUITANSI PEMBAYARAN</th>
						<th>Tanggal</th>
						<!-- <th align="right">Total</th> -->
					</tr>
					<?php $gt = 0; ?>
					@foreach($reseps as $resep)

						<tr>
							<td>#{{ $resep->nomor_resep }}</td>
							<td>{{ Format::indoDate2($resep->tgl_input) }}</td>
							<!-- <td align="right">{{ number_format($resep->grand_total,0,',','.') }}</td> -->
						</tr>
						<?php $gt += $resep->grand_total; ?>
					@endforeach


					@foreach($treatments as $tr)

						<tr>
							<td>#{{ $tr->nomor_treatment }}</td>
							<td>{{ Format::indoDate2($tr->tgl_input) }}</td>
							<!-- <td align="right">{{ number_format($tr->grand_total,0,',','.') }}</td> -->
						</tr>
						<?php $gt += $tr->grand_total; ?>
					@endforeach


					@foreach($rinaps as $rinap)
						<tr>
							<td>#{{ $rinap->kode_kamar }} ({{ $rinap->nm_kamar }})</td>
							<td>{{ Format::indoDate2($rinap->check_in) }}</td>
							<!-- <td align="right">{{ number_format($rinap->tarif_kamar,0,',','.') }}</td> -->
						</tr>
						<?php $gt += $tr->grand_total; ?>
					@endforeach

				</table>


				<br />
					<table width="97%">
						<tr>
							<td width="60%">
								Terbilang : <div>{{ Format::terbilang($biling->total) }} rupiah</div>
							</td>
							<td width="30%">
								<table width="100%">
									<tr>
										<td>Total</td>
										<td>:</td>
										<td align="right"><h4>{{ number_format($biling->total,0,',','.') }}</h4></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					<div class="footer">
						<table>
							<tr>
								<td valign="top">
									Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
									<p>{{ $halaman }}</p>
								</td>
								<td>
									<table>
										<tr>
											<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
										</tr>
										<tr>
											<td>
												<p>&nbsp;</p>
											</td>
										</tr>
										<tr>
											<td align="center">
												(................................................)
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				

			</section>
	<!-- end summary -->

	<!-- tambahan -->

	@if(count($tambahan) > 0)
			<section class="area-print">
				<center>
					<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
					<div>#{{ $biling->nomor_faktur }}</div>
				</center>
				<br />
				<table width="97%">
					<tr valign="top">
						<td>
							<table>
								<tr>
									<td>Pasien</td>
									<td>:</td>
									<td>{{ $biling->nama_pasien }}</td>
								</tr>
								<tr valign="top">
									<td colspan="3">
										Alamat
										<div>{{ $biling->alamat_pasien }}</div>
									</td>
								</tr>
							</table>
						</td>
						<td>
							
						</td>
					</tr>
				</table>

				<table class="table" cellpadding="0" cellspacing="0" width="100%">
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
						<?php $total_tambahan = 0; ?>
						@foreach($tambahan as $tambah)
							<tr>
								<td>{{ $tambah->deskripsi }}</td>
								<td>{{ $tambah->qty }}</td>
								<td>{{ $tambah->harga }}</td>
								<td>{{ $tambah->diskon }}%</td>
								<td>{{ number_format($tambah->total,0,',','.') }}</td>
							</tr>
							<?php $total_tambahan += $tambah->total; ?>
						@endforeach	
					</tbody>
				</table>
					<!-- <br />
					<table width="97%">
						<tr>
							<td width="60%">
								Terbilang : <div>{{ Format::terbilang($total_tambahan) }}</div>
							</td>
							<td width="30%">
								<table width="100%">
									<tr>
										<td>Total</td>
										<td>:</td>
										<td align="right"><h4>{{ number_format($total_tambahan,0,',','.') }}</h4></td>
									</tr>
								</table>
							</td>
						</tr>
					</table> -->

					<div class="footer">
						<table>
							<tr>
								<td valign="top">
									Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
									<p>{{ $halaman }}</p>
								</td>
								<td>
									<table>
										<tr>
											<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
										</tr>
										<tr>
											<td>
												<p>&nbsp;</p>
											</td>
										</tr>
										<tr>
											<td align="center">
												(................................................)
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</section>


	@endif

	<!-- end tambahan -->

	@if(count($reseps) > 0)
			<section class="area-print">
				<center>
					<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
					<div>#{{ $biling->nomor_faktur }}</div>
				</center>
				<br />
				<table width="97%">
					<tr valign="top">
						<td>
							<table>
								<tr>
									<td>Pasien</td>
									<td>:</td>
									<td>{{ $biling->nama_pasien }}</td>
								</tr>
								<tr valign="top">
									<td colspan="3">
										Alamat
										<div>{{ $biling->alamat_pasien }}</div>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table>
								<tr>
									<td>No Resep</td>
									<td>:</td>
									<td>{{ $first_resep->nomor_resep }}</td>
								</tr>
								<tr>
									<td>Tanggal</td>
									<td>:</td>
									<td>{{ Format::hari($first_resep->created_at) }}, {{ Format::indoDate($first_resep->created_at) }}</td>
								</tr>
								<tr>
									<td>Dokter</td>
									<td>:</td>
									<td>{{ $first_resep->nm_depan }} {{ $first_resep->nm_belakang }}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table class="table" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th width="5%">No.</th>
							<th width="35%">Uraian</th>
							<th align="right" width="15%">Jumlah</th>
							<!-- <th align="right" width="15%">Biaya</th>
							<th align="right" width="15%">Diskon</th> -->
							<th align="right" width="15%">Total</th>
						</tr>
					</thead>
					<tbody>


		@foreach($reseps as $resep)
			<?php $halaman++; ?>
			<?php
				$items = App\Models\data_faktur_pasien::itemresep($resep->id_resep)->get();
				$no = 1;
				$urut = 1;
			?>
			@foreach($items as $item)
				@if($item->id_barang > 0)
					<tr>
						<td>{{ $no }}</td>
						<td>{{ $item->nm_barang }}</td>
						<td align="right">{{ $item->qty }} {{ $item->nm_satuan }}</td>
						<!-- <td align="right">{{ number_format($item->harga_jual,0,',','.') }}</td>
						<td align="right">{{ $item->diskon }}%</td> -->
						<td align="right">{{ number_format($item->subtotal),0,',','.' }}</td>
						<?php $total_resep += $item->subtotal; ?>
					</tr>
				@endif
				<?php $u = 1; ?>
				@if($item->id_barang < 1)
					<tr>
						<th colspan="6">
							<strong>Obat Campur {{ $u }}</strong>
						</th>
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


					 @if($urut == $perhalaman)
					 	</tbody>
							</table>
							<br />
							<!-- <table width="97%">
								<tr>
									<td width="60%">
										Terbilang : <div>{{ Format::terbilang($total_resep) }}</div>
									</td>
									<td width="30%">
										<table width="100%">
											<tr>
												<td>Total</td>
												<td>:</td>
												<td align="right"><h4>{{ number_format($total_resep,0,',','.') }}</h4></td>
											</tr>
										</table>
									</td>
								</tr>
							</table> -->

							<div class="footer">
								<table>
									<tr>
										<td valign="top">
											Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
											<p>{{ $halaman }}</p>
										</td>
										<td>
											<table>
												<tr>
													<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
												</tr>
												<tr>
													<td>
														<p>&nbsp;</p>
													</td>
												</tr>
												<tr>
													<td align="center">
														(................................................)
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</section>


						<section class="area-print">
							<center>
								<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
								<div>#{{ $biling->nomor_faktur }}</div>
							</center>
							<br />
							<table width="97%">
								<tr valign="top">
									<td>
										<table>
											<tr>
												<td>Pasien</td>
												<td>:</td>
												<td>{{ $biling->nama_pasien }}</td>
											</tr>
											<tr valign="top">
												<td colspan="3">
													Alamat
													<div>{{ $biling->alamat_pasien }}</div>
												</td>
											</tr>
										</table>
									</td>
									<td>
										<table>
											<tr>
												<td>No Resep</td>
												<td>:</td>
												<td>{{ $resep->nomor_resep }}</td>
											</tr>											
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td>{{ Format::hari($resep->created_at) }}, {{ Format::indoDate($resep->created_at) }}</td>
											</tr>
											<tr>
												<td>Dokter</td>
												<td>:</td>
												<td>{{ $resep->nm_depan }} {{ $resep->nm_belakang }}</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<table class="table" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th width="5%">No.</th>
										<th width="35%">Uraian</th>
										<th align="right" width="15%">Jumlah</th>
										<!-- <th align="right" width="15%">Biaya</th>
										<th align="right" width="15%">Diskon</th> -->
										<th align="right" width="15%">Total</th>
									</tr>
								</thead>
								<tbody>
								<?php $urut = 1; ?>
								<?php $total_resep = 0; ?>
								<?php $halaman++; ?>
					 @endif



					@foreach($campurs as $campur)
						<tr>
							<td>{{ $c }}</td>
							<td>{{ $campur->nm_barang }}</td>
							<td align="right">{{ $campur->qty }} {{ $campur->nm_satuan }}</td>
							<!-- <td align="right">{{ number_format($campur->harga,0,',','.') }}</td>
							<td align="right">{{ $campur->diskon }}%</td> -->
							<td align="right">{{ number_format($campur->subtotal),0,',','.' }}</td>
							<?php $total_resep += $campur->subtotal; ?>
						</tr>
						<?php $c++; ?>

						@if($urut == $perhalaman)
					 	</tbody>
							</table>
							<br />
							<!-- <table width="97%">
								<tr>
									<td width="60%">
										Terbilang : <div>{{ Format::terbilang($total_resep) }}</div>
									</td>
									<td width="30%">
										<table width="100%">
											<tr>
												<td>Total</td>
												<td>:</td>
												<td align="right"><h4>{{ number_format($total_resep,0,',','.') }}</h4></td>
											</tr>
										</table>
									</td>
								</tr>
							</table> -->

							<div class="footer">
								<table>
									<tr>
										<td valign="top">
											Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
											<p>{{ $halaman }}</p>
										</td>
										<td>
											<table>
												<tr>
													<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
												</tr>
												<tr>
													<td>
														<p>&nbsp;</p>
													</td>
												</tr>
												<tr>
													<td align="center">
														(................................................)
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</section>


						<section class="area-print">
							<center>
								<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
								<div>#{{ $biling->nomor_faktur }}</div>
							</center>
							<br />
							<table width="97%">
								<tr valign="top">
									<td>
										<table>
											<tr>
												<td>Pasien</td>
												<td>:</td>
												<td>{{ $biling->nama_pasien }}</td>
											</tr>
											<tr valign="top">
												<td colspan="3">
													Alamat
													<div>{{ $biling->alamat_pasien }}</div>
												</td>
											</tr>
										</table>
									</td>
									<td>
										<table>
											<tr>
												<td>No Resep</td>
												<td>:</td>
												<td>{{ $resep->nomor_resep }}</td>
											</tr>
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td>{{ Format::hari($resep->created_at) }}, {{ Format::indoDate($resep->created_at) }}</td>
											</tr>
											<tr>
												<td>Dokter</td>
												<td>:</td>
												<td>{{ $resep->nm_depan }} {{ $resep->nm_belakang }}</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<table class="table" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th width="5%">No.</th>
										<th width="35%">Uraian</th>
										<th align="right" width="15%">Jumlah</th>
										<!-- <th align="right" width="15%">Biaya</th>
										<th align="right" width="15%">Diskon</th> -->
										<th align="right" width="15%">Total</th>
									</tr>
								</thead>
								<tbody>
								<?php $urut = 1; ?>
								<?php $total_resep = 0; ?>
								<?php $halaman++; ?>
					 	@endif

					 	<?php $urut++; ?>
					@endforeach
				@endif

				<?php $no++; $u++; $urut++;?>
			@endforeach
					

		@endforeach

			</tbody>
					</table>
					<br />
					<!-- <table width="97%">
						<tr>
							<td width="60%">
								Terbilang : <div>{{ Format::terbilang($total_resep) }}</div>
							</td>
							<td width="30%">
								<table width="100%">
									<tr>
										<td>Total</td>
										<td>:</td>
										<td align="right"><h4>{{ number_format($total_resep,0,',','.') }}</h4></td>
									</tr>
								</table>
							</td>
						</tr>
					</table> -->

					<div class="footer">
						<table>
							<tr>
								<td valign="top">
									Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
									<p>{{ $halaman }}</p>
								</td>
								<td>
									<table>
										<tr>
											<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
										</tr>
										<tr>
											<td>
												<p>&nbsp;</p>
											</td>
										</tr>
										<tr>
											<td align="center">
												(................................................)
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</section>


	@endif


		<!-- treatment -->

		@if(count($treatments) > 0)

		<section class="area-print">
			<center>
				<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
				<div>#{{ $biling->nomor_faktur }}</div>
			</center>
			<br />
			<table width="97%">
				<tr valign="top">
					<td>
						<table>
							<tr>
								<td>Pasien</td>
								<td>:</td>
								<td>{{ $biling->nama_pasien }}</td>
							</tr>
							<tr valign="top">
								<td colspan="3">
									Alamat
									<div>{{ $biling->alamat_pasien }}</div>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>No Treatment</td>
								<td>:</td>
								<td>{{ $first_treatment->nomor_treatment }}</td>
							</tr>
							<tr>
								<td>Tanggal</td>
								<td>:</td>
								<td>{{ Format::hari($first_treatment->created_at) }}, {{ Format::indoDate($treatments[0]->created_at) }}</td>
							</tr>
							<tr>
								<td>Dokter</td>
								<td>:</td>
								<td>{{ Me::dokter($first_treatment->id_dokter) }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th colspan="2" width="50%">Uraian</th>
						<th width="15%">Tarif Dasar</th>
						<!-- <th width="15%">Tarif Dokter</th>
						<th width="15%">Tarif RS</th> -->
						<!-- <th width="15%">Diskon</th> -->
						<th width="15%">Subtotal</th>
					</tr>
				</thead>

				<tbody>
		<?php $total_resep = 0; ?>
		@foreach($treatments as $treat)
				<?php $halaman++; ?>
				<?php
					$items = App\Models\data_faktur_pasien::itemtreatments($treat->id_treatment)->get();
					$no = 1;
					$urut = 1;
				?>
				@foreach($items as $item)
					<tr>
						<td>{{ $no }}</td>
						<td  {!! ($item->tipe == 2) ? 'colspan="2"' : "colspan=\"4\"" !!}>
							<div>{{ $item->nm_service }}</div>
						</td>
						@if($item->tipe == 2)
						<td align="right">{{ number_format($item->tarif_dasar,0,',','.') }}</td>
						<!-- <td align="right">{{ number_format($item->tarif_dr,0,',','.') }}</td>
						<td align="right">{{ number_format($item->tarif_rs,0,',','.') }}</td> -->
						<!-- <td align="right">{{ $item->diskon }}%</td> -->
						<td align="right">{{ number_format($item->subtotal,0,',','.') }}</td>
						@endif
					</tr>
					<?php 
						$no++;

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

							$total_resep += $item->subtotal;
					?>

					@if(count($bhps) > 0)
						<tr>
							<th></th>
							<th>barang</th>
							<th align="right">Qty</th>
							<th align="right">Total</th>
							<!-- <th align="right">Diskon</th> -->
							<th align="right">Subtotal</th>
						</tr>
						<?php $nn = 1; ?>
						@foreach($bhps as $bhp)
							<tr>
								<td {!! (count($bhps) == $nn) ? 'style="border-bottom:1px solid #000;"' : '' !!}>{{ ($no - 1) }}.{{ $nn }}</td>
								<td {!! (count($bhps) == $nn) ? 'style="border-bottom:1px solid #000;"' : '' !!}>{{ $bhp->nm_barang }}</td>
								<td {!! (count($bhps) == $nn) ? 'style="border-bottom:1px solid #000;"' : '' !!} align="right">{{ $bhp->qty }} {{ $bhp->nm_satuan }}</td>
								<td {!! (count($bhps) == $nn) ? 'style="border-bottom:1px solid #000;"' : '' !!} align="right">{{ number_format($bhp->harga,0,',','.') }}</td>
								<!-- <td align="right">{{ number_format($bhp->diskon,0,',','.') }}%</td> -->
								<td {!! (count($bhps) == $nn) ? 'style="border-bottom:1px solid #000;"' : '' !!} align="right">{{ number_format($bhp->subtotal,0,',','.') }}</td>
								<?php $total_resep += $bhp->subtotal; ?>
							</tr>
							<?php $nn++; ?>

							@if($urut == $perhalaman)
								</tbody>

									</table>
									<br />
									<!-- <table width="97%">
										<tr>
											<td width="60%">
												Terbilang : <div>{{ Format::terbilang($total_resep) }}</div>
											</td>
											<td width="30%">
												<table width="100%">
													<tr>
														<td>Total</td>
														<td>:</td>
														<td align="right"><h4>{{ number_format($total_resep,0,',','.') }}</h4></td>
													</tr>
												</table>
											</td>
										</tr>
									</table> -->

									<div class="footer">
										<table>
											<tr>
												<td valign="top">
													Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
													<p>{{ $halaman }}</p>
												</td>
												<td>
													<table>
														<tr>
															<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
														</tr>
														<tr>
															<td>
																<p>&nbsp;</p>
															</td>
														</tr>
														<tr>
															<td align="center">
																(................................................)
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</div>

								</section>


								<section class="area-print">
									<center>
										<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
										<div>{{ $biling->nomor_faktur }}</div>
									</center>
									<br />
									<table width="97%">
										<tr valign="top">
											<td>
												<table>
													<tr>
														<td>Pasien</td>
														<td>:</td>
														<td>{{ $biling->nama_pasien }}</td>
													</tr>
													<tr valign="top">
														<td colspan="3">
															Alamat
															<div>{{ $biling->alamat_pasien }}</div>
														</td>
													</tr>
												</table>
											</td>
											<td>
												<table>
													<tr>
														<td>No Treatment</td>
														<td>:</td>
														<td>{{ $treat->nomor_treatment }}</td>
													</tr>
													<tr>
														<td>Tanggal</td>
														<td>:</td>
														<td>{{ Format::hari($treat->created_at) }}, {{ Format::indoDate($treat->created_at) }}</td>
													</tr>
													<tr>
														<td>Dokter</td>
														<td>:</td>
														<td>{{ Me::dokter($treat->id_dokter) }}</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>

									<table class="table" cellpadding="0" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th></th>
												<th>barang</th>
												<th align="right">Qty</th>
												<th align="right">Total</th>
												<!-- <th align="right">Diskon</th> -->
												<th align="right">Subtotal</th>
											</tr>
										</thead>

										<tbody>

								<?php $urut = 1; ?>
								<?php $halaman++; ?>
							@endif

							<?php $urut++; ?>
						@endforeach

					@endif


					@if($urut == $perhalaman)
						</tbody>

							</table>
							<br />
							<!-- <table width="97%">
								<tr>
									<td width="60%">
										Terbilang : <div>{{ Format::terbilang($total_resep) }}</div>
									</td>
									<td width="30%">
										<table width="100%">
											<tr>
												<td>Total</td>
												<td>:</td>
												<td align="right"><h4>{{ number_format($total_resep,0,',','.') }}</h4></td>
											</tr>
										</table>
									</td>
								</tr>
							</table> -->

							<div class="footer">
								<table>
									<tr>
										<td valign="top">
											Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
											<p>{{ $halaman }}</p>
										</td>
										<td>
											<table>
												<tr>
													<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
												</tr>
												<tr>
													<td>
														<p>&nbsp;</p>
													</td>
												</tr>
												<tr>
													<td align="center">
														(................................................)
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>

						</section>


						<section class="area-print">
							<center>
								<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
								<div>#{{ $biling->nomor_faktur }}</div>
							</center>
							<br />
							<table width="97%">
								<tr valign="top">
									<td>
										<table>
											<tr>
												<td>Pasien</td>
												<td>:</td>
												<td>{{ $biling->nama_pasien }}</td>
											</tr>
											<tr valign="top">
												<td colspan="3">
													Alamat
													<div>{{ $biling->alamat_pasien }}</div>
												</td>
											</tr>
										</table>
									</td>
									<td>
										<table>
											<tr>
												<td>No Treatment</td>
												<td>:</td>
												<td>{{ $treat->nomor_treatment }}</td>
											</tr>
											<tr>
												<td>Tanggal</td>
												<td>:</td>
												<td>{{ Format::hari($treat->created_at) }}, {{ Format::indoDate($treat->created_at) }}</td>
											</tr>
											<tr>
												<td>Dokter</td>
												<td>:</td>
												<td>{{ Me::dokter($treat->id_dokter) }}</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<table class="table" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th width="5%">No</th>
										<th colspan="2" width="50%">Uraian</th>
										<th width="15%">Tarif Dasar</th>
										<!-- <th width="15%">Tarif Dokter</th>
										<th width="15%">Tarif RS</th> -->
										<!-- <th width="15%">Diskon</th> -->
										<th width="15%">Subtotal</th>
									</tr>
								</thead>

								<tbody>

						<?php $urut = 1; ?>
						<?php $total_resep = 0; ?>
						<?php $halaman++; ?>
					@endif

					<?php $urut++; ?>
				@endforeach

			
		@endforeach


	</tbody>

		</table>
		<br />
		<!-- <table width="97%">
			<tr>
				<td width="60%">
					Terbilang : <div>{{ Format::terbilang($total_resep) }}</div>
				</td>
				<td width="30%">
					<table width="100%">
						<tr>
							<td>Total</td>
							<td>:</td>
							<td align="right"><h4>{{ number_format($total_resep,0,',','.') }}</h4></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
 -->
		<div class="footer">
			<table>
				<tr>
					<td valign="top">
						Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
						<p>{{ $halaman }}</p>
					</td>
					<td>
						<table>
							<tr>
								<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
							</tr>
							<tr>
								<td>
									<p>&nbsp;</p>
								</td>
							</tr>
							<tr>
								<td align="center">
									(................................................)
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>

	</section>
	@endif



	<!-- Rinap -->

		@if(count($rinaps) > 0)

		<section class="area-print">
			<center>
				<h3 style="margin:0;">KUITANSI PEMBAYARAN</h3>
				<div>#{{ $biling->nomor_faktur }}</div>
			</center>
			<br />
			<table width="97%">
				<tr valign="top">
					<td>
						<table>
							<tr>
								<td>Pasien</td>
								<td>:</td>
								<td>{{ $biling->nama_pasien }}</td>
							</tr>
							<tr valign="top">
								<td colspan="3">
									Alamat
									<div>{{ $biling->alamat_pasien }}</div>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>Tanggal</td>
								<td>:</td>
								<td>{{ Format::hari($first_treatment->created_at) }}, {{ Format::indoDate($treatments[0]->created_at) }}</td>
							</tr>
							<tr>
								<td>Dokter</td>
								<td>:</td>
								<td>{{ Me::dokter($first_treatment->id_dokter) }}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th width="5%">No</th>
						<th width="50%">Uraian</th>
						<th width="15%">Sewa Kamar</th>
						<!-- <th width="15%">Tarif Dokter</th>
						<th width="15%">Tarif RS</th> -->
						<!-- <th width="15%">Diskon</th> -->
						<th width="15%">Subtotal</th>
					</tr>
				</thead>

				<tbody>
		<?php
			$no_rinap = 1; 
			$total_rinap = 0;
		?>
		@foreach($rinaps as $rinap)
				<?php $halaman++; ?>
				<tr>
					<td>{{ $no_rinap }}</td>
					<td>#{{ $rinap->kode_kamar }} ({{ $rinap->nm_kamar }})</td>
					<td align="center">
						
						@if($rinap->total_sewa > 11)
							{{ ($rinap->total_sewa / 24) }} hari
						@else
							0.5 hari
						@endif

					</td>
					<td align="right">{{ number_format($rinap->tarif_kamar,0,',','.') }}</td>
				</tr>
			<?php
				$no_rinap++; 
				$total_rinap += $rinap->tarif_kamar;
			?>
		@endforeach


	</tbody>

		</table>
		<br />
		<!-- <table width="97%">
			<tr>
				<td width="60%">
					Terbilang : <div>{{ Format::terbilang($total_rinap) }}</div>
				</td>
				<td width="30%">
					<table width="100%">
						<tr>
							<td>Total</td>
							<td>:</td>
							<td align="right"><h4>{{ number_format($total_rinap,0,',','.') }}</h4></td>
						</tr>
					</table>
				</td>
			</tr>
		</table> -->

		<div class="footer">
			<table>
				<tr>
					<td valign="top">
						Untuk pelayanan non rawat inap, hingga obat dan bahan abis pakai sudah termasuk PPN 10%
						<p>{{ $halaman }}</p>
					</td>
					<td>
						<table>
							<tr>
								<td align="center">Surabaya, {{ Format::hari(date('Y-m-d')) }}, {{ Format::indoDate(date('Y-m-d')) }}</td>
							</tr>
							<tr>
								<td>
									<p>&nbsp;</p>
								</td>
							</tr>
							<tr>
								<td align="center">
									(................................................)
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>

	</section>
	@endif

</body>
</html>
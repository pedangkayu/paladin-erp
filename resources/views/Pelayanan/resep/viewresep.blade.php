@extends('Master.Template')

@section('meta')
	 <!--  -->
<script type="text/javascript" src="{{ asset('js/resep/index.js') }}"></script>
	<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-skb tr:hover td .link{
			display: block;
		}
	</style>
@endsection

@section('title')
	Data Resep Obat
@endsection

@section('content')
<div class="row">
		<div class="col-sm-9">
			<div class="grid simple">
				<div class="grid-title no-border">
				</div>
				<div class="grid-body no-border">

					<div class="table-responsive">
						<table class="table table-striped daftar-skb">
							<thead>
								<tr>
								    <th width="5%"  class="text-middle">No. </th>
									<th width="20%" class="text-middle">No.Resep</th>
									<th width="20%" class="text-middle">No.Reg</th>
									<th width="25%" class="text-middle">Pasien</th>
									<th width="25%" class="text-middle">Dokter</th>
									<th width="5%" class="text-middle">Status</th>
								</tr>

							</thead>
							<tbody class="allresep">

									<?php $no = $items->currentPage() == 1 ? 1 : ($items->perPage() + $items->currentPage()) -1 ; ?>
								@forelse($items as $item)
								<tr class="sr_{{ $item->id_resep }}">
										<td>{{ $no }}</td>
										<td width="20%">
										{{ $item->nomor_resep}}
											<div class="link text-muted">
												<small>
													@if (($item->status_resep < 1)&&( $item->status < 2))
														<a href="{{ url('/resep/accresep/'.$item->id_resep) }}">Edit &middot; </a>
													@endif
														<a href="{{ url('/resep/detailresep/'.$item->id_resep) }}">Lihat &middot; </a>
														<a href="{{ url('/resep/print/' .$item->id_resep) }}" target="_blank" ><i class="fa fa-print"></i></a>
												</small>
											</div>
										</td>
										<td>
											<b>{{ $item->id_pasien_hc  }}</b>
											<div class="text-muted"><small>{{ Format::indoDate2($item->created_at) }}, {{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small></div>
											</td>
										<td>{{ $item->nama_pasien}}</td>
										<td>{{ $item->nm_depan }} {{$item->nm_belakang}}</td>
										<td>
											{{ $status[$item->status] }}
										</td>
										<td>
											@if ($item->status_resep < 1)
												<span class="label label-warning">Menunggu </span><br><br>
												<a href="{{ url('/resep/ambil/'.$item->id_resep) }}" class="text-danger">
													{{-- <a href="javascript:;" onclick="ubahstatus({{ $item->id_resep }});" class="text-danger"> --}}
												<span class="label label-success">Serah Terima</span></a>
											@else
												<span class="label label-important">Selesai </span><br>
											@endif
											<br><br>
											<a href="{{ url('/resep/retur/'.$item->id_resep) }}" class="text-danger"><span class="label label-info">Retur Barang</span></a>
										</td>
									</tr>
									<?php $no++; ?>
							@empty
							<tr>
								<td colspan="6">Tidak ditemukan</td>
							</tr>
							@endforelse

							</tbody>
						</table>
					</div>

					<div class="text-right paginresep">
						{!! $items->render() !!}
					</div>

				</div>
			</div>
		</div>

		<!-- halaman kanan layar -->
		<div class="col-sm-3">
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				<div class="btn-group" style="width:100%;">
				<a href="{{ url('/resep/obatpaten') }}" class="btn btn-primary btn-block dropdown-toggle" > Buat Resep  <span class="caret"></span></a>
				</div>
			</div>
		</div>
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				<div class="form-group">
					<b>Keterangan</b>
				</div>

				<div class="form-group">
					<label><span class="label label-warning">Menunggu</span></label>
					<small>Obat belum diAmbil pasien</small>
				</div>

				<div class="form-group">
					<label><span class="label label-success">Serah Terima</span></label>
					<small>Tombol untuk Serahterima Obat Pasien</small>
				</div>

				<div class="form-group">
					<label><span class="label label-info">Retur Obat</span></label>
					<small>Tombol untuk Melakukan Retur Obat</small>
				</div>

				<div class="form-group">
					<label><span class="label label-important">Selesai </span></label>
					<small>Status Resep yang sudah di Ambil</small>
				</div>
			</div>
		</div>
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				<div class="form-group">
					<label>No.Reg</label>
					<input type="text" name="id_pasien_hc" class="form-control">
				</div>
				<div class="form-group">
					<label>Nama</label>
					<input type="text" name="nama_pasien" class="form-control">
				</div>
				<div class="form-group">
					<label>Nomor Resep</label>
					<input type="text" name="nomor_resep" class="form-control">
				</div>

				<div class="form-group">
					<label>Status pengambilan </label>
					<select name="status_resep" class="form-control">
						<option value="">Semua</option>
						<option value="0" >Menunggu</option>
						<option value="1">Di ambil</option>
					</select>
				</div>

				<div class="form-group">
					<label>Status Bayar </label>
					<select name="status" class="form-control">
						<option value="">Semua</option>
						<option value="1" >Belum Lunas</option>
						<option value="2">Lunas</option>
					</select>
				</div>


				<div class="form-group">
					<label>Limit / Page</label>
					<select name="limit" class="form-control">
						<option value="5">5</option>
						<option value="10" selected="selected">10</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="500">500</option>
					</select>
				</div>

				<div class="form-group">
					<button class="btn btn-block btn-primary cariresep"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
		@endsection

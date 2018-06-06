@extends('Master.Template')

@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
	<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/js/modpembelian/batch/index.js') }}"></script>
@endsection

@section('title')
Batch
@endsection

@section('content')

<div class="row">
	<!-- left -->
	<div class="col-sm-9">

		<div class="grid simple">
			<div class="grid-title no-border">
				<h4><span class="total">{{ $items->total() }}</span> Items <strong>ditemukan</strong></h4>
			</div>
			<div class="grid-body no-border">

				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>No.</th>
								<th>Kode</th>
								<th>Barang / Obat</th>
								<th>No Batch / SN</th>
								<th class="text-right">Total</th>
								<th class="text-right">Sisa</th>
								<th>Exp</th>
							</tr>
						</thead>

						<tbody class="content-batch">
							<?php $no =1; ?>
							@forelse($items as $item)
							<?php $i = 1; ?>
							@foreach($item->batchs()->where('status', 1)->orderby('tgl_expired', 'asc')->get() as $batch)
							<tr>
								@if($i == 1)
								<td>{{ $no }}</td>
								<td>{{ $item->kode }}</td>
								<td>{{ $item->nm_barang }}</td>
								@else
								<td></td>
								<td></td>
								<td></td>
								@endif
								<td>
									<a href="#" onclick="detail({{ $batch->id_batch }});" data-toggle="modal" data-target="#myModal">{{ $batch->nomor_batch }}</a>
								</td>
								<td class="text-right">{{ $batch->total_qty }}</td>
								<td class="text-right">{{ $batch->in - $item->out }}</td>
								<td>{{ Format::indoDate2($batch->tgl_expired) }}</td>
							</tr>
							<?php $i++; ?>
							@endforeach
							<?php $no++; ?>
							@empty
								<tr>
									<td colspan="7">Tidak ditemukan</td>
								</tr>
							@endforelse
						</tbody>

					</table>
				</div>

				<div class="text-right pagin">
					{!! $items->render() !!}
				</div>

			</div>
		</div>

	</div>

	<!-- right -->
	<div class="col-sm-3">

		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				
				<div class="form-group">
					<label for="src">Nama Barang</label>
					<input type="text" class="form-control" id="src" name="nm_barang">
				</div>

				<div class="form-group">
					<label>Kode Barang</label>
				  	<input type="text" class="form-control" name="kode">
				</div>

				<div class="form-group">
					<div class="checkbox check-info">
						<input type="checkbox" name="titipan" id="titipan">
						<label for="titipan">Barang Titipan</label>
					</div>
				</div>
				
				<div class="form-group">
					<label for="source">Kategori</label>
					<select id="source" style="width:100%" name="kat">
						<option value="">Semua Kategori</option>
						@foreach($kategoris as $kategori)
							<option value="{{ $kategori->id_kategori }}" >{{ $kategori->nm_kategori }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="jenis">Jenis Barang</label>
					<select id="source" style="width:100%" name="tipe" id="jenis">
						<option value="0">Semua Tipe Barang</option>
						<option value="1">Obat</option>
						<option value="2">Barang</option>
					</select>
				</div>
				
				
				<div class="form-group">
					<label>Limit / Page</label>
					<select id="source" style="width:100%" name="limit">
						<option value="5">5</option>
						<option value="10" selected="selected">10</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="500">500</option>
					</select>
				</div>
				

				<div class="form-group">
					<button class="cari btn btn-block btn-primary"><i class="fa fa-search"></i> Cari</button>
				</div>

			</div>
		</div>

	</div>

</div>

@endsection

@section('footer')
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Detail Batch</h4>
			</div>
			<div class="modal-body">

				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<h4 class="nm_barang">...</h4>
						<span class="kode">...</span> | <span class="oleh"></span>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6">
						
						<div class="grid simple">
							<div class="grid-title no-border">
								<h4>Batch <strong>Detail</strong></h4>
							</div>
							<div class="grid-body no-border">
								
								<address>
									<strong>Nomor Batch / SN</strong>
									<p class="nomor_batch">...</p>

									<strong>Total Quantity</strong>
									<p class="total_qty">...</p>
									<strong>Sisa</strong>
									<p class="sisa">...</p>
									<strong>Expired</strong>
									<p class="tgl_expired">...</p>
									<strong>Titipan</strong>
									<p class="titipan">...</p>
								</address>

							</div>
						</div>

					</div>
					<div class="col-sm-6">
						
						<div class="grid simple">
							<div class="grid-title no-border">
								<h4>Detail <strong>GR</strong></h4>
							</div>
							<div class="grid-body no-border">
								<address>
									<strong>Nomor Good Receive</strong>
									<p class="no_spbm">...</p>

									<strong>Nomor Purchase Order</strong>
									<p class="no_po">...</p>

									<strong>Quantity Permintaan</strong>
									<p class="qty_item">...</p>

									<strong>Pengirim</strong>
									<p class="nm_pengirim">...</p>
									<strong>Tanggal Terima</strong>
									<p class="tgl_terima_barang">...</p>
								</address>
							</div>
						</div>

					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
			</div>
		</div>
	</div>
</div>
@endsection
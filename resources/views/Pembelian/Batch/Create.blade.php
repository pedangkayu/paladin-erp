@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('/js/modpembelian/batch/create.js') }}"></script>
	<script type="text/javascript">
		$(function(){
			$('[data-tipe="sn"]:first').focus();
		});
	</script>
	<style type="text/css">
		.btn-primary:focus{
			background-color: #064E48;
		}
		.datepicker{z-index:1151 !important;}
	</style>
@endsection

@section('title')
	Input Batch
@endsection

@section('content')
	
	<div class="row">
		<!-- left -->
		<div class="col-sm-8">
			
			<div class="grid simple">
				<div class="grid-title no-border">
						<h4>Purchase Order No. <b>{{ $gr->no_po }}</b></h4>
						<h4>Supplier : <b>{{ $gr->nm_vendor }}</b></h4>
					</div>
				<div class="grid-body no-border">
					
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Kode</th>
									<th>Barang/Obat</th>
									<th class="text-right">Qty</th>
									<th class="text-right">Bonus</th>
									<th class="text-right">Batch/SN</th>
								</tr>
							</thead>
							<tbody>
								<?php $no = 1; ?>
								@foreach($items as $item)
									<tr>
										<td>{{ $item->kode }}</td>
										<td>{{ $item->nm_barang }}</td>
										<td class="text-right">
											{{ number_format($item->qty_lg,0,',','.') }} {{ $item->nm_satuan }}
											<div>
												<small class="text-muted"><span class="total-batch-{{ $item->id_spbm_item }}">{{ $item->total_batch }}</span> batch</small>
											</div>
										</td>
										<td class="text-center">{!! $item->bonus > 0 ? '<i class="fa fa-check"></i>' : '-' !!}</td>
										<td class="text-right">
											<a data-toggle="modal" data-target="#myModal" tabindex="{{ $no }}" onclick="getbatch({{ $item->id_spbm_item }}, '{{ $item->tgl_exp }}');" data-tipe="sn" href="#" class="btn btn-small btn-primary"><i class="fa fa-barcode"></i> batch</a>
										</td>
									</tr>
									<?php $no++; ?>
								@endforeach
							</tbody>
						</table>
					</div>

				</div>
			</div>

		</div>

		<!-- right -->
		<div class="col-sm-4">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>Rangkuman</h4>
				</div>
				<div class="grid-body no-border">
					<address>
						<strong>No. Good Receive</strong>
						<p>{{ $gr->no_spbm }}</p>
						<strong>No. Invoice</strong>
						<p>{{ $gr->no_surat_jalan }}</p>
						<strong>Pengirim</strong>
						<p>{{ $gr->nm_pengirim }}</p>
						<strong>Tabggal Terima</strong>
						<p>{{ Format::indoDate($gr->tgl_terima_barang) }}</p>
						<strong>Jenis Pengiriman</strong>
						<p>{{ $kirim[$gr->id_kirim] }}</p>
						<strong>Pemerikasa</strong>
						<p>{{ $gr->pemeriksa1 }}</p>
						<strong>Pengawas</strong>
						<p>{{ $gr->pemeriksa2 }}</p>
						<strong>Keterangan</strong>
						<p>{{ $gr->keterangan }}</p>
					</address>

					<a href="{{ url('/batch/gr') }}" class="btn btn-primary btn-block">Kembali</a>
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
					<h4 class="modal-title" id="myModalLabel">Batch Nomor</h4>
				</div>
				<div class="modal-body">
					
					<div class="grid simple">
						<div class="grid-title no-border"></div>
						<div class="grid-body no-border">
							
							<div class="row">
								<div class="col-sm-5">
									<div class="form-group">
										<label for="no_batch">Batch Nomor</label>
										<input type="text" tabindex="101" id="no_batch" name="no_batch" class="form-control">
										<input type="hidden" name="id_spbm_item" value="0">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label for="tgl_exp">Tanggal Expired</label>
										<input type="text" tabindex="102" id="tgl_exp" name="tgl_exp" readonly="readonly" class="form-control">
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label for="qty">Qty</label>
										<input type="number" tabindex="103" id="qty" name="qty" class="form-control text-right">
									</div>
								</div>
							</div>

							<div>
								<button tabindex="104" data-spbm="{{ $gr->id_spbm }}" data-loading-text="<i class='fa fa-spin fa-circle-o-notch'></i>" class="btn btn-primary add-batch"><i class="fa fa-plus"></i> Tambah</button> * jumlah qty dalam satuan terkecil
							</div>

						</div>
					</div>

					<div class="grid simple">
						<div class="grid-title no-border">
							<h4><span class="total">0</span> Batch <strong>ditemukan</strong></h4>
						</div>
						<div class="grid-body no-border">
							
							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="30%">Nomor Batch</th>
											<th width="30%">Exp</th>
											<th width="30%" class="text-right">Quantity</th>
										</tr>
									</thead>
									<tbody class="modal-cotent-batch">
										<tr>
											<td colspan="3">Memuat...</td>
										</tr>
									</tbody>
								</table>
							</div>

						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				</div>
			</div>
		</div>
	</div>

@endsection
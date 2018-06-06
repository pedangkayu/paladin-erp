@extends('Master.Template')

@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
	<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/js/modpembelian/retur/index.js') }}"></script>
@endsection

@section('title')
	Retur Pembelian
@endsection

@section('content')
	
	<div class="row">
		<!-- left -->
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>{{ $items->total() }} ditemukan</h4>
				</div>
				<div class="grid-body no-border">
					
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th width="5%">No</th>
									<th width="20%">No. Retur</th>
									<th width="15%">No. PO</th>
									<th width="30%">Supplier</th>
									<th width="20%">Tanggal</th>
									<th width="10%"></th>
								</tr>
							</thead>

							<tbody class="content-retur">
								<?php $no = 1; ?>
								@forelse($items as $item)
									<tr>
										<td>{{ $no }}</td>
										<td>
											{{ $item->no_retur }}
										</td>
										<td>{{ $item->no_po }}</td>
										<td>
											{{ $item->nm_vendor }}<br />
											<small class="text-muted">
												{{ $item->telpon }}
											</small>
										</td>
										<td>
											{{ Format::indoDate2($item->created_at) }}<br />
											<small class="text-muted">{{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small>
										</td>
										<td>
											<a class="btn btn-white" href="{{ url('returvendor/print/' . $item->id_retur) }}" target="_blank"><i class="fa fa-print"></i></a>
										</td>
									</tr>
									<?php $no++; ?>
								@empty
									<tr>
										<td colspan="5">Tidak ditemukan!</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="text-right retur-pagin">
						{!! $items->render() !!}
					</div>

				</div>
			</div>

		</div>

		<!-- right -->
		<div class="col-sm-3">
			@if(Auth::user()->permission > 2)
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<a class="btn btn-block btn-primary" href="{{ url('/returvendor/po') }}">Dari Daftar PO</a>
					<a class="btn btn-block btn-primary" href="{{ url('/returvendor/itempo') }}">Dari Item / PO</a>
				</div>
			</div>
			@endif

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					
					<div class="form-group">
						<label>No. Retur</label>
						<input type="text" name="no_retur" class="form-control">
					</div>

					<div class="form-group">
						<label>No. PO</label>
						<input type="text" name="no_po" class="form-control">
					</div>
					
					<div class="form-group">
						<label for="penyedia">Penyedia</label>
						<select style="width:100%;" name="id_vendor" id="penyedia" required>
							<option value="">Loading...</option>
						</select>
					</div>

					<div class="form-group">
						<label for="tanggal">Tanggal Buat</label>
						<div class="input-group">
					      <input type="text" class="form-control tgl" name="tanggal" id="tanggal" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default btn-tanggal" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>

					<div class="form-group">
						<label for="limit">Limit / Page</label>
						<select name="limit" class="form-control">
							<option value="5">5</option>
							<option value="10" selected="selected">10</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
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
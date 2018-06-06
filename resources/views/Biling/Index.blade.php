@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ url('/js/biling/index.js') }}"></script>
@endsection

@section('title')
	Daftar Biling
@endsection

@section('content')
	
	<div class="row">
		<!-- left -->
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4><span class="total-biling">{{ number_format($total,0,',','.') }}</span> biling <strong>ditemukan</strong></h4>
				</div>
				<div class="grid-body no-border">
					
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>No.</th>
								<th>No. Faktur</th>
								<th>Pasien</th>
								<th>Tanggal</th>
								<th>Status</th>
								<th class="text-right">Total</th>
							</tr>
						</thead>


						<tbody class="content-biling">
							<?php $no = 1; ?>
							@forelse($items as $item)
								<tr>
									<td>{{ $no }}</td>
									<td>
										<div>{{ $item->nomor_faktur }}</div>
										<small>[
											<a href="{{ url('/biling/view/' . $item->id_faktur) }}">Lihat</a> | 
											<a href="{{ url('/biling/edit/' . $item->id_faktur) }}">Edit</a>
										]</small>
									</td>
									<td>{{ $item->nama_pasien }}</td>
									<td>
										{{ Format::indoDate2($item->tgl_faktur) }}
										<div><small class="text-muted">{{ Format::hari($item->tgl_faktur) }}, {{ Format::jam($item->craeted_at) }}</small></div>
									</td>
									<td class="text-center">
										<span class="label label-{{ $status[$item->status]['label'] }}">
											{{ $status[$item->status]['err'] }}
										</span>
									</td>
									<td class="text-right">{{ number_format($item->total,0,',','.') }}</td>
								</tr>
							<?php $no++; ?>
							@empty
								<tr>
									<td colspan="6">Tidak ditemukan...</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>

					<div class="pagin text-right">
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
					<a class="btn btn-primary btn-block" href="{{ url('/biling/create') }}">Buat Biling</a>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					
					<div class="form-group">
						<label for="no_faktur">No. Faktur</label>
						<input type="text" id="no_faktur" name="no_faktur" class="form-control">
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
						<label for="duodate">Duo Date</label>
						<div class="input-group">
					      <input type="text" class="form-control tgl" name="duodate" id="duodate" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default btn-duodate" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>


					<div class="form-group">
						<label for="duodate">Status</label>
						
					      <select class="form-control" name="status">
					      	<option value="-">Semua Status</option>
					      	<option value="0">Unpaid</option>
					      	<option value="1">Partially Paid</option>
					      	<option value="2">Paid</option>
					      </select>
					    
					</div>

					<div class="form-group">
						<label for="limit">Limit / Page</label>
						<select class="form-control" name="limit" id="limit">
							<option value="5">5</option>
							<option value="10" selected="selected">10</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
						</select>
					</div>

					<div class="form-group">
						<button class="btn btn-primary btn-block cari"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>

		</div>
		
	</div>

@endsection
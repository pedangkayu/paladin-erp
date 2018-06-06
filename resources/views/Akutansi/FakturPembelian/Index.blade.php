@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/akunting/fakturpembelian/index.js') }}"></script>
	<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-faktur tr:hover td .link{
			display: block;
		}
	</style>
@endsection

@section('title')
	Faktur Pembelian
@endsection

@section('content')
		
	<div class="row">
		<!-- left -->
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4><span class="total-faktur">{{ $items->total() }}</span> faktur <strong>ditemukan</strong></h4>
				</div>
				<div class="grid-body no-border">
					
					<table class="table table-striped daftar-faktur">
						<thead>
							<tr>
								<th width="10%">No.</th>
								<th width="25%">No. Faktur</th>
								<th width="20%" class="text-right">Total</th>
								<th width="15%">Tanggal</th>
								<th width="15%">Duo Date</th>
								<th width="15%">Status</th>
								<th width="15%">Tukar Faktur</th>	
							</tr>
						</thead>

						<tbody class="content-faktur">
							<?php $no =1; ?>
							@forelse($items as $item)
							<tr class="faktur-{{ $item->id_faktur }}">
								<td>{{ $no }}</td>
								<td>
									{{ $item->nomor_faktur }}
									<div class="link">
										<small>
												<a href="{{ url('/fakturpembelian/view/' . $item->id_faktur) }}">Lihat</a> |
											[	@if($item->status_faktur < 1)
												<a href="{{ url('/fakturpembelian/edit/' . $item->id_faktur) }}">Edit</a> |
												@else
												@endif
												<a target="_blank" href="{{ url('/fakturpembelian/print/' . $item->id_faktur) }}">Print</a> |
												<a href="javascript:void(0);" onclick="hapus({{ $item->id_faktur }});" class="text-danger">Batal</a>
											]
										</small>
									</div>
								</td>
								<td class="text-right">{{ number_format($item->total,0,',','.') }}</td>
								<td>
									{{ Format::indoDate2($item->tgl_faktur) }}<br />&nbsp;
								</td>
								<td>
									{{ Format::indoDate2($item->duodate) }}<br />&nbsp;
								</td>
								<td>{{ $status[$item->status] }}</td>
								<td>
									@if(($item->status_faktur ==0)&&($item->status==0))
										<a href="#" onclick="newtukar({{ $item->id_faktur }});" data-toggle="modal" data-target="#detail"  class="label label-important">Tukar Faktur</a>
									@else
										<span class="label label-success">Selesai TF</span>
									@endif
								</td>
							</tr>
							<?php $no++; ?>
							@empty
							<tr>
								<td colspan="6">Tidak ditemukan</td>
							</tr>
							@endif
						</tbody>

					</table>

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
					<a href="{{ url('/fakturpembelian/baru') }}" class="btn btn-primary btn-block">Buat Faktur</a>
				</div>
			</div>


			<!-- NOTIFIKASI -->
			<div class="tiles red added-margin">
				<div class="tiles-body">
					<div class="tiles-title">
						HUTANG JATUH TEMPO
					</div>	
					<div class="heading">
						RP <span class="animate-number" data-value="{{ empty($total_hutang_tempo->total) ? 0 : $total_hutang_tempo->total }}" data-animation-duration="1200">0</span>	
					</div>
					<div class="description"><i class="icon-custom-right"></i><span class="text-white mini-description ">&nbsp; {{ empty($count_hutang_tempo->total) ? 0 : $count_hutang_tempo->total }} Supplier</span></div>	
				</div>
			</div>
			<br />

			<div class="tiles green added-margin">
			  <div class="tiles-body">
				<div class="tiles-title">
					HUTANG SUPPLIER
				</div>	
				<div class="heading">
				RP <span class="animate-number" data-value="{{ empty($total_hutang->total) ? 0 : $total_hutang->total }}" data-animation-duration="1200">0</span>
										
				</div>
				<div class="description"><i class="icon-custom-right"></i><span class="text-white mini-description ">&nbsp; {{ empty($count_hutang->total) ? 0 : $count_hutang->total }} Supplier</span></div>
				</div>	
			</div>
			<br />


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
						<label for="status">Status</label>
						<select class="form-control" name="status" id="status">
							<option value="-">Semua</option>
							<option value="0">Baru</option>
							<option value="1">Nyicil</option>
							<option value="2">Lunas</option>
							<option value="3">Batal</option>
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
@section('footer')
	<!-- Modal -->
	<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">NO Faktur <span class="no_faktur"></span></h4>
				</div>
				<div class="modal-body">
					<div class="detail-tukar">Memuat...</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
					<span class="btn-acc"></span>
				</div>
			</div>
		</div>
	</div>
	@endsection
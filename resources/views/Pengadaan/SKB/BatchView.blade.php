@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/pengadaan/skb.js') }}"></script>
	<script type="text/javascript">
	$(function(){
		$.getJSON(_base_url + '/skb/terkait', {id : {{ $skb->id_spb }} }, function(json){
			$('.item-terkait').html(json.content);
			$('.total-terkait').html(json.total);
		});
	});
	</script>
@endsection

@section('title')
	Detail Batch
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<h1><i>No. {{ $skb->no_skb }}</i></h1>
					<span class="text-muted">
						No. PMB/PMO {{ $skb->no_spb }}
					</span>
					<p><div class="well well-sm">{{ $skb->keterangan }}</div></p>
					<div class="text-right">
						<a href="{{ url('/skb') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali ke List</a>
					</div>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="20%">Kode</th>
									<th width="30%">Barang/Obat</th>
									<th width="20%">Batch</th>
									<th width="15%" class="text-right">Quantity</th>
									<th width="15%">Exp</th>
								</tr>
							</thead>
							<tbody>
								@forelse($items as $item)
									<?php
										$batchs = $item->batchs()
										->join('data_batch', 'data_batch.id_batch', '=', 'data_log_batch.id_batch')
										->where('id_parent', $item->id_skb)
										->where('tipe', 1)
										->orderby('data_batch.tgl_expired', 'asc')
										->orderby('data_log_batch.qty_in', 'desc')
										->get();
										$i = 1;
									?>
									@forelse($batchs as $batch)
										<tr>
											@if($i == 1)
												<td>{{ $item->kode }}</td>
												<td>{{ $item->nm_barang }}</td>
											@else
												<td colspan="2"></td>
											@endif

											<td>{{ $batch->nomor_batch }}</td>
											<td class="text-right">{{ number_format($batch->qty_in,0,',','.') }}</td>
											<td>{{ Format::indoDate2($batch->tgl_expired) }}</td>
										</tr>
										<?php $i++; ?>
									@empty
										<tr>
											@if($i == 1)
												<td>{{ $item->kode }}</td>
												<td>{{ $item->nm_barang }}</td>
											@else
												<td colspan="2"></td>
											@endif
											<td colspan="3">Tidak ditemukan</td>
										</tr>	
									@endforelse

									@empty
									<tr>
										<td colspan="5">Tidak ditemukan</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<address>
						<strong>Oleh</strong>
						<p>{{ $skb->nm_depan }} {{ $skb->nm_belakang }}</p>
						<strong>Tanggal</strong>
						<p>{{ Format::indoDate($skb->created_at) }}<br />
						<small class="text-muted">{{ Format::hari($skb->created_at) }}, {{ Format::jam($skb->created_at) }}</small>
						</p>

						<strong>Untuk Departemen</strong>
						<p>{{ $skb->nm_departemen }}</p>
					</address>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border">
					<span><b><span class="total-terkait">0</span> Terkait</b> {{ $skb->no_spb }}</span>
				</div>
				<div class="grid-body no-border">
					<table class="table table-bordered table-striped">
						<tbody class="item-terkait">
							<tr>
								<td>Memuat...</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

		</div>
	</div>

@endsection
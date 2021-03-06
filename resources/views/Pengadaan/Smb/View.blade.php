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
	Detail SMB
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<h1><i>No. {{ $skb->no_mutasi_skb }}</i></h1>
					<span class="text-muted">
						No. PMBU{{ $skb->no_mutasi_spb }}
					</span>
					<span class="tex-muted"></span>
					<p><div class="well well-sm">{{ $skb->keterangan }}</div></p>
					<div class="text-right">
						<a href="{{ url('/Smb') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali ke List</a>
						<a href="{{ url('/Smb/print/' . $skb->id_mutasi_skb) }}" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
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
									<th width="25%">Nama</th>
									<th width="15%" class="text-right text-middle">Stok</th>
									<th width="15%" class="text-right text-middle">Qty Permohonan</th>
									<th width="15%" class="text-right text-middle">Realisasi</th>
								</tr>
							</thead>
							<tbody>
								@foreach($items as $item)
									<tr>
										<td>
											<a href="javascript:;" data-toggle="tooltip" data-placement="bottom" title="{{ $item->nm_barang }}">{{ Format::substr($item->nm_barang,25) }}</a><br />
											<small class="text-muted">{{ $item->kode }}</small>
										</td>
										<td class="text-right">
											{{ number_format($item->in - $item->out,0,',','.') }} {{ $item->nm_satuan }}
										</td>
										<td class="text-right">
											{{ number_format($item->qty_awal,0,',','.') }} {{ $item->nm_satuan }}
										</td>
										<td class="text-right">
											{{ number_format($item->qty,0,',','.') }} {{ $item->nm_satuan }}
										</td>
									</tr>
								@endforeach
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

						<strong>Untuk Gudang</strong><br>
						{{$skb->nm_gudang_asal}}
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
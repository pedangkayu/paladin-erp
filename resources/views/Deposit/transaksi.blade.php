@extends('Master.Template')
@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection

@section('meta')
	<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<style>
		.datepicker{z-index:1151 !important;}
	</style>
@endsection

@section('title')
DETAIL TRANSAKSI SALDO
@endsection

@section('content')

<div class="row">
	<!-- left -->
	<div class="col-sm-12">

		<div class="grid simple header-status">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">

				<div class="row">
					<div class="col-sm-7">
						<address>
							<h5>No. Registrasi Pasien  <b>#{{ $data->id_pasien }}</b></h5>
							<h3>{{ $data->nama_pasien }} </h3>
							<h5>
								{{ $data->alamat_pasien }}<br />
								{{ $data->telp_pasien }}
							</h5>
						 </address>	
						 
                         <div class="grid-title no-border"></div>
					
						<h5> data diperbarui pada tanggal {{ Format::indoDate2($data->tanggal) }} , {{ Format::hari($data->created_at) }}, {{ Format::jam($data->created_at) }}</h5>
					</div>

					<div class="col-sm-5 text-right">
						<h5>Jumlah Saldo Deposit</h5>
						<button type="button" class="btn btn-danger btn-cons"><b>Rp. {{ number_format($data->saldo,2,',','.') }}</b></button>
						<div class="grid-title no-border"></div>
						<div class="text-right">
								<a href="{{ url('/Deposit') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
								<a class="btn btn-primary" href="{{ url('/Deposit/print/' . $data->id_deposit) }}" target="_blank"><i class="pull-left fa fa-print"></i> Print</a>
						</div>
				    </div>
			</div>
		</div>
	</div>

	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">

			<table class="table">
				<thead>
					<tr>
						<th width="5%"  class="text-middle">No. </th>
						<th width="30%" class="text-middle">Metode Pembayaran</th>
						<th width="15%" class="text-middle">Debit</th>
						<th width="15%" class="text-middle">Kredit</th>
						<th width="20%" class="text-middle">Tanggal Transaksi</th>
						<th width="10%" class="text-middle">Keterangan</th>
					</tr>
				</thead>

				<tbody>
					<?php $no = 1; ?>
					@foreach($detail as $item)
					<tr>
						<td class="text-middle">{{ $no }}</td>
						<td>{{ $id_payment_method[$item->id_payment_method] }} <small>{{ $item->nm_bank }}</small></td>
						 

						<td class="text-middle">@if($item->masuk > 0 )
								Rp. {{ number_format($item->masuk,0,',','.') }}
							@else
								--
							@endif
						</td>
						<td class="text-middle">
							@if($item->keluar > 0 )
								Rp. {{ number_format($item->keluar,0,',','.') }}
							@else
								--
							@endif
						</td>

						<td  class="text-middle"><b>{{ Format::indoDate2($item->created_at) }}</b>
							<div class="text-muted"><small> {{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small></div>
						</td>
						<td class="text-middle" align="right">{{ $item->catatan }}</td>
					</tr>
					<?php $no++; ?>
					@endforeach

				</tbody>
			</table>	

		</div>
	</div>

</div>

<!-- right -->

</div>

@endsection

@section('footer')
<!-- Modal -->
@endsection
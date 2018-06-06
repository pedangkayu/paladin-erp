@extends('Master.Print')
@section('meta')
@endsection
@section('content')
	
	<center><h3><strong>DETAIL TRANSAKSI DEPOSIT</strong></h3></center>

	<table width="100%">
		<tr>
			<td width="65%" valign="top">
				<address>
					<h3 style="margin:0;"><strong>{{ $data->nama_pasien }}</strong></h3>
					<div>NO. REG. PASIEN #{{ $data->id_pasien }} </div>
					<div>{{ $data->alamat_pasien }}</div>
					<div>Telpon : {{ $data->telp_pasien }}</div>
					
				</address>
				 
			</td>
			<td width="35%" valign="top">
				<table class="table table-bordered" cellspacing="0" cellpadding="3" width="100%">
					<tr>
						<td width="50%" class="bold"><strong>Tanggal Perbaruan</strong></td>
						<td width="50%" align="right">#{{ Format::indoDate($data->tanggal) }}</td>
					</tr>

					<tr>
						<td width="50%" class="bold">Saldo</td>
						<td width="50%" align="right"><b> Rp. {{ number_format($data->saldo,2,',','.') }} </b></td>
					</tr>
					
					
				</table>
			</td>
		</tr>
	</table>

	<br />

	<table class="table table-bordered" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="5%">No.</th>
				<th width="15%">Metode Pembayaran</th>
				<th width="15%">Debit</th>
				<th width="15%">Kredit</th>
				<th width="20%">Tanggal Transaksi</th>
				<th width="30%">Keterangan</th>
			</tr>
		</thead>

		<tbody>
			<?php $no = 1; ?>
			@foreach($detail as $item)
			<tr>
				<td class="text-middle">{{ $no }}</td>
				<td>{{ $id_payment_method[$item->id_payment_method] }}  
				    <div class="text-muted"><small>{{ $item->nm_bank }} </small></div>
				</td>
				 
				<td class="text-middle">
					@if($item->masuk > 0 )
						Rp. {{ number_format($item->masuk,0,',','.') }}
					@else
					Rp. -
					@endif
				</td>
				<td class="text-middle">
					@if($item->keluar > 0 )
						Rp. {{ number_format($item->keluar,0,',','.') }}
					@else
					Rp. 0,00
					@endif
				</td>

				<td  class="text-middle"> {{ Format::indoDate2($item->created_at) }} 
					<div class="text-muted"><small> {{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small></div>
				</td>
				<td class="text-middle" align="right">{{ $item->catatan }}</td>
			</tr>
				<?php $no++; ?>
			@endforeach

		</tbody>
	</table>
	
@endsection
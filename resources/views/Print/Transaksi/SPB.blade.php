@extends('Master.Print')

@section('meta')
<style type="text/css">
	h3{
		font-weight: normal;
		margin: 0;
	}
</style>
@endsection

@section('content')

<center>
	<h3><strong>Rekap PMB / PMO </strong></h3>
	<span>Periode
		@if($req->waktu == 1)
		{{ Format::nama_bulan($req->bulan) }} {{ $req->tahun }}
		@else
		{{ Format::indoDate2($req->dari) }} - {{ Format::indoDate2($req->sampai) }}
		@endif</span>
	</center>
	<br />
	<table class="table table-bordered" cellspacing = "0">
		<thead>
			<tr>
				<th>No.</th>
				<th class="text-middle">No PMB/PMO</th>
				<th class="text-center">Pemohon</th>
				<th class="text-center">Departemen</th>
				<th class="text-center">Deadline</th>
				<th class="text-center">Tgl Transaksi</th>
				<th class="text-center">Nama Barang</th>
				<th class="text-center">Qty</th>
				<th class="text-center">Satuan</th>
				
			</tr>
		</thead>

		<tbody>
			<?php 
			$no = 1; 
			$id_spb = "";
			?>
			@foreach($items as $item)
				<?php $i = 1; ?>
				@foreach($item->rekap as $data)
				@if($i == 1)
				<tr>
					<td>{{ $no }}</td>
					<td class="text-left">{{$item->no_spb}} </td>
					<td class="text-left">{{$item->nm_depan}} {{$item->nm_belakang}}</td>
					<td class="text-left">{{$item->nm_departemen}}</td>
					<td class="text-left">{{Format::indoDate2($item->deadline)}}</td>
					<td class="text-left">{{Format::indoDate2($item->created_at) }}</td>
				@else
					<td colspan="6"></td>
				@endif
					<td class="text-left">{{ $data->nm_barang }}</td>
					<td class="text-left">{{ $data->qty }}</td>
					<td class="text-left">{{ $data->nm_satuan }}</td>
				</tr>
				<?php	$i++; ?>
				@endforeach
				<?php	$no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection
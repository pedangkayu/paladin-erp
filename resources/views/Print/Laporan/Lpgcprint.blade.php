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
	<h3><strong>Rekap Laporan Gudang Kecil</strong></h3>
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
				<th width="5%" class="text-middle">No.</th>
				<th width="55%" class="text-middle">Barang</th>
				<th width="10%" class="text-right">In</th>
				<th width="10%" class="text-right">Out</th>
				<th width="10%" class="text-right">Jumlah</th>
				<th class="text-middle"> Gudang </th>
			</tr>
		</thead>

		<tbody> 
			@if(count($items) > 0)
			<?php 
			$no = 1; 
			?>
			@foreach($items as $item)
			<tr>
				<td>{{ $no }}</td>
				<td>{{ $item->nm_barang }}</td>
				<td class="text-right"> {{$item->in}}  </td>
				<td class="text-right"> {{$item->out}}  </td>
				<td class="text-right"> {{$item->qty}} </td>
				<td class="text-middle"> {{ $item->nm_gudang }} </td>
			</tr>
			<?php 
			$no++; 
			?>
			@endforeach
			@endif
		</tbody>
	</table>
@endsection
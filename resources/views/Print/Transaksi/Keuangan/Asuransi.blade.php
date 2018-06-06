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
	<h3><strong>Rekap Pembayaran Asuransi </strong></h3>
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
					<th class="text-middle">No.</th>
					<th class="text-middle">Nama</th>
					<th class="text-center">No Faktur</th>
					<th class="text-center">Tanggal</th>
					<th class="text-center">Pembayaran</th>
					<th class="text-center">Nomor</th>
					<th class="text-center">Nominal</th>
			</tr>
		</thead>

		<tbody>
			<?php 
			$no = 1; 
			$id_faktur = "";
			$total=0;

			?>
			@foreach($items as $item)
				<?php
				$i = 1;
				$total += $item->jumlah;
				 ?>

				<tr>
						<td>{{ $no }}</td>
						<td colspan="text-left">{{$item->nama_pasien}} </td>
						<td class="text-left">{{$item->nomor_faktur}}</td>
						<td class="text-left">{{ Format::indoDate2($item->created_at)}}</td>
						<td class="text-left">{{ $item->nm_asuransi }}</td>
						<td class="text-left">{{ $item->no_asuransi }}</td>
						<td class="text-left">Rp.{{ number_format($item->jumlah,0,',','.')}}</td>
						
				</tr>
				<?php $no++; ?>
			@endforeach
				<tr>
					<td class="text-left" colspan="6"><center>Total</center> </td>
					<td  class="text-left"> Rp.{{ number_format($total,0,',','.')}}</td>
				</tr>
		</tbody>
	</table>
	@endsection
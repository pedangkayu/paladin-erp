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
	<h3><strong>Rekap Honor Dokter Dari Pasien </strong></h3>
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
				<th class="text-middle">Dokter</th>
				<th class="text-middle">Pasien</th>
				<th class="text-center">Kategori</th>
				<th class="text-center">Tanggal</th>
				<th class="text-center">Jenis Jasa</th>
				<th class="text-center">Nominal</th>

			</tr>
		</thead>

		<tbody>
			<?php
			$no = 1;
			?>
			@foreach($medis as $item)

				<tr>
					<td>{{ $no }}</td>
					<td class="text-left">{{$item->nm_depan}} {{$item->nm_belakang}}</td>
					<td class="text-left">{{$item->nama_pasien}} </td>
					<td class="text-left">{{$item->nm_gudang}}</td>
					<td class="text-left">{{Format::indoDate2($item->tgl_input)}}</td>
					<td class="text-left">{{$item->nm_service}}</td>
					<td class="text-left">{{number_format($item->tarif_dr,0,',','.') }}</td>
				<?php	$no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection

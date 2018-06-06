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
	<h3><strong>Rekap KRS </strong></h3>
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
					<th class="text-middle">Pasien</th>
                    <th class="text-center">Antrian</th>
					<th class="text-center">Tanggal Daftar</th>
					<th class="text-center">Tanggal Pakai</th>
					<th class="text-center">Selesai Rinap</th>
                    <th class="text-center"> Kamar</th>

			</tr>
		</thead>

		<tbody>
			<?php
				$no = 1;
			?>
			@foreach($krs as $item)
		
					<td>{{ $no }}</td>
					<td class="text-left">{{$item->nama_pasien}}</td>
					<td class="text-left">{{$item->id_antrian}} </td>
					<td class="text-left">{{Format::indoDate2($item->daftar_rinap)}}</td>
					<td class="text-left">{{Format::indoDate2($item->tgl_pakai)}}</td>
					<td class="text-left">{{Format::indoDate2($item->selesai_rinap)}}</td>
					<td class="text-left">{{$item->nm_kamar}}</td>
				</tr>
			
				<?php $no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection

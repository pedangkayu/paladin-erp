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
	<h3><strong>Rekap Transaki Data Pasien Rawat Inap </strong></h3>
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
				<th class="text-middle">No Antrian</th>
				<th class="text-center">Pasien</th>
				<th class="text-center">Kamar</th>
				<th class="text-center">Tgl Masuk</th>
				<th class="text-center">Tgl Keluar</th>
				<th class="text-center">Status</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$no = 1; 
			$id_rinap = "";
			?>
			@foreach ($rinap as $item) 
			
			<tr>
				<td class="text-left">{{$no}}</td>
				<td class="text-left">{{$item->id_antrian}}</td>
				<td class="text-left">{{$item->nama_pasien}}</td>
				<td class="text-left">{{ $item->nm_kamar}}</td>
				<td class="text-left">{{  \Format::indoDate2($item->tgl_pakai)}} {{ \Format::hari($item->tgl_pakai)}} {{ \Format::jam($item->tgl_pakai)}}</td>
				<td>
					@if($item->selesai_rinap >0)
						{{ Format::indoDate2($item->selesai_rinap)}} {{Format::hari($item->selesai_rinap)}} {{Format::jam($item->selesai_rinap)}}
					@else
					
					@endif
				<td >{{ $No_trans[$item->No_trans] }}</td>
			</tr>
	
			<?php $no++;?>
			@endforeach	
		</tbody>

	</table>
	@endsection
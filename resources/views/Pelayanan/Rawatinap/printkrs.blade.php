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
		@if($req->bulan < 1)
		Semua Bulan {{ $req->tahun }}
		@else
		{{ Format::nama_bulan($req->bulan) }} {{ $req->tahun }}
		@endif</span>
	</center>
	<br />


	<table class="table table-bordered" cellspacing = "0">
		<thead>
			<tr>

                    <th>No.</th>
					<th class="text-middle">No Antrian</th>
					<th class="text-middle">Pasien</th>
					<th class="text-middle">Tanggal Daftar</th>
					<th class="text-middle">Tanggal Pakai</th>
					<th class="text-middle">Tanggal Keluar</th>
					<th class="text-middle">Kamar</th>
					<th class="text-middle">Status</th>

			</tr>
		</thead>

		<tbody>
			<?php
				$no = 1;
			?>
			@foreach($krs as $item)
		
					<td>{{ $no }}</td>
					<td class="text-left">{{ $item->id_antrian}}</td>
					<td class="text-left">
						<div>{{ $item->nama_pasien}}</div>
						<small>{{ $item->alamat_pasien}}</small>

					</td>
					<td class="text-left">{{ Format::indoDate2($item->daftar_rinap)}}</td>
					<td class="text-left"> 
						@if($item->tgl_pakai >0)
							<div>{{ Format::indoDate2($item->tgl_pakai)}}</div> 
							<small>{{Format::hari($item->tgl_pakai)}} {{Format::jam($item->tgl_pakai)}}</small>
						@else
						
						@endif
					</td>
					<td class="text-left"> 
						@if($item->selesai_rinap >0)
							<div>{{ Format::indoDate2($item->selesai_rinap)}}</div> 
							<small>{{Format::hari($item->selesai_rinap)}} {{Format::jam($item->selesai_rinap)}}</small>
						@else
						
						@endif
					</td>
					<td class="text-left">{{ $item->nm_kamar}}</td>
					<td class="text-left">{{ $No_trans[$item->No_trans] }}</td>
				</tr>
			
				<?php $no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection

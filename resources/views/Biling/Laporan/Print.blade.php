@extends('Master.Print')

@section('meta')
<style type="text/css">
	h3{
		font-weight: normal;
		margin: 0;
	}
	.table-bordered tr td, .table-bordered tr th{
		border-right: none !important;
	}
</style>
@endsection

@section('content')

<center>
	<h3><strong>JURNAL UMUM</strong></h3>
	<h3><strong>{{ $header }}</strong></h3>
	
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
			<th>NO</th>
			<th class="text-middle">TANGGAL</th>
			<th class="text-middle">AKUN</th>
			<th class="text-middle">PERKIRAAN</th>
			<th class="text-center">KETERANGAN</th>
			<th class="text-center">DEBIT</th>
			<th class="text-center">KREDIT</th>
		</tr>
	</thead>

	<tbody>
		{!! $content !!}
	</tbody>

</table>
@endsection
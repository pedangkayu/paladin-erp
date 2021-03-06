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
	<h3><strong>Rekap Belanja Barang dan Obat</strong></h3>
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
			<th rowspan="2" class="text-middle">No.</th>
			<th rowspan="2" class="text-middle">Kode</th>
			<th class="text-middle" rowspan="2">{{ $req->id_kategori > 0 ? 'Barang/Obat' : 'Kategori' }}</th>

			<th class="text-center" colspan="2">Reguler</th>
			<th class="text-center" colspan="2">Titipan</th>
			<th class="text-center" colspan="2">Global</th>
		</tr>

		<tr>
			<th>Jumlah</th>
			<th>Nominal</th>

			<th>Jumlah</th>
			<th>Nominal</th>

			<th>Jumlah</th>
			<th>Nominal</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		$reg_total      = 0;
		$reg_nominal    = 0;
		$no = 1; 
		?>
	 
		@foreach($items as $item)
		<tr>
			<td>{{ $no }}</td>
			<td>{{ $item->kode }}</td>
			<td>{{empty($item->nm_kategori) ? $item->nm_barang : $item->nm_kategori}}</td>
			<td class="text-right">{{number_format($item->total,0,',','.')}}</td>
			<td class="text-right">{{number_format($item->harga,2,',','.')}}</td>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right">{{number_format($item->total,0,',','.')}}</td>
			<td class="text-right">{{ number_format($item->harga,2,',','.') }}</td>
		</tr>
		<?php $no++;  
		$reg_total += $item->total;
		$reg_nominal += $item->harga;
		?>
		@endforeach
		<tr>
			<td class="text-center" colspan="3"><b>Total</b></td>
			<td class="text-right semi-bold"><strong>{{number_format($reg_total,0,',','.')}}</strong></td>
			<td class="text-right semi-bold"><strong>{{number_format($reg_nominal,2,',','.')}}</strong></td>
			<td class="text-right"></td>
			<td class="text-right"></td>
			<td class="text-right semi-bold"><strong>{{number_format($reg_total,0,',','.')}}</strong></td>
			<td class="text-right semi-bold"><strong>{{number_format($reg_nominal,2,',','.')}}</strong></td>
		</tr>
	</tbody>

</table>
@endsection
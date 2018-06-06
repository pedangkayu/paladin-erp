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
	<h3><strong>Rekap Good Recive </strong></h3>
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
				<th class="text-middle">No GR</th>
				<th class="text-center">Surat Jalan</th>
				<th class="text-center">Supplier</th>
				<th class="text-center">Kode</th>
				<th class="text-center">Item</th>
				<th class="text-center">Merk</th>
				<th class="text-center">Qty Diminta</th>
				<th class="text-center">Qty Terpenuhi</th>
				<th class="text-center">Sisa</th>
				<th class="text-center">Bonus</th>
				<th class="text-center">Titipan</th>
				<th class="text-center">Satuan</th>
			</tr>
		</thead>

		<tbody>
			<?php 
			$no = 1; 
			$id_spbm = '';
			?>
			@foreach ($items as $item) 
				<?php $i = 1; ?>
				@foreach($item->rekap as $data)
					@if($i == 1)
						<tr>
						<td>{{ $no }}</td>
						<td class="text-left">{{$item->no_spbm}}</td>
						<td class="text-left">{{$item->no_surat_jalan }}</td>
						<td class="text-left">{{ $item->nm_vendor }}</td>
					@else
						<td colspan="4"></td>
					@endif
						<td class="text-left">{{$data->kode }}</td>
						<td class="text-left">{{$data->nm_barang}} </td>
						<td class="text-left">{{$data->merek}} </td>
						<td class="text-right">{{$data->qty_lg}} </td>
						<td class="text-right">{{$data->qty}} </td>
						<td class="text-right">{{$data->sisa}} </td>
						<td class="text-right">{{$data->bonus}} </td>
						<td class="text-left"></td>
						<td class="text-left">{{$data->satuan}} </td>
					</tr>
					<?php $i++; ?>	
				@endforeach
				<?php $no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection
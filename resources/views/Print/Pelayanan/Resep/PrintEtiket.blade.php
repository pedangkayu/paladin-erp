
@extends('Master.PrintEtiket')
@section('content')
<div>
	<table class="identitas">
		@foreach($pasiendetail as $item)
			<tr>
				<td><strong>No. Resep</strong></td>
				<td>:{{$item->nomor_resep}}</td> 
			</tr>
			<tr>
				<td><strong>Nama</strong></td>
				<td>:{{$item->nama_pasien}} </td>
			</tr>
		</table>
		<table class="identitas1">	
			<tr>
				<td><strong>Alamat</strong></td>
				<td>:{{$item->alamat_pasien}}</td>
			</tr>
			
			<tr>
				<td><strong>Tanggal lahir</strong></td>
				<td>:{{$item->tgllahir_pasien}}</td>
			</tr>
		</table>
		
	</div>
	<table class="table table-bordered" cellspacing="0">
			<thead>
				<tr>
					<th rowspan="2">Nama Barang</th>
					<th rowspan="2">Jumlah</th>
					<th rowspan ="2">Aturan Pakai</th>
					<th  rowspan="2">Keterangan</th>
				</tr>
			</thead>
					<tr>
					<td >{{$item->nm_barang}}</td>
					<td class="text-center">{{$item->qty}} &nbsp;{{$item->nm_satuan}}</td>
					<td class="text-center">{{$item->resep_aturan}}</td>
					<td class="text-center">.................</td>
					</tr>
		</table>
	@endforeach
<!-- //isi -->
@endsection



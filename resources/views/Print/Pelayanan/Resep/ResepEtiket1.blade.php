<!DOCTYPE html>
<html>
<head>
	<title>Document Rs Onkologi</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/Print/PrintEtiket.css') }}">
	<script type="text/javascript" src="{{ asset('/js/print.js') }}"></script>
	<style>
		.page-break {
		    page-break-after: always;
		}
	</style>
	@yield('meta')
</head>
<body>

<!-- untuk perulangan obat yang table -->
@foreach($pasiendetail as $item)
@if(($item->id_satuan ==5)&&( $item->id_barang >0) OR ($item->id_satuan==23))
<br>
<header>
	<img src="{{ asset('/img/logo-etiket1.png') }}">
	<div class="alamat">
		Araya Galaxy Bumi Permai Blok A-2 No.7		<br />
		(Jl. Arif Rahman Hakim) Surabaya 60111		<br />
		Telp. +62-31-5914855 Fax. +62-31-5914860	<br />
		www.rsonkologi.com
	</div>
</header>
<br />
<section class="container">
<div>
	<table class="identitas">
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
</section>
@endif
@if(($item->id_satuan ==6)&&( $item->id_barang >0))
<header>
	<img src="{{ asset('/img/logo-etiket1.png') }}">
	<div class="alamat">
		Araya Galaxy Bumi Permai Blok A-2 No.7		<br />
		(Jl. Arif Rahman Hakim) Surabaya 60111		<br />
		Telp. +62-31-5914855 Fax. +62-31-5914860	<br />
		www.rsonkologi.com
	</div>
</header>
<br />
<section class="container">
<div>
	<table class="identitas">
			<tr>
				<td><strong>No. Resep</strong></td>
				<td>:{{$data->nomor_resep}}</td> 
			</tr>
			<tr>
				<td><strong>Nama</strong></td>
				<td>:{{$data->nama_pasien}} </td>
			</tr>
		</table>
		<table class="identitas1">	
			<tr>
				<td><strong>Alamat</strong></td>
				<td>:{{$data->alamat_pasien}}</td>
			</tr>
			
			<tr>
				<td><strong>Tanggal lahir</strong></td>
				<td>:{{$data->tgllahir_pasien}}</td>
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
					<td class="text-center">{{$item->keterangan}}</td>
					</tr>
		</table>
</section>
@endif

@endforeach

	<footer class="text-center btn-print">
		<hr />
		<button type="button" onclick="window.print();">Print Dokumen</button>
		<button type="button" onclick="window.close();">Keluar</button>
	</footer>
</body>

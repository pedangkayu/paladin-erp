@extends('Master.Print')

@section('content')
	<div>
		<table class="printparent">
			<tr>
				<td><strong>No. Permintaan</strong></td>
				<td>: {{ $spb->no_mutasi_spb }}</td>
			</tr>
			<tr>
				<td><strong>No. Mutasi Barang</strong></td>
				<td>: {{ $spb->no_mutasi_skb }}</td>
			</tr>
			<tr>
				<td><strong>Tanggal</strong></td>
				<td>: {{ Format::indoDate($spb->created_at) }}</td>
			</tr>
{{-- 			<tr>
				<td><strong>Bagian</strong></td>
				<td>: {{ $spb->nm_departemen }}</td>
			</tr> --}}
			<tr>
				<td><strong>Gudang Pengirim</strong></td>
				<td>: {{ $spb->nm_gudang_termohon }}</td>
			</tr>
			<tr>
				<td><strong>Gudang Tujuan</strong></td>
				<td>: {{ $spb->nm_gudang_pemohon }}</td>
			</tr>
		</table>
	</div>

	<div>
		<table class="table table-bordered" cellspacing="0">
			<thead>
				<tr>
					<th width="5%" rowspan="2">No</th>
					<th width="20%" rowspan="2">Kode Barang</th>
					<th width="25%" rowspan="2">Nama Barang</th>
					<th width="10%" rowspan="2">Jumlah</th>
					<th colspan="2">Penyerahan</th>
					<th colspan="2">Penerimaan</th>
			
					<th width="15%" rowspan="2">Keterangan</th>
				</tr>
				
				<tr>
					<th width="5%">Jumlah</th>
					<th width="5%">Check</th>
					<th width="5%">Jumlah</th>
					<th width="5%">Check</th>
				</tr>

			</thead>
			<tbody>
				<?php $no = 1; ?>
				@foreach($items as $item)
					<tr>
						<td class="text-center">{{ $no }}</td>
						<td>{{ $item->kode }}</td>
						<td>{{ $item->nm_barang }}</td>
						<td class="text-right">{{ $item->qty_awal }} {{ $item->nm_satuan }}</td>

						<td class="text-right">{{ number_format($item->qty) }}</td>
						<td></td>
						<td></td>
						<td></td>
						<td>{{ $item->keterangan }}</td>
					</tr>
					<?php $no++; ?>
				@endforeach
			</tbody>
		</table>
	</div>

	<div>
		<table class="ttd">
			<tr>
				<td>Pengirim,</td>
				<td>Mengetahui,</td>
				<td>Menerima,</td>
			</tr>
			<tr>
				<td colspan="2"><br/><br/></td>
			</tr>
			<tr>
				
				<td>{{ $spb->petugas_depan }} {{ $spb->petugas_belakang }}</td>
				<td>...............................</td>
				<td>{{ $spb->nm_depan }} {{ $spb->nm_belakang }}</td>
			</tr>
			<tr>
				<td>(Admin  Gudang {{ $spb->nm_gudang_termohon }})</td>
				<td>(Kepala Unit)</td>
				<td>( Admin Gudang  {{ $spb->nm_gudang_pemohon }})</td>
			</tr>
		</table>
	</div>
@endsection
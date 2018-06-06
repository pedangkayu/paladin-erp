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
	<h3><strong>Rekap Transaki Obat Racik/ Penjualan Obat  </strong></h3>
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
				<th class="text-middle">No Resep</th>
				<th class="text-middle">Kode Pasien</th>
				<th class="text-center">Pasien</th>
				<th class="text-center">Tgl Transaksi</th>
				<th class="text-center">Nama Racikan</th>
				<th class="text-center">Komposisi</th>
				<th class="text-center">Qty</th>
			
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 1; 
			$id_resep = "";
			?>
			@foreach($items as $item)
				<?php $i = 1; ?>
				@foreach($item->obat as $data)
				<?php
				$c = 1;
						$ite = $data->campur()
								->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
								->join('data_item_gudang', 'data_item_gudang.id_item_gudang', '=' ,'data_resep_campur.id_item_gudang') //<-- untuk join² gudankan id_item_gudang. Perlu di ingat id_item_gudang tidak sama dengan id_barang
								->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_campur.id_satuan_campur')
								->join('data_resep_item','data_resep_item.id_resep_item', '=', 'data_resep_campur.id_resep_item')
								->join('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan')
								->select(
									'data_resep_campur.*',
									'data_barang.kode',
									'data_barang.nm_barang',
									'data_resep_item.keterangan',
									'data_resep_item.id_resep_aturan',
									'ref_resep_aturan.resep_aturan',
									'data_resep_item.nama_campur',
										'ref_satuan.nm_satuan'
									)
								->get();
					
				?>
				@foreach ($ite as $campur) 
				@if($i == 1)
				<tr>
					<td>{{ $no }}</td>
					<td class="text-left">{{ $item->nomor_resep}} </td>
					<td class="text-left">{{$item->id_pasien_hc}}</td>
					<td class="text-left">{{ $item->nama_pasien}} </td>
					<td class="text-left">{{ Format::indoDate2($item->created_at)}}</td>
				@else
					<td colspan="5"></td>
				@endif
				@if($c==1)
					<td class="text-left">{{$data->nama_campur}}</td>
				@else
				<td></td>
				@endif
					<td class="text-left">{{ $campur->nm_barang }}</td>
					<td class="text-left">{{ $campur->qty }}&nbsp; {{$campur->nm_satuan}}</td>
					
				</tr>
				<?php	$i++; ?>

				<?php $c++; ?>
				@endforeach
				@endforeach
				<?php	$no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection
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
	<h3><strong>Rekap Transaki Obat Paten dan Obat Racik</strong></h3>
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
				<th class="text-middle">Nama</th>
				<th class="text-center">Dokter</th>
				<th class="text-center">Tgl Transaksi</th>
				<th class="text-center">Obat</th>
				<th class="text-center">Komposisi</th>
				<th class="text-center">Qty</th>
				<th class="text-center">Stn</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$no = 1; 
			$id_resep = "";
			?>
			@foreach ($items as $item) 	
				<?php	
			$patens=$item->obat()
					->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
					->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
					->get();
					?>
				<?php	$i = 1; ?>
				@foreach ($patens as $paten) <!--paten-->
				@if($paten->id_barang <> 0)
					@if($i == 1)
					<tr>
						<td class="text-left">{{ $no}}</td>
						<td class="text-left">{{ $item->nomor_resep }}</td>
						<td class="text-left">{{ $item->nama_pasien }}</td>
						<td class="text-left">{{ $item->nm_depan}} {{$item->nm_belakang }}</td>
						<td class="text-left">{{ Format::indoDate2($item->created_at)}}</td>
					@else
						<td colspan="5"></td>
					@endif
						<td class="text-left">{{ $paten->nm_barang }}</td>
						<td class="text-left">-</td>
						<td class="text-left">{{ $paten->qty }}</td>
						<td class="text-center"> {{$paten->nm_satuan }}</td>
					</tr>
					@endif <!-- id_barang-->
					@if($paten->id_barang == 0)
					<?php
					$campurs=$paten->campur()
						->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
						->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_campur.id_satuan_campur')
						->get(); 
						?>
					<?php	$c=1; ?> 
						@foreach ($campurs as $campur)
						@if($c==1)
					<tr>
						<td></td>
						<td colspan="2" class="semi-bold text-center">Nama Rscikan</td>
						<td colspan="3" class="semi-bold text-center">{{ $paten->nama_campur }}s</td>
						@else
						<td colspan="6"></td>
						@endif
						<td class="text-left">{{ $campur->nm_barang }}</td>
						<td class="text-left">{{ $campur->qty }}</td>
						<td class="text-center">{{ $campur->nm_satuan }}</td>
					</tr>
						<?php $c++; ?>
						@endforeach
						@endif <!--i-jj-->
						<?php $i++;?>
					@endforeach <!--obat paten-->
					<?php $no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection
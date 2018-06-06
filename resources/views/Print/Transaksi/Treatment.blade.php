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
	<h3><strong>Rekap Transaki Treatment </strong></h3>
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
				<th class="text-middle">No Treatment</th>
				<th class="text-center">Pasien</th>
				<th class="text-center">Tgl Pelayanan</th>
				<th class="text-center">Tindaka /Pelayanan</th>
				<th class="text-center">Obat</th>
				<th class="text-center">Qty</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$no = 1; 
			$id_treatment = "";
			?>
			@foreach ($treatment as $item) 
			<?php
				$jasa =$item->items()
					//->join('ref_service', 'ref_service.id_service', '=', 'data_treatment_item.id_service')
					->leftjoin('ref_service_kode', 'ref_service_kode.service_kode', '=', 'data_treatment_item.service_kode')
				// ->orderby('data_treatment_item.id_treatment_item', 'desc')
				->get();
				$i =1;?>
			@foreach ($jasa as $jasa)
			@if($i == 1)
			<tr>
				<td class="text-left">{{$no}}</td>
				<td class="text-left">{{$item->nomor_treatment}}</td>
				<td class="text-left">{{$item->nama_pasien}}</td>
				<td class="text-left">{{ \Format::indoDate2($item->created_at)}}</td>
			@else
				<td></td>
				<td colspan="3"></td>
			@endif
				<td class="text-left">{{$jasa->nm_service}}</td>
				<td colspan="2"></td>
			</tr>
			<?php 
				$bhp=$jasa->bhp()
				->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
				->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
				->get();
			$c=1; ?>
		@foreach ($bhp as $obt) 
			@if($c==1)
			<tr>
				<td></td>
				<td colspan="4"></td>
			@else
				<td></td>
				<td colspan="4"></td>
			@endif
				<td class="text-left">{{$obt->nm_barang}}</td>
				<td class="text-left">{{$obt->qty}} &nbsp;{{$obt->nm_satuan}}</td>
			</tr>
			<?php $c++; ?>
			@endforeach
			<?php $i++; ?>
			@endforeach
			<?php $no++;?>
			@endforeach	
		</tbody>

	</table>
	@endsection

@extends('Master.Print')

@section('meta')
<script type="text/javascript" src="{{ asset('/js/akunting/fakturpembelian/Report/print/pembelian.js') }}"></script>
<style type="text/css">
	h3{
		font-weight: normal;
		margin: 0;
	}
</style>
@endsection

@section('content')

<center>
	<h3><strong>Rekap Faktur Pembelian </strong></h3>
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
                <th class="text-middle">Tgl Faktur</th>
                <th class="text-center">No FP</th>
                <th class="text-center">Penjual</th>
                <th class="text-center">NPWP Penjual</th>
                <th class="text-center">Pembeli</th>
                <th class="text-center">Alamat</th>
                <th class="text-center">NPWP Pembeli</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Diskon</th>
                <th class="text-center">Dpp</th>
                <th class="text-center">Ppn</th>
                <th class="text-center">Dpp + Ppn</th>

			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			?>
			@foreach($medis as $item)
            <?php $persen= $item->total/100;
            $kali = $persen * $item->diskon;
            $dpp=$item->total - $kali;
            //uuntuk mencari ppn
            $kali_ppn = $persen * $item->ppn;
            $ppn_dpp = $kali_ppn + $dpp; ?>
				<tr>
					<td>{{ $no }}</td>
					<td class="text-left">{{$item->tgl_faktur}} </td>
					<td class="text-left">{{$item->nomor_faktur}} </td>
                    <td class="text-left">{{$item->nm_vendor}}</td>
                    <td class="text-left">{{$item->no_npwp}}</td>
                    <td class="text-left">{{$client->nm_client}}</td>
                    <td class="text-left">{{$client->alamat}}</td>
                    <td class="text-left">{{$client->no_npwp}}</td>
                    <td class="text-left">{{number_format($item->total,0,',','.')}}</td>
                    <td class="text-left">{{$item->diskon}}(%)</td>
                    <td class="text-left">{{number_format($dpp,0,',','.')}} </td>
                    <td class="text-left">{{$item->ppn}} (%)</td>
                    <td class="text-left">{{number_format($ppn_dpp,0,',','.')}}</td>
				<?php	$no++; ?>
			@endforeach
		</tbody>

	</table>
	@endsection

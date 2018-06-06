@extends('Master.Print')

@section('meta')
<style type="text/css">
	h4{
		margin: 0;
	}
	.table-bordered tr td, .table-bordered tr th{
		border-right: none !important;
	}
</style>

@endsection

@section('content')


<input type="hidden" name="dari" value="{{ isset($req['dari']) ? $req['dari'] : '' }}">
<input type="hidden" name="sampai" value="{{ isset($req['sampai']) ? $req['sampai'] : '' }}">

<center>
	<h3><strong>BUKU BESAR </strong></h3>
	<span>Periode
	 <?php echo date('d/m/Y', strtotime($req['dari'])) ?> -
	 <?php echo date('d/m/Y', strtotime($req['sampai'])) ?>
	</span>
</center>
<br/>


@forelse($leadgers as $lg)

		<?php $total_debit = 0; ?>
		<?php $total_kredit = 0; ?>
		<table class="table table-bordered" cellspacing="0" style="margin-bottom:30px;border:solid 1px #000;">
			<tr>
				<td class="text-left bold" colspan="3">AKUN {{ strtoupper($lg[0]->nm_coa) }}</td>
				<td class="text-right bold" colspan="3">KODE AKUN {{ $lg[0]->kode }}</td>
			</tr>
			<tr>
				<th class="text-left">TANGGAL</th>
				<th class="text-left">KODE AKUN</th>
				<th class="text-left">PERKIRAAN</th>
				<th class="text-left">KETERANGAN</th>
				<th class="text-right">Debit</th>
				<th class="text-right">Kredit</th>
			</tr>
		@forelse($lg as $item)
			<tr>
				<td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
				<td>{{ $item->kode }}</td>
				<td>{{ $item->nm_coa }}</td>
				<td>{{ $item->keterangan }}</td>
				<td class="text-right">{{ empty($item->debit) ? '-' : number_format($item->debit,0,',','.') }}</td>
				<td class="text-right">{{ empty($item->kredit) ? '-' : number_format($item->kredit,0,',','.') }}</td>
				<?php $total_debit += $item->debit; ?>
				<?php $total_kredit += $item->kredit; ?>
			</tr>
		@empty
			<tr>
				<td colspan="6">Tidak ditemukan</td>
			</tr>
		@endforelse

			<tr class="text-bold">
				<td colspan="4"><h4 class="bold">TOTAL PERKIRAAN {{ strtoupper($item->nm_coa) }}</h4></td>
				<td class="text-right"><h4 class="bold">{{ number_format($total_debit,0,',','.') }}</h4></td>
				<td class="text-right"><h4 class="bold">{{ number_format($total_kredit,0,',','.') }}</h4></td>
			</tr>
			
		</table>
	@empty
		
	@endforelse

@foreach($coas as $coa)
	@if(!in_array($coa->id_coa, $ids) && $all)
	<table class="table table-bordered" cellspacing="0"  style="margin-bottom:30px;border:solid 1px #000;">
		<tr>
			<td class="text-left bold" colspan="3">AKUN {{ strtoupper($coa->nm_coa) }}</td>
			<td class="text-right bold" colspan="3">KODE AKUN {{ $coa->kode }}</td>
		</tr>
		<tr>
			<th class="text-left">TANGGAL</th>
			<th class="text-left">KODE AKUN</th>
			<th class="text-left">PERKIRAAN</th>
			<th class="text-left">KETERANGAN</th>
			<th class="text-right">Debit</th>
			<th class="text-right">Kredit</th>
			<th class="text-right">Debit</th>
			<th class="text-right">Kredit</th>
		</tr>
	
		<tr>
			<td colspan="6">Tidak ditemukan</td>
		</tr>
	

		<tr class="text-bold">
			<td colspan="4"><h4 class="bold">TOTAL PERKIRAAN {{ strtoupper($coa->nm_coa) }}</h4></td>
			<td class="text-right"><h4 class="bold">-</h4></td>
			<td class="text-right"><h4 class="bold">-</h4></td>
		</tr>
	</table>
	@endif
@endforeach

@endsection
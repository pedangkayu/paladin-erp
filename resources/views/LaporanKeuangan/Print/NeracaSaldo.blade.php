@extends('Master.Print')

@section('meta')
<style type="text/css">
	h4{
		margin: 0;
	}
	.table-bordered tr td{
		border-right: none !important;
	}
</style>

@endsection

@section('content')


<input type="hidden" name="dari" value="{{ isset($req['dari']) ? $req['dari'] : '' }}">
<input type="hidden" name="sampai" value="{{ isset($req['sampai']) ? $req['sampai'] : '' }}">

<center>
	<h3><strong>NERACA SALDO </strong></h3>
	<span>Periode
	 <?php echo date('d/m/Y', strtotime($req['dari'])) ?> -
	 <?php echo date('d/m/Y', strtotime($req['sampai'])) ?>
	</span>
</center>
<br/>


<table class="table table-bordered" cellspacing="0" style="margin-bottom:30px;border:solid 1px #000;">
	<thead>
		<tr>
			<th rowspan="2">KODE</th>
			<th rowspan="2">PERKIRAAN</th>
			<th rowspan="2">DEBIT</th>
			<th rowspan="2">KREDIT</th>
			<th colspan="2">SALDO</th>
		</tr>
		<tr>
			<th>DEBIT</th>
			<th>KREDIT</th>
		</tr>
	</thead>
	<tbody>
		<?php $total_debit = 0; ?>
		<?php $total_kredit = 0; ?>
		<?php $total_debit_saldo = 0; ?>
		<?php $total_kredit_saldo = 0; ?>
		@foreach($ns as $nsal)
			<tr>
				<td class="text-right">{{ $nsal->kode }}</td>
				<td>{{ $nsal->nm_coa }}</td>
				<td class="text-right">{{ $nsal->debit > 0 ? number_format($nsal->debit,0,',','.') : '-' }}</td>
				<td class="text-right">{{ $nsal->kredit > 0 ? number_format($nsal->kredit,0,',','.') : '-' }}</td>
				<td class="text-right">{{ $nsal->debit > $nsal->kredit ? number_format($nsal->debit - $nsal->kredit,0,',','.') : '-' }}</td>
				<td class="text-right">{{ $nsal->kredit > $nsal->debit ? number_format($nsal->kredit - $nsal->debit,0,',','.') : '-' }}</td>
				<?php $total_debit += $nsal->debit; ?>
				<?php $total_kredit += $nsal->kredit; ?>
				<?php $total_debit_saldo += $nsal->debit > $nsal->kredit ? ($nsal->debit - $nsal->kredit) : '-'; ?>
				<?php $total_kredit_saldo += $nsal->kredit > $nsal->debit ? ($nsal->kredit - $nsal->debit) : '-'; ?>
			</tr>
		@endforeach

		<tr>
			<td colspan="2"><strong class="bold">TOTAL</strong></td>
			<td class="text-right bold"><strong class="bold">{{ number_format($total_debit,0,',','.') }}</strong></td>
			<td class="text-right bold"><strong class="bold">{{ number_format($total_kredit,0,',','.') }}</strong></td>
			<td class="text-right bold"><strong class="bold">{{ number_format($total_debit_saldo,0,',','.') }}</strong></td>
			<td class="text-right bold"><strong class="bold">{{ number_format($total_kredit_saldo,0,',','.') }}</strong></td>
		</tr>

	</tbody>
</table>

@endsection
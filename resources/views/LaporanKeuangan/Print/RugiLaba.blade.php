@extends('Master.Print')

@section('meta')
<style type="text/css">
	h3{
		font-weight: normal;
		margin: 0;
	}
	.table-bordered tbody tr td{
		border-right: none !important;
	}
	.table {
		margin-bottom: 30px;
	}
</style>

@endsection

@section('content')

<input type="hidden" name="dari" value="{{ isset($req['dari']) ? $req['dari'] : '' }}">
<input type="hidden" name="sampai" value="{{ isset($req['sampai']) ? $req['sampai'] : '' }}">

<center>
	<h3><strong>Rekap Laba Keuangan RSOS </strong></h3>
	<span>Periode
		 <?php echo date('d/m/Y', strtotime($req['dari'])) ?> -
		 <?php echo date('d/m/Y', strtotime($req['sampai'])) ?>
		</span>
	</center>
	<br/>

	<table class="table table-bordered" cellspacing = "0">
			<thead>
				<tr>
					<th>Kode</th>
					<th>Akun</th>
					<th class="text-right">Balance</th>
				</tr>
			</thead>

			<tbody>

				<tr>
					<td colspan="3"><h3>PENDAPATAN</h3></td>
				</tr>

				<?php  $ids = ''; ?>
				<?php  $total = 0; ?>
				<?php  $total_km = 0; ?>
				@foreach($pendapatan as $rep)
					<tr>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->kode }}
						</td>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->nm_coa }}
						</td>
						<td class="text-right total total-{{ $rep->id_coa }}">
							@if(($rep->kredit - $rep->debit) < 0)
								({{ number_format(abs($rep->kredit - $rep->debit),0,',','.') }})
							@else
									{{ number_format(($rep->kredit - $rep->debit),0,',','.') }}
							@endif
							<?php  $total += ($rep->kredit - $rep->debit); ?>
						</td>
					</tr>
				@endforeach

				<tr>
					<td colspan="2"><h3><span>TOTAL PENDAPATAN</span></h3></td>
					<td class="text-right"><h3><span class="total_pendapatan">{{ number_format($total,0,',','.') }}</span></h3></td>
					<?php  $total_km += $total; ?>
				</tr>


				<tr>
					<td colspan="3"><h3>BEBAN USAHA</h3></td>
				</tr>

				<?php $total2 = 0; ?>
				@foreach($biaya as $rep)
					<tr>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->kode }}
						</td>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->nm_coa }}
						</td>
						<td class="text-right total total-{{ $rep->id_coa }}">
							@if(($rep->debit - $rep->kredit) < 0)
								({{ number_format(abs($rep->debit - $rep->kredit),0,',','.') }})
							@else
									{{ number_format(($rep->debit - $rep->kredit),0,',','.') }}
							@endif
							<?php  $total2 += ($rep->debit - $rep->kredit); ?>
						</td>
					</tr>
				@endforeach

				<tr>
					<td colspan="2"><h3><span>TOTAL BEBAN USAHA</span></h3></td>
					<td class="text-right"><h3><span class="total_biaya">{{ number_format($total2,0,',','.') }}</span></h3></td>
					<?php  $total_km -= $total2; ?>
				</tr>


				<tr>
					<td colspan="3"><h3>PENDAPATAN DILUAR USAHA</h3></td>
				</tr>
				<?php  $total3 = 0; ?>
				@foreach($pendapatan_luar_usaha as $rep)
					<tr>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->kode }}
						</td>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->nm_coa }}
						</td>
						<td class="text-right total total-{{ $rep->id_coa }}">
							@if(($rep->kredit - $rep->debit) < 0)
								({{ number_format(abs($rep->kredit - $rep->debit),0,',','.') }})
							@else
									{{ number_format(($rep->kredit - $rep->debit),0,',','.') }}
							@endif
							<?php  $total3 += ($rep->kredit - $rep->debit); ?>
						</td>
					</tr>
				@endforeach

				<tr>
					<td colspan="2"><h3><span>TOTAL PENDAPATAN DILUAR USAHA</span></h3></td>
					<td class="text-right"><h3><span class="total_pendapatan">{{ number_format($total3,0,',','.') }}</span></h3></td>
					<?php  $total_km += $total3; ?>
				</tr>

				<tr>
					<td colspan="3"><h3>BEBAN DILUAR USAHA</h3></td>
				</tr>

				<?php $total4 = 0; ?>
				@foreach($biaya_luar_usaha as $rep)
					<tr>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->kode }}
						</td>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->nm_coa }}
						</td>
						<td class="text-right total total-{{ $rep->id_coa }}">
							@if(($rep->debit - $rep->kredit) < 0)
								({{ number_format(abs($rep->debit - $rep->kredit),0,',','.') }})
							@else
									{{ number_format(($rep->debit - $rep->kredit),0,',','.') }}
							@endif
							<?php  $total4 += ($rep->debit - $rep->kredit); ?>
						</td>
					</tr>
				@endforeach

				<tr>
					<td colspan="2"><h3><span>TOTAL BEBAN USAHA DILUAR USAHA</span></h3></td>
					<td class="text-right"><h3><span class="total_biaya">{{ number_format($total4,0,',','.') }}</span></h3></td>
					<?php  $total_km -= $total4; ?>
				</tr>


				<tr>
					<td colspan="3"><h3>PAJAK</h3></td>
				</tr>

				<?php $total5 = 0; ?>
				@foreach($pajak as $rep)
					<tr>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->kode }}
						</td>
						<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
							{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
							{{ $rep->nm_coa }}
						</td>
						<td class="text-right total total-{{ $rep->id_coa }}">
							@if(($rep->debit - $rep->kredit) < 0)
								({{ number_format(abs($rep->debit - $rep->kredit),0,',','.') }})
							@else
									{{ number_format(($rep->debit - $rep->kredit),0,',','.') }}
							@endif
							<?php  $total5 += ($rep->debit - $rep->kredit); ?>
						</td>
					</tr>
				@endforeach

				<tr>
					<td colspan="2"><h3><span>TOTAL PAJAK</span></h3></td>
					<td class="text-right"><h3><span class="total_biaya">{{ number_format($total5,0,',','.') }}</span></h3></td>
					<?php  $total_km -= $total5; ?>
				</tr>


				<tr>
					<td colspan="2"><h3><span>TOTAL RUGI/LABA</span></h3></td>
					<td class="text-right"><h3><span class="total_rugi_laba">{{ number_format($total_km,0,',','.') }}</span></h3></td>
				</tr>


			</tbody>
		</table>

@endsection

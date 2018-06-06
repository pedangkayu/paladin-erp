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


<input type="hidden" name="dari" value="{{ isset($req['dari']) ? $req['dari'] : '' }}">
<input type="hidden" name="sampai" value="{{ isset($req['sampai']) ? $req['sampai'] : '' }}">

<center>
	<h3><strong>Rekap Neraca Keuangan RSOS </strong></h3>
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
					<?php  $ids = ''; ?>
					<?php  $total = 0; ?>
					<?php  $total_km = 0; ?>
					@foreach($harta as $rep)
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
								 {{ $rep->tipe == 2 ? number_format(abs($rep->debit - $rep->kredit),0,',','.') : number_format(($rep->debit - $rep->kredit),0,',','.') }}
								<?php  $total += $rep->tipe == 2 ? abs($rep->debit - $rep->kredit) : ($rep->debit - $rep->kredit); ?>
							</td>
						</tr>
					@endforeach

					<tr>
						<td colspan="2"><h3><strong>TOTAL HARTA</strong></h3></td>
						<td class="text-right"><h3><strong class="total_harta">{{ number_format($total,0,',','.') }}</strong></h3></td>
					</tr>
					<?php $total = 0 ; ?>
					@foreach($kewajiban as $rep)
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
								 {{ $rep->tipe == 2 ? number_format(abs($rep->debit - $rep->kredit),0,',','.') : number_format(($rep->debit - $rep->kredit),0,',','.') }}
								<?php  $total += $rep->tipe == 2 ? abs($rep->debit - $rep->kredit) : ($rep->debit - $rep->kredit); ?>
							</td>
						</tr>
					@endforeach

					<tr>
						<td colspan="2"><h3><strong>TOTAL KEWAJIBAN</strong></h3></td>
						<td class="text-right"><h3><strong class="total_kewajiban">{{ number_format($total,0,',','.') }}</strong></h3></td>
						<?php  $total_km += $total; ?>
					</tr>

					<?php $total = 0; ?>
					@foreach($modal as $rep)
						<tr>
							<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
								{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
								{{ $rep->kode }}
							</td>
							<td {!! $rep->parent_id > 0 ? '' : 'class="text-bold"' !!}>
								{!! str_repeat('&nbsp;&nbsp;', count(explode('.', $rep->kode))) !!}
								{{ $rep->nm_coa }}
							</td>
							<td class="text-right  total-{{ $rep->id_coa }}">
								 {{ $rep->tipe == 2 ? number_format(abs($rep->debit - $rep->kredit),0,',','.') : number_format(($rep->debit - $rep->kredit),0,',','.') }}
								<?php  $total += $rep->tipe == 2 ? abs($rep->debit - $rep->kredit) : ($rep->debit - $rep->kredit); ?>
							</td>
						</tr>
					@endforeach

					<tr>
						<td colspan="2"><h3><strong>TOTAL MODAL</strong></h3></td>
						<td class="text-right"><h3><strong class="total_modal">{{ number_format($total,0,',','.') }}</strong></h3></td>
						<?php  $total_km += $total; ?>
					</tr>


					<tr>
						<td colspan="2"><h3><strong>TOTAL KEWAJIBAN DAN MODAL</strong></h3></td>
						<td class="text-right"><h3><strong class="total_kewajiban_modal">{{ number_format($total_km,0,',','.') }}</strong></h3></td>
					</tr>

				</tbody>
			</table>
	@endsection
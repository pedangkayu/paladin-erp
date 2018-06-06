@extends('Master.Template')

@section('meta')
	<style type="text/css">
		.text-bold{
			font-weight: bold;
		}
	</style>

	<script type="text/javascript">

		$(function(){
			$('[name="dari"]').datepicker({
				format : 'yyyy-mm-dd'
			});

			$('.dari-btn').click(function(){
				$('[name="dari"]').val('');
			});

				$('[name="sampai"]').datepicker({
				format : 'yyyy-mm-dd'
			});

			$('.sampai-btn').click(function(){
				$('[name="sampai"]').val('');
			});


		});

	</script>

@endsection

@section('title')
	{{ $header }}
@endsection

@section('content')
<form  method="get" action="{{url('/lapkeuangan/rugilaba')}}">

	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">


				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="dari">Dari tanggal</label>
							<div class="input-group">
						      <input type="text" value="{{ !empty($req['dari']) ? $req['dari'] : date('Y-m-d', strtotime('-1 Month', time())) }}" class="form-control" name="dari" readonly="readonly">
						      <span class="input-group-btn">
						        <button class="btn btn-default dari-btn" type="button"><i class="fa fa-trash"></i></button>
						      </span>
						    </div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
							<label for="dari">Sampai Tanggal</label>
							<div class="input-group">
						      <input type="text" value="{{ !empty($req['sampai']) ? $req['sampai'] : date('Y-m-d') }}" class="form-control" name="sampai" readonly="readonly">
						      <span class="input-group-btn">
						        <button class="btn btn-default sampai-btn" type="button"><i class="fa fa-trash"></i></button>
						      </span>
						    </div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
					<div class="col-sm-4">
						<label>&nbsp;</label>
						<button type="submit" class="btn cari btn-block btn-primary">Proses</button>
					</div>
					<div class="col-sm-4">
						<label>&nbsp;</label>
						<a href="{{ url($print) }}" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-print"></i></a>
					</div>
						</div>
					</div>

				</div>


		</div>
	</div>
</form>
	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">

			<table class="table">
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




		</div>
	</div>

@endsection

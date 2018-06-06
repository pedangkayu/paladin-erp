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
<form  method="get" action="{{url('/lapkeuangan/neraca')}}">
	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">

			
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label for="dari">Dari tanggal</label>
						<div class="input-group">
					      <input type="text" value="{{ date('Y-m-d', strtotime('-1 Month', time())) }}" class="form-control" name="dari" readonly="readonly">
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
					      <input type="text" value="{{ date('Y-m-d') }}" class="form-control" name="sampai" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default sampai-btn" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>
				</div>
				
					
				<div class="col-sm-2">
					<div class="form-group">
						<label>&nbsp;</label>
						<button type="submit" class="btn cari btn-block btn-primary">Proses</button>
					</div>
				</div>

				
				<div class="col-sm-2">
					<label>&nbsp;</label>
					<a href="{{ url($print) }}" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-print"></i></a>
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
								 {{ number_format(($rep->debit - $rep->kredit),0,',','.') }}
								<?php  $total += ($rep->debit - $rep->kredit); ?>
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
								 {{  number_format(($rep->kredit - $rep->debit),0,',','.') }}
								<?php $total += ($rep->kredit - $rep->debit); ?>
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
								 {{ number_format(($rep->kredit - $rep->debit),0,',','.') }}
								<?php  ($rep->kredit - $rep->debit); ?>
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


			

		</div>
	</div>

@endsection
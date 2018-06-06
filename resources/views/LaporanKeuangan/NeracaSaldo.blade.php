@extends('Master.Template')

@section('meta')
	<script type="text/javascript">

		$(function(){
			$('[name="dari"]').datepicker({
				format : 'yyyy-mm-dd'
			});

			$('[name="sampai"]').datepicker({
				format : 'yyyy-mm-dd'
			});

		});

	</script>
@endsection

@section('title')
	Neraca Saldo
@endsection

@section('content')
	

	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">

			<form method="GET" action="">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="dari">Dari tanggal</label>
							<div class="input-group">
						      <input type="text" value="{{ date('Y-m-d', strtotime('-7 Day', time())) }}" class="form-control" name="dari" readonly="readonly">
						      <span class="input-group-btn">
						        <button class="btn btn-default dari-btn" type="button"><i class="fa fa-calendar"></i></button>
						      </span>
						    </div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-3">
						<div class="form-group">
							<label for="dari">Sampai Tanggal</label>
							<div class="input-group">
						      <input type="text" value="{{ date('Y-m-d') }}" class="form-control" name="sampai" readonly="readonly">
						      <span class="input-group-btn">
						        <button class="btn btn-default sampai-btn" type="button"><i class="fa fa-calendar"></i></button>
						      </span>
						    </div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-3">
						<label>&nbsp;</label>
						<button type="submit" class="btn cari btn-block btn-primary">Proses</button>
					</div>
					<div class="col-sm-3">
						<label>&nbsp;</label>
						<a href="{{ $print }}" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-print"></i></a>
					</div>
				</div>


				<div class="checkbox checkbox check-success"> <!-- <a href="#">Trouble login in?</a>&nbsp;&nbsp; -->
				  &nbsp;&nbsp;&nbsp;
				  <input type="checkbox" id="checkbox1" name="ada" value="1" {!! $ada ? 'checked="checked"' : '' !!}>
				  <label for="checkbox1">Tampilkan yang memiliki nilai saja </label>
				</div>

			</form>
			

		</div>
	</div>

	<!-- Content buku besar -->

	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">
			<table class="table">
				<thead>
					<tr>
						<th rowspan="2">KODE</th>
						<th rowspan="2">PERKIRAAN</th>
						<th rowspan="2" class="text-right">DEBIT</th>
						<th rowspan="2" class="text-right">KREDIT</th>
						<th class="text-right">SALDO DEBIT</th>
						<th class="text-right">SALDO KREDIT</th>
					</tr>
				</thead>
				<tbody>
					<?php $total_debit = 0; ?>
					<?php $total_kredit = 0; ?>
					<?php $total_debit_saldo = 0; ?>
					<?php $total_kredit_saldo = 0; ?>
					@foreach($ns as $nsal)
						<tr>
							<td>{{ $nsal->kode }}</td>
							<td>{{ $nsal->nm_coa }}</td>
							<td class="text-right">{{ $nsal->debit > 0 ? number_format($nsal->debit,0,',','.') : '-' }}</td>
							<td class="text-right">{{ $nsal->kredit > 0 ? number_format($nsal->kredit,0,',','.') : '-' }}</td>
							<td class="text-right">{{ $nsal->debit > $nsal->kredit ? number_format($nsal->debit - $nsal->kredit,0,',','.') : '-' }}</td>
							<td class="text-right">{{ $nsal->kredit > $nsal->debit ? number_format($nsal->kredit - $nsal->debit,0,',','.') : '-' }}</td>
							<?php $total_debit += $nsal->debit; ?>
							<?php $total_kredit += $nsal->kredit; ?>
							<?php $total_debit_saldo += $nsal->debit > $nsal->kredit ? ($nsal->debit - $nsal->kredit) : 0; ?>
							<?php $total_kredit_saldo += $nsal->kredit > $nsal->debit ? ($nsal->kredit - $nsal->debit) : 0; ?>
						</tr>
					@endforeach

					<tr>
						<td colspan="2"><h5 class="bold">TOTAL</h5></td>
						<td class="text-right bold"><h5 class="bold">{{ number_format($total_debit,0,',','.') }}</h5></td>
						<td class="text-right bold"><h5 class="bold">{{ number_format($total_kredit,0,',','.') }}</h5></td>
						<td class="text-right bold"><h5 class="bold">{{ number_format($total_debit_saldo,0,',','.') }}</h5></td>
						<td class="text-right bold"><h5 class="bold">{{ number_format($total_kredit_saldo,0,',','.') }}</h5></td>
					</tr>

				</tbody>
			</table>

		</div>
	</div>

@endsection
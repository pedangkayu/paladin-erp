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
	Buku Besar
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
						<label>Dari Akun</label>
						<select name="coa_dari">{!! $coa_dari !!}</select>
					</div>
					<div class="col-sm-3">
						<label>Sampai Akun</label>
						<select name="coa_sampai">{!! $coa_sampai !!}</select>
					</div>

				</div>


				<div class="row">
					
					<div class="form-group row">
						<div class="col-sm-6">
							<div class="checkbox checkbox check-success"> <!-- <a href="#">Trouble login in?</a>&nbsp;&nbsp; -->
							  &nbsp;&nbsp;&nbsp;
							  <input type="checkbox" id="checkbox1" name="all" value="1" {!! $all ? 'checked="checked"' : '' !!}>
							  <label for="checkbox1">Tampilkan semua akun </label>
							</div>
						</div>
						<div class="col-sm-3">
							<button type="submit" class="btn cari btn-block btn-primary">Proses</button>
						</div>
						<div class="col-sm-3">
							<a href="{{ $print }}" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-print"></i></a>
						</div>
					</div>		
					
				</div>

			</form>
			

		</div>
	</div>

	<!-- Content buku besar -->

	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">
			

			@forelse($leadgers as $lg)

				<?php $total_debit = 0; ?>
				<?php $total_kredit = 0; ?>
				<table class="table" style="margin-bottom:30px;border:solid 1px #ddd;">
					<tr>
						<td class="text-left bold" colspan="3">AKUN {{ strtoupper($lg[0]->nm_coa) }}</td>
						<td class="text-right bold" colspan="3">KODE AKUN {{ $lg[0]->kode }}</td>
					</tr>
					<tr>
						<th>Tanggal</th>
						<th>Kode Akun</th>
						<th>Perkiraan</th>
						<th>Keterangan</th>
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
				<table class="table" style="margin-bottom:30px;border:solid 1px #ddd;">
					<tr>
						<td class="text-left bold" colspan="3">AKUN {{ strtoupper($coa->nm_coa) }}</td>
						<td class="text-right bold" colspan="3">KODE AKUN {{ $coa->kode }}</td>
					</tr>
					<tr>
						<th>Tanggal</th>
						<th>Kode Akun</th>
						<th>Perkiraan</th>
						<th>Keterangan</th>
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

		</div>
	</div>

@endsection
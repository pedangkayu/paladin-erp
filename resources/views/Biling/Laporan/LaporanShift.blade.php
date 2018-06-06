@extends('Master.Template')

@section('meta')
<link rel="stylesheet" href="{{ asset('/plugins/bootstrap-timepicker/css/jquery.timepicker.min.css') }}" media="screen" title="no title">
<script type="text/javascript" src="{{ asset('/plugins/bootstrap-timepicker/js/jquery.timepicker.min.js') }}"></script>
<script type="text/javascript">

	$(function(){
		$('[name="tanggal"]').datepicker({
			format : 'yyyy-mm-dd'
		});

		$('.tanggal-btn').click(function(){
			$('[name="tanggal"]').val('');
		});

		$('[name="dari"]').timepicker({
				timeFormat: 'HH:mm',
				 // year, month, day and seconds are not important
				 minTime: new Date(0, 0, 0, 0, 0, 0),
				 maxTime: new Date(0, 0, 0, 23, 50, 0),
				 // time entries start being generated at 6AM but the plugin
				 // shows only those within the [minTime, maxTime] interval
				 startHour: 6,
				 // the value of the first item in the dropdown, when the input
				 // field is empty. This overrides the startHour and startMinute
				 // options
				 startTime: new Date(0, 0, 0, 8, 20, 0),
				 // items in the dropdown are separated by at interval minutes
				 interval: 10
		});
		$('[name="sampai"]').timepicker({
				timeFormat: 'HH:mm',
				 // year, month, day and seconds are not important
				 minTime: new Date(0, 0, 0, 0, 0, 0),
				 maxTime: new Date(0, 0, 0, 23, 50, 0),
				 // time entries start being generated at 6AM but the plugin
				 // shows only those within the [minTime, maxTime] interval
				 startHour: 6,
				 // the value of the first item in the dropdown, when the input
				 // field is empty. This overrides the startHour and startMinute
				 // options
				 startTime: new Date(0, 0, 0, 8, 20, 0),
				 // items in the dropdown are separated by at interval minutes
				 interval: 10
		});

		$('[name="optionshift"]').change(function(){
			var val = $(this).val();
			$('[name="id_shift_kasir"]').val(val);
		});

		$('[name="kasaoption"]').change(function(){
			var val = $(this).val();
			$('[name="shift"]').val(val);
		});

	});

</script>
@endsection

@section('title')
	Laporan Shift
@endsection

@section('content')

<div class="grid simple">
	<div class="grid-title no-border">
		<h4>Pencarian Data Transaksi Kasir</h4>
	</div>
	<div class="grid-body no-border">

			<form class="" action="" method="get">
				<input type="hidden" name="id_shift_kasir" value="0">
				<input type="hidden" name="shift" value="0">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="dari">Tanggal</label>
							<div class="input-group">
									<input type="text" value="{{ empty($req['tanggal']) ? date('Y-m-d') : $req['tanggal'] }}" class="form-control" name="tanggal" readonly="readonly">
									<span class="input-group-btn">
										<button class="btn btn-default tanggal-btn" type="button"><i class="fa fa-trash"></i></button>
									</span>
								</div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-2">
						<div class="form-group">
							<label for="dari">Dari</label>
							<div class="input-group">
									<input type="text" value="{{ empty($req['dari']) ? '00:00' : $req['dari'] }}" class="form-control" name="dari" readonly="readonly">
								</div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-2">
						<div class="form-group">
							<label for="dari">Sampai</label>
							<div class="input-group">
									<input type="text" value="{{ empty($req['sampai']) ? '23:50' : $req['sampai'] }}" class="form-control" name="sampai" readonly="readonly">
								</div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-1">
						<div class="form-group">
							<label for="dari">&nbsp;</label>
							<div class="input-group">
								<button type="submit" class="btn btn-default btn-block">Cari</button>
							</div><!-- /input-group -->
						</div>
					</div>

					<div class="col-sm-1">
						<div class="form-group">
							<label for="dari">&nbsp;</label>
							<div class="input-group">
								<a href="{{ $link }}" class="btn btn-default btn-block" target="_blank">Print</a>
							</div><!-- /input-group -->
						</div>
					</div>
				</div>
			</form>
		</div>
</div>

	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="dari">Pilih Nama Kasir</label>
								<select class="form-control" name="optionshift">
									<option value="0">- Semua -</option>
									@foreach($kasir as $item)
										<option value="{{ $item->id_shift_kasir }}" {{ !empty($req['id_shift_kasir']) && $req['id_shift_kasir'] == $item->id_shift_kasir ? 'selected' : '' }}>{{ $item->nm_depan }} {{ $item->nm_belakang }} [Kassa {{ $item->shift }}]</option>
									@endforeach
								</select>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="form-group">
							<label for="dari">Pilih Kassa</label>
								<select class="form-control" name="kasaoption">
									<option {{ !empty($req['shift']) && $req['shift'] == 0 ? 'selected' : '' }} value="0">Semua Kassa</option>
									<option {{ !empty($req['shift']) && $req['shift'] == 1 ? 'selected' : '' }} value="1">Kassa 1</option>
									<option {{ !empty($req['shift']) && $req['shift'] == 2 ? 'selected' : '' }} value="2">Kassa 2</option>
									<option {{ !empty($req['shift']) && $req['shift'] == 3 ? 'selected' : '' }} value="3">Kassa 3</option>
									<option {{ !empty($req['shift']) && $req['shift'] == 4 ? 'selected' : '' }} value="4">Kassa 4</option>
								</select>
						</div>
					</div>
				</div>

				<table class="table table-hover table-striped daftar-prq">
						<thead>
							<tr>
								<th>Nama Kasir</th>
								<th>Tangal</th>
								<th>Kassa</th>
								<th>(A) Saldo Awal</th>
								<th>(B) Saldo Kembali</th>
								<th>(C) Pendapatan</th>
								<th>Status</th>
							</tr>
						</thead>

						<tbody>
								@forelse($kasir as $item)
									@if(!empty($req['id_shift_kasir']) > 0 && $req['id_shift_kasir'] == $item->id_shift_kasir)
									<tr>
										<td>{{ $item->nm_depan }} {{ $item->nm_belakang }}</td>
										<td>{{ Format::indoDate2($item->created_at) }}</td>
										<td>{{ $shifts[$item->shift] }}</td>
										<td>{{ number_format($item->saldo_awal,0,',','.') }}</td>
										<td>{{ number_format($item->saldo_kembali,0,',','.') }}</td>
										<td>{{ number_format($item->pendapatan_kassa,0,',','.') }}</td>
										<td><span class="label {{  $item->status ? 'label-important' : 'label-success' }}">{{ $status[$item->status] }}</span></td>
									</tr>
									@elseif(!empty($req['id_shift_kasir']) == 0)
									<tr>
										<td>{{ $item->nm_depan }} {{ $item->nm_belakang }}</td>
										<td>{{ Format::indoDate2($item->created_at) }}</td>
										<td>{{ $shifts[$item->shift] }}</td>
										<td>{{ number_format($item->saldo_awal,0,',','.') }}</td>
										<td>{{ number_format($item->saldo_kembali,0,',','.') }}</td>
										<td>{{ number_format($item->pendapatan_kassa,0,',','.') }}</td>
										<td><span class="label {{  $item->status ? 'label-important' : 'label-success' }}">{{ $status[$item->status] }}</span></td>
									</tr>
									@endif

								@empty
									<tr>
										<td colspan="7">Tidak ditemukan</td>
									</tr>
								@endforelse
						</tbody>
				</table>
			</div>
	</div>

<div class="grid simple">
  <div class="grid-title no-border"></div>
  <div class="grid-body no-border">

				<?php $jumlahTotal = 0; ?>

        @forelse($header as $header)
				<table class="table table-bordered">
		      <tbody>
	          <tr>
	            <th>PENDAPATAN DARI {{ strtoupper($header->keterangan) }}</th>
		          <th>NAMA PELANGGAN</th>
		          <th>TGL KWITANSI</th>
							<th>KASIR</th>
		          <th class="text-right">JUMLAH KWITANSI</th>
	          </tr>
						<?php $total = 0; ?>
						@foreach($items[$header->keterangan] as $item)
							<tr>
								<td>#{{ $item->nomor_faktur }}</td>
								<td>{{ $item->nama_pasien }}</td>
								<td>{{ Format::indoDate2($item->tgl_kwitansi) }}</td>
								<td>{{ $header->nm_depan }} {{ $header->nm_belakang }}</td>
								<td class="text-right">{{ number_format($item->jumlah,0,',','.') }}</td>
							</tr>
							<?php $total += $item->jumlah; ?>
						@endforeach

						<tfoot>
							<tr>
								<th class="text-right" colspan="4">TOTAL</th>
								<th class="text-right">{{ number_format($total,0,',','.') }}</th>
								<?php $jumlahTotal += $total; ?>
							</tr>
						</tfoot>

					</tbody>

				</table>
        @empty
					<div class="well">
						Data Transaksi Pembayaran Tidak ditemukan
					</div>
        @endforelse

				<table class="table table-bordered">
					<tr>
						<th width="85%" class="text-right">JUMLAH TOTAL</th>
						<th width="15%" class="text-right">{{ number_format($jumlahTotal,0,',','.') }}</th>
					</tr>
				</table>

  </div>
</div>

@endsection

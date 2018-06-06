@extends('Master.Template')

@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/js/Deposit/create.js') }}"></script>
<script type="text/javascript">
$(function(){
		// date pic
		var checkin = $('#dpd1').datepicker({
			format : 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
			if (ev.date.valueOf() > checkout.date.valueOf()) {
				var newDate = new Date(ev.date)
				newDate.setDate(newDate.getDate() + 1);
				checkout.setValue(newDate);
			}
			checkin.hide();
			$('#dpd2')[0].focus();
		}).data('datepicker');

		var checkout = $('#dpd2').datepicker({
			onRender: function(date) {

				return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
			},
			format : 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
			checkout.hide();
		}).data('datepicker');

		// date pic /////////////////////////////////////////
		var checkinb = $('#dpd3').datepicker({
			format : 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
			if (ev.date.valueOf() > checkoutb.date.valueOf()) {
				var newDate = new Date(ev.date)
				newDate.setDate(newDate.getDate() + 1);
				checkoutb.setValue(newDate);
			}
			checkinb.hide();
			$('#dpd4')[0].focus();
		}).data('datepicker');

		var checkoutb = $('#dpd4').datepicker({
			onRender: function(date) {
				return date.valueOf() <= checkinb.date.valueOf() ? 'disabled' : '';
			},
			format : 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
			checkoutb.hide();
		}).data('datepicker');

	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#id_karyawan").select2();

	});
$(function(){
		$('#id_karyawan').select2({
			placeholder: "Pilih metode...."
		});

	});
</script>


@endsection
@section('title')
Pengembalian Pinjaman
@endsection

@section('content')

<div class="row">
	<!-- left -->
	<div class="col-sm-12">

		<div class="grid simple header-status">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">

				<div class="row">
					<div class="col-sm-7">

                        <div class="grid-title no-border"></div>

						<form action="{{ url('Pinjaman/kembali') }}" method="post">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="id_loan" value="{{$data->id_loan}}">
							<div class="col-md-8 col-sm-8 col-xs-8"><!-- start form -->

								<div class="form-group">
									<div class="form-label">Nama Pemohon</div>
									<div class="controls">
										<select class="form-control select2" name="id_karyawan" id="id_karyawan">
											<!-- <option value=""> - Pilih -</option> -->
											@foreach($data_karyawan as $datas)
												<option value="{{ $datas->id_karyawan }}" {{$datas->id_karyawan==$data->id_karyawan ? 'selected="selected"' : ''}}> {{ $datas->nm_depan }} {{$datas->nm_belakang}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="form-label">Nominal Pinjaman Awal</div>
									<div class="control">
										<input type="number" name="nominal" value="{{$data->nominal}}" readonly="readonly" class="form-control col-xs-8" required>
										<input type="hidden" name="total_terbayar" value="{{$data->total_terbayar}}">
									</div>
								</div>
								<div class="form-group">
									<div class="form-label">Nominal Pengembalian</div>
									<div class="control">
										<input type="number" name="pengembalian" value="" max="{{($data->nominal - $data->total_terbayar) }}" class="form-control col-xs-8" placeholder="sisa Hutang Anda{{($data->nominal - $data->total_terbayar) }}" required>
									</div>
								</div>
								<div class="form-group">
										<?php $rand = rand(11111111,999999999); ?>
										<label>Metode Pembayaran *</label>
										<select name="tipe" onchange="getdata({{ $rand }}, this.value);" style="width:100%;" required>
											<option value="">- Pilih -</option>
											<option value="1" > Bank </option>
											<option value="3" > Cash </option>
										</select>
									</div>

									<div class="form-group" data-tipemethod="{{ $rand }}">
										<label>Akun Bank</label>
										<select name="method" id="method" style="width:100%;" data-valmethod="{{ $rand }}">
											<option value="">- Pilih -</option>
												@foreach($banks as $bank)
													<option value="{{ $bank->id_bank }}" >{{ $bank->nm_bank }}</option>
												@endforeach
										</select>
									</div>
								<div class="form-group">
									<div class="form-label">Tanggal Perbarui</div>
									<div class="control">
										<div class="input-append success date col-md-10 col-lg-6 no-padding">
											<input type="text" name="datetime_in" readonly="readonly" class="form-control" data-provide="datepicker" value="{{ date('m/d/Y') }}"><span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
										</div>
									</div>
								</div>
								<div class="grid-title no-border"></div>

								<div class="form-group">
									<div class="form-label">Keterangan</div>
									<div class="control">
										<textarea class="form-control" name="keterangan"></textarea>
									</div>
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-primary">Simpan</button>
								</div>
							</div>
						</form>
					</div>

					<div class="col-sm-5 text-right">
							<div class="grid-title no-border"></div>
							<div class="text-right">
							  <a href="{{ url('/Pinjaman') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
							</div>
				    </div>
			</div>

		</div>
	</div>

</div>

<!-- right -->


</div>
<!-- Modal -->
@endsection

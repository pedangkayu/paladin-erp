@extends('Master.Template')

@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<!-- <script type="text/javascript" src="{{ asset('/js/Pinjaman/create.js') }}"></script> -->
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
Permohonan Pinjaman
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

						<form action="{{ url('Pinjaman/create') }}" method="post">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="col-md-8 col-sm-8 col-xs-8"><!-- start form -->

								<div class="form-group">
									<div class="form-label">Nama Pemohon</div>
									<div class="controls">
										<select class="form-control select2" name="id_karyawan" id="id_karyawan">
											<option value=""> - Pilih -</option>
											@foreach($karyawan as $datas)
												<option value="{{ $datas->id_karyawan }}"> {{ $datas->nm_depan }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="form-label">Nominal Pinjaman</div>
									<div class="control">
										<input type="number" name="nominal" class="form-control col-xs-8" required>
									</div>
								</div>
								<div class="form-group">
									<div class="form-label">Lama Pinjaman</div>
									<p><small>Minimal Pinjaman 1 Bulan</small></p>
									<div class="input-group">
										<input type="text" name="dari" class="form-control col-xs-8" readonly="readonly" value="{{ date('Y-m-d') }}" id="dpd1">
										<span class="input-group-addon">Sampai</span>
										<input type="text" name="sampai" class="form-control" readonly="readonly" value="{{ date('Y-m-d', strtotime('+30 day', time())) }}" id="dpd2">
									</div>
								</div>
								<div class="grid-title no-border"></div>
								<div class="form-group">
									<div class="form-label">Tanggal</div>
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
										<textarea class="form-control" name="keterangan" required></textarea>
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
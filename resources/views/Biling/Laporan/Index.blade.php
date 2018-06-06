@extends('Master.Template')

@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection

@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(function(){
			// close_sidebar();

			$('[name="limit"]').change(function(){
				var val = $(this).val();
				if(val < 5)
					$(this).val(5);
			});
			//====radio excel===
			$('.radio-src').click(function(){
				var val = $(this).val();
				if(val == 1){
					$('.no_excel').addClass('hide');
					$('.excel').removeClass('hide');
				}else{
					$('.excel').addClass('hide');
					$('.no_excel').removeClass('hide');
				}
			});
			//=====radio excel
			$('.waktu-src').click(function(){
				var val = $(this).val();
				if(val == 1){
					$('.pertanggal').addClass('hide');
					$('.perbulan').removeClass('hide');
				}else{
					$('.perbulan').addClass('hide');
					$('.pertanggal').removeClass('hide');
				}
			});

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

			$('form').submit(function(){
				$('[type="submit"]').button('reset');
				$('body').css('cursor', 'default');
			});


			$('.btn-proses').click(function(){

				var bulan 	= $('[name="bulan"]').val();
				var tahun 	= $('[name="tahun"]').val();
				var dari 	= $('[name="dari"]').val();
				var sampai 	= $('[name="sampai"]').val();
				var waktu 	= $('[name="waktu"]:checked').val();
				var jurnal 	= $('[name="jurnal"]').val();

				var id_vendor 	= $('[name="id_vendor"]').val();
				var id_pasien 	= $('[name="id_pasien"]').val();
				var id_coa 		= $('[name="id_coa"]').val();

				switch(jurnal){

					case "0":
						data = {
							bulan : bulan,
							tahun : tahun,
							dari : dari,
							sampai : sampai,
							waktu : waktu,
							jurnal : jurnal
						};		
					break;

					case "1":
						data = {
							bulan : bulan,
							tahun : tahun,
							dari : dari,
							sampai : sampai,
							waktu : waktu,
							jurnal : jurnal,
							id_vendor : id_vendor
						};		
					break;


					case "2":
						data = {
							bulan : bulan,
							tahun : tahun,
							dari : dari,
							sampai : sampai,
							waktu : waktu,
							jurnal : jurnal,
							id_pasien : id_pasien
						};		
					break;

					case "3":
						data = {
							bulan : bulan,
							tahun : tahun,
							dari : dari,
							sampai : sampai,
							waktu : waktu,
							jurnal : jurnal,
							id_coa : id_coa
						};		
					break;

				}

				

				$('.content-laporan').html('\
					<tr>\
						<td colspan="10">Memuat...</td>\
					</tr>\
				');
				$.getJSON(_base_url + '/biling/loadlaporan', data, function(json){
					$('.content-laporan').html(json.content);
				});

			});



			$('[name="jurnal"]').change(function(){
				var val = $(this).val();
				$('.tipe_jurnal').html('Memuat...');
				switch (val){
					case "0":
						$('.tipe_jurnal').html('');
					break;

					case "1":
						$.getJSON(_base_url + '/biling/vendors', {}, function(json){
							$('.tipe_jurnal').html(json.content);
							$('[name="id_vendor"]').select2();
						});
					break;

					case "2":
						$.getJSON(_base_url + '/biling/selectpasien', {}, function(json){
							$('.tipe_jurnal').html(json.content);
							$('[name="id_pasien"]').select2();
						});
					break;


					case "3":
						$.getJSON(_base_url + '/biling/selectakun', {}, function(json){
							$('.tipe_jurnal').html(json.content);
							$('[name="id_coa"]').select2();
						});
					break;
				}
				
			});

		});
</script>
@endsection

@section('title')
Jurnal Transaksi
@endsection

@section('content')


<form  method="get" action="{{url('/biling/printlaporan')}}" target="_blank">
	<div class="row">
		<!-- left -->
		<div class="col-sm-7">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>Pencarian</h4>
				</div>
				<div class="grid-body no-border">
					
					<div class="row">
						<div class="col-sm-12">

							<div class="perbulan">
								<div class="row">
									<div class="col-sm-7">
										<div class="form-group">
											<label for="bulan">Bulan</label>
											<select class="select" style="width:100%;" name="bulan" id="bulan">
												@for($i=1;$i<13;$i++)
												<option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>{{ Format::nama_bulan($i) }}</option>
												@endfor
											</select>
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label for="tahun">Tahun</label>
											<select class="select text-right" style="width:100%;" name="tahun" id="tahun">
												@for($i = 2000; $i <= date('Y'); $i++)
												<option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
												@endfor
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="pertanggal hide">
								<div class="form-group">
									<label>Tentukan tanggal</label>
									<div class="input-group">
										<input type="text" name="dari" class="form-control" readonly="readonly" value="{{ date('Y-m-d') }}" id="dpd1">
										<span class="input-group-addon">s/d</span>
										<input type="text" name="sampai" class="form-control" readonly="readonly" value="{{ date('Y-m-d', strtotime('+30 day', time())) }}" id="dpd2">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 tipe_jurnal"></div>								
							</div>

						</div>

					</div>
				</div>
			</div>

		</div>

		<div class="col-sm-5">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border text-center">
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group text-left">
								<label>Waktu</label>
								<div class="radio">
									<input type="radio" name="waktu" class="waktu-src" value="1" id="bln" checked>
									<label for="bln">Per Bulan</label>

									<input type="radio" name="waktu" class="waktu-src" value="2" id="tgl">
									<label for="tgl">Per Tanggal</label>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label>Jurnal</label>
								<select name="jurnal" style="width:100%;">
									<option value="0">Semua Jurnal</option>
									<option value="1">Supplier</option>
									<option value="2">Pasien</option>
									<option value="3">Akun</option>
								</select>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-8">
							<button type="button" class="btn btn-primary btn-block btn-proses" data-loading-text="Loading...">Proses</button>
						</div>
						<div class="col-sm-4">
							<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-print"></i></button>
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
		
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No.</th>
					<th class="text-middle">Tanggal</th>
					<th class="text-middle">Akun</th>
					<th class="text-middle">Perkiraan</th>
					<th class="text-center">Keterangan</th>
					<th class="text-center">Debit</th>
					<th class="text-center">Kredit</th>
				</tr>
			</thead>

			<tbody class="content-laporan">
				<tr>
					<td colspan="10">Silakan lakukan pencarian</td>
				</tr>
			</tbody>

		</table>
		<div class="pagin text-center"></div>
	</div>
</div>

@endsection
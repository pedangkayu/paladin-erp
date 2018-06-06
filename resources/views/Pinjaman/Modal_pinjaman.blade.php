
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
<script src="{{ asset ('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>

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
  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<form method="post" action="{{ url('/Pinjm/edit') }}" id="submit_payment"> <!--  Form submit -->

		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Edit Pinjaman {{$item->nd}} {{$item->nb}}</h4>
		      </div>
		      <div class="modal-body">
		        
		      		<div class="grid simple">
						<div class="grid-title no-border"></div>
						<div class="grid-body no-border">
							
						</div>
					</div>
					<div class="grid simple">
		                <div class="grid-title no-border"></div>
		                <div class="grid-body no-border">
		                    <div class="row">
		                    	<div class="col-sm-12 text-center">
		                    		<b>Detail Pemohon Pinjaman {{$item->nd}} {{$item->nb}}</b>
		                    	</div>
		                        <div class="col-sm-6 ">
		                            <div class="form-group">
		                            <div class="form-label">Nama Pasien *</div>
										<div class="controls">
			                                <select class="form-control select2"   name="id_karyawan" id="id_karyawan">
												<option value=""> - Pilih -</option>
												@foreach($karyawan as $datas)
														<?php ?>
													<option value="{{ $datas->id_karyawan }}" {{$datas->id_karyawan == $item->id_karyawan  ? 'selected' : ''}}> {{ $datas->nm_depan }}</option>
												@endforeach
											</select>
										</div>
		                            </div>
		                        </div>
			                    <div class="col-sm-6 ">
			                        <div class="form-group">
			                              <div class="form-label">Nominal *</div>
										<div class="controls">
			                                <input type="text" name="nominal" value="{{$item->nominal}}" class="form-control">
										</div>
		                            </div>
			                    </div>
			                    <div class="col-sm-12 ">
			                        <div class="form-group">
			                        	<div class="form-label">Lama Pinjaman</div>
										<p><small>Minimal Pinjaman 1 Bulan</small></p>
										<div class="input-group">
											<input type="text" name="dari" class="form-control col-xs-8" readonly="readonly" value="{{ date('Y-m-d') }}" id="dpd1">
											<span class="input-group-addon">Sampai</span>
											<input type="text" name="sampai" class="form-control" readonly="readonly" value="{{ date('Y-m-d', strtotime('+30 day', time())) }}" id="dpd2">
										</div>
			                        </div>
			                    </div>
			                    <div class="col-sm-12">
			                    	<div class="form-group"><b>Telpon  :</b>{{$item->telp}}</div>
			                    </div>
			                    <div class="col-sm-12">
			                    	<div class="form-group"><b>Nominal Pinjaman  :</b>{{number_format($item->nominal,0,',','.')}}   </div>
			                    </div>
			                    <div class="col-sm-12">
			                    	<div class="form-group"><b>Durasi Pinjaman  :</b>{{Format::indoDate2($item->start_time) }}  {{ Format::hari($item->start_time) }}   s/d    {{Format::indoDate2($item->end_time)}}{{ Format::hari($item->end_time) }}  </div>
			                    </div>
		                    </div>
		                </div>
	           	 	</div>

		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary">Payment</button>
		      </div>

	    </form> <!-- end Form submit -->

	    </div>
	  </div>
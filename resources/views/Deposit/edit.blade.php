@extends('Master.Template')

@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/js/Deposit/create.js') }}"></script>

<script type="text/javascript">
	$(document).ready(function() { 
		$("#id_pasien_hc").select2(); 

	});
$(function(){
		$('#method').select2({
			placeholder: "Pilih metode...."
		});
		
	});
</script>


@endsection
@section('title')
Tambah Saldo Pasien
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
				<li class="active"><a href="#">Tambah Saldo Pasien</a></li>
				<li><a href="{{ url('Deposit/') }}">Saldo Pasien</a></li>
				
			</ul>
			<div class="tab-content">

				<div class="tab-pane active" id="status_aktif">
					<div class="row">
						<div class="grid simple">
							<div class="grid-title no-border">
								<h4>Tambah Nominal Saldo Pasien</h4>
								<div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
							</div>
							<div class="grid-body no-border"> <br>
								<div class="row">
									<form action="{{ url('Deposit/edit') }}" method="post">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<div class="col-md-8 col-sm-8 col-xs-8">
											<div class="form-group">
												<label class="form-label">Nama Pasien</label>
												<div class="controls">
													<select class="form-control select2" name="id_pasien_hc" id="id_pasien_hc">
														<option value="">-Pilih-</option>
														@foreach($data as $datas)
														<option value="{{ $datas->id_pasien_hc }}"> {{ $datas->nama_pasien }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="form-label">Nominal</label>
												<div class="control">
													<input type="number" name="nominal" class="form-control col-xs-8" >
													<input type="hidden" name="keluar" value="">
												</div>
											</div>
										<div class="form-group">
											<?php $rand = rand(11111111,999999999); ?>
											<label>Jenis Pembayaran</label>
											<select name="tipe" onchange="getdata({{ $rand }}, this.value);" style="width:100%;" required>
												<option value="">- Pilih TIpe -</option>
												<option value="1" > BANK </option>
												<option value="3" > Cash </option>
											</select>
										</div>

										<div class="form-group" data-tipemethod="{{ $rand }}">
											<label>Method</label>
											<select name="method" id="method" style="width:100%;" data-valmethod="{{ $rand }}">
												<option value="">- Pilih Method -</option>
													@foreach($banks as $bank)
														<option value="{{ $bank->id_bank }}" >{{ $bank->nm_bank }}</option>
													@endforeach
												
											</select>
										</div>

											<div class="form-group">
												<div class="form-label">Tanggal</div>
												<div class="control">
													<div class="input-append success date col-md-10 col-lg-6 no-padding">
														<input type="text" name="datetime_in" readonly="readonly" class="form-control" data-provide="datepicker" value="{{ date('m/d/Y') }}">
														<span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span> 
													</div>
												</div>
											</div>
											<br><br>
										</div>
											</div>
											<div class="form-group">
												<label class="form-label">Keterangan</label>
												<textarea class="form-control" name="keterangan"></textarea>
											</div>
											<div class="form-group">
												<button class="btn btn-primary">Simpan</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
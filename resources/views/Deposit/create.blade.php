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
Setoran Deposit Pasien
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

						<form action="{{ url('Deposit/create') }}" method="post">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="col-md-8 col-sm-8 col-xs-8"><!-- start form -->

								<div class="form-group">
									<div class="form-label">Nama Pasien</div>
									<div class="controls">
										<select class="form-control select2" name="id_pasien_hc" id="id_pasien_hc">
											<option value=""> - Pilih -</option>
											@foreach($data as $datas)
											<option value="{{ $datas->id_pasien_hc }}"> {{ $datas->nama_pasien }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="form-group">
									<?php $rand = rand(11111111,999999999); ?>
									<div class="form-label">Metode Pembayaran</div>
									<select name="tipe" onchange="getdata({{ $rand }}, this.value);" style="width:100%;" required>
										<option value="0"> - Pilih - </option>
										<option value="1"> Bank </option>
										<option value="3"> Cash </option>
									</select>
								</div>

								<div class="form-group" data-tipemethod="{{ $rand }}">
									<div class="form-label">Akun Bank</div>
									<select name="method" id="method" style="width:100%;" data-valmethod="{{ $rand }}">
										<option value="">- Pilih -</option>
											@foreach($banks as $bank)
												<option value="{{ $bank->id_bank }}" >{{ $bank->nm_bank }}</option>
											@endforeach
									</select>
								</div>

								<div class="form-group">
									<div class="form-label">Nominal Setoran</div>
									<div class="control">
										<input type="number" name="nominal" class="form-control col-xs-8" >
										<input type="hidden" name="keluar" value="">
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
							  <a href="{{ url('/Deposit') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
							</div>		 
				    </div>
			</div>

		</div>
	</div>

</div>

<!-- right -->
 

</div>

@endsection

@section('footer')
<!-- Modal -->
@endsection
@extends('Master.Template')

@section('meta')
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
	<script type="text/javascript" src="{{ asset('/plugins/autocomplete/bootstrap3-typeahead.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/js/biling/create.js') }}"></script>
	<style type="text/css">
		.btn-bayar:focus{
			background: #333;
		}
	</style>
@endsection

@section('title')
	Biling 
@endsection

@section('content')
	
	<form method="post" action="{{ url('/biling/create') }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<div class="row">
			<!-- left -->
			<div class="col-sm-9">
				
				<div class="grid simple">
					<div class="grid-title no-border">
						 
					</div>

					<div class="grid-body no-border">
						
						<div class="row">
							<div class="col-sm-5">
								<div class="form-group">
							    	<input type="text" tabindex="1" class="input-lg" id="pasien" name="nm_pasien" placeholder="Nama Pasien" style="width:100%;" required>
							  	</div>

							  	<div class="form-group">
							    	<input type="text" id="no_registrasi" placeholder="No Registrasi" style="width:100%;" readonly="readonly">
							  	</div>

							  	<div class="form-group">
							    	<input type="text" id="tgl_lahir" placeholder="Tanggal Lahir" style="width:100%;" readonly="readonly">
							  	</div>

							  	<div class="form-group">
							    	<label for="pasien">Alamat</label>
							    	<textarea style="resize:none;" rows="3" readonly="readonly" class="form-control" name="alamt" id="alamat"></textarea>
							  	</div>

							  	<input type="hidden" name="id_pasien" value="{{ $data['id_pasien'] }}">
							</div>


							<div class="col-sm-7">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="terms">Payment Terms *</label>
											<select name="terms" id="terms" class="form-control">
												@foreach($data['terms'] as $term)
												<option value="{{ $term->id_payment_terms }}">{{ $term->payment_terms }}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label for="tanggal">Tanggal Faktur *</label>
											<div class="input-group transparent">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" value="{{ date('m/d/Y') }}" name="tgl_faktur" id="tanggal" class="form-control" readonly="readonly">
											</div>
										</div>

										<div class="form-group">
											<div class="form-group">
												<label for="duodate">Tanggal Jatuh Tempo *</label>
												<div class="input-group transparent">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" value="{{ date('m/d/Y', strtotime('+1 month')) }}" name="duodate" id="duodate" class="form-control" readonly="readonly">
												</div>
											</div>
										</div>
										
									</div>
									<div class="col-sm-6">
										
										<div class="form-group">
											<label>Prefix Faktur</label>
											<input type="text" name="prefix">
										</div>

										<div class="form-group">
									    	<label for="pasien">Diskon <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Diskon ini akan mengeset keseluruhan diskon di bawah"><i class="glyphicon glyphicon-question-sign"></i></a></label>
									    	<div class="input-group">
												<input type="text" name="diskon_all" value="0" class="text-right form-control" />
												<span class="input-group-addon">%</span>
											</div>
									  	</div>	

									  	<div class="form-group">
									    	<label for="pasien">
									    		+/- Pembulatan
									    		<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Pembulatan harga total bisa diisi dengan +/-"><i class="glyphicon glyphicon-question-sign"></i></a>
									    	</label>
									    	<input type="text" name="adjustment" value="0" class="text-right form-control" />
									  	</div>

									</div>
								</div>


							</div>
						</div>

                        <div style="float: left; width: 40%;">
						    <div class="btn btn-warning btn-cons hide verified"><i class="fa fa-check-circle"></i> Data Pembayaran Sudah Valid</div>
							<a href="javascript:void(0);" onclick="validasi()" class="btn hide btn-info btn-validasi">Proses Validasi</a>
                        </div>

                        <div style="float: right; width: 60%;">
							<button type="button" class="btn btn-default btn-add-tindakan"><i class="fa fa-plus"></i></button>
							<button type="button" tabindex="2" class="btn btn-default btn-reload hide" onclick="load_paket();"><i class="fa fa-refresh"></i> Reload Paket</button>
							<button type="submit" tabindex="2" class="btn btn-danger btn-bayar"><strong>Proses Pembayaran</strong></button>
							<button type="button" class="btn btn-danger btn-hitung hide" data-loading-text="Menghitung..."><strong>Hitung Ulang</strong></button>
                        </div>
                        <div style="clear: both;"></div>

					</div>
				</div>
			</div>

			<!-- right -->
			<div class="col-sm-3">

				<div class="grid simple">
					<div class="grid-title no-border"></div>

					<div class="grid-body no-border">
						<div>Subtotal</div>
						<h4 class="total load">RP 0</h4>
						<input type="hidden" name="subtotal" value="0">
						<input type="hidden" name="grandtotal" value="0">

						<div>Total</div>
						<h3 class="grandtotal load">RP 0</h3>
						
						<div class="form-group">
							<label for="ket">Keterangan</label>
							<textarea class="form-control" name="keterangan" id="ket"></textarea>
						</div>

						<p> <a class="btn btn-primary btn-block" href="{{ url('/biling') }}">Daftar Biling</a></p>
						<p> <a class="btn btn-primary btn-block" href="{{ url('/biling/listpasien') }}">Pasien</a></p>

					</div>
				</div>

			</div>
			
		</div>

		<div class="table-responsive content-item-add"></div>
		<div class="table-responsive content-item"></div>

	</form>
@endsection
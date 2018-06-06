@extends('Master.Template')
@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection

@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/akunting/fakturpendapatan/baru.js') }}"></script>
<script type="text/javascript">
	$(function () {
		// body...
		customers();
	});
</script>
@endsection

@section('title')
Faktur Pendapatan 
@endsection

@section('content')
<form method="post" action="{{ url ('/fakturpendapatan/baru')}}">
	<input type="hidden" name="_token" value="{{ csrf_token()}}">
	<div class="row">
		<div class="col-sm-12">

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="row">
						<div class="col-sm-6">
                            <div class="form-group">
								<label for="customer">Customer *</label>
								<div class="input-group">
									<select name="customer" id="customer" style="width:100%;" required>
										<option value="">Memuat...</option> 
									</select>

								    <span class="input-group-btn">
								        <button class="btn btn-white" data-toggle="modal" data-target="#pelanggan" title="Tambahkan Customer bila tidak ada"><i class="fa fa-plus"></i></button><!-- /input-data-supplier -->
								    </span>
							    </div>
							</div>

							<div class="form-group">
								<label for="alamat">Alamat *</label>
								<textarea class="form-control" name="alamat" rows="4" readonly="readonly"></textarea>
							</div>

						</div>

						<div class="col-sm-6">
							<div class="row form-row">
								<div class="col-md-6">
									<label for="no_faktur">Nomor Faktur *</label>
									<input name="no_faktur" id="no_faktur" type="text"  class="form-control" value="-" readonly="readonly">
								</div>
								<div class="col-md-6">
									<label for="prefix">Prefix Faktur</label>
									<input name="prefix" id="prefix" type="text"  class="form-control">
								</div>
							</div>

							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="tanggal">Tanggal Faktur *</label>
										<div class="input-group transparent">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" value="{{ date('m/d/Y') }}" name="tanggal" id="tanggal" class="form-control" readonly="readonly">
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="duodate">Tanggal Jatuh Tempo *</label>
										<div class="input-group transparent">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" value="{{ date('m/d/Y', strtotime('+1 month')) }}" name="duodate" id="duodate" class="form-control" readonly="readonly">
										</div>
									</div>
								</div>
							</div>

						
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="ppn">PPN (%)</label>
										<input type="number" name="ppn" id="ppn" class="form-control text-right">
										<input type="hidden" name="total_ppn" value="0">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="terms">Payment Terms *</label>
										<select name="terms" id="terms" class="form-control">
											@foreach($terms as $term)
											<option value="{{ $term->id_payment_terms }}">{{ $term->payment_terms }}</option>
											@endforeach
										</select>
									</div>	
									<input type="hidden" name="diskon" id="diskon" class="form-control text-right">	
								</div>
							</div>


						</div>
					</div>
					<!-- End Input Header -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									{{-- <th width="15%">Kode</th> --}}
									<th width="30%" class="text-left">Barang</th>
									<th width="15%" class="text-right">Qty</th>
									<th width="10%" class="text-right">Disc (%)</th>
									<th width="15%" class="text-right">Harga</th>
									<th width="15%" class="text-right">Total</th>
								</tr>
							</thead>

							<tbody class="content-item"></tbody>
						</table>
					</div>

					<!-- footer  -->
					<div class="row" style="padding:10px 0;">
						<div class="col-sm-7">
							<div class="form-group">
								<button type="button" class="btn btn-primary add-new-blank"><i class="fa fa-plus"></i> Tambah Item Faktur</button>
								<button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus</button>
								<input type="hidden" name="id_delete" value="0">
							</div>

							<div class="form-group">
								<label for="keterangan">Catatan :</label>
								<textarea name="keterangan" id="keterangan" class="form-control" rows="5"></textarea>
							</div>

						</div>
						<div class="col-sm-5">
							<table class="table table-striped">
								<tr>
									<td width="30%" class="text-right"><strong>Sub Total :</strong></td>
									<td width="70%" class="faktur-subtotal text-right">0,00</td>
								</tr>
								<!-- <tr>
									<td class="text-right"><strong>Diskon :</strong></td>
									<td class="faktur-diskon text-right">0,00</td>
								</tr> -->
								<tr>
									<td class="text-right"><strong>PPN :</strong></td>
									<td class="faktur-ppn text-right">0,00</td>
								</tr>
								<!-- <tr>
									<td class="text-right"><strong>PPh :</strong></td>
									<td class="faktur-pph text-right">-</td>
								</tr> -->
								<tr valign="center">
									<td class="text-right"><strong>Adjustment :</strong></td>
									<td class="faktur-adjustment text-right">
										<input type="number" value="0" name="adjustment" class="form-control text-right">
									</td>
								</tr>
								<tr>
									<td class="text-right"><strong>Total :</strong></td>
									<td class="faktur-total text-right">0,00</td>
								</tr>
							</table>
							<input type="hidden" name="subtotal" value="0">
							<input type="hidden" name="grandtotal" value="0">
						</div>
					</div>

					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-2">
								<a href="{{ url('/fakturpembelian') }}" class="btn btn-default btn-block">Batal</a>
							</div>
							<div class="col-sm-offset-7 col-sm-3">
								<button class="btn btn-primary btn-block" type="submit">Simpan</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</form>
@endsection

@section('footer')

<div class="modal fade" id="pelanggan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Tambah Customer</h4>
			</div>
			<div class="modal-body">
				<!-- content -->
				<div class="grid simple">
					<div class="grid-title no-border"><h4></h4></div>
					<div class="grid-body no-border">
						<div class="form-group">
							<label class="form-label" for="nm_payerr">Nama Depan Customer *</label>
							<span class="help">e.g. "Sebastian"</span>
							<div class="controls">
								<input type="text" class="form-control" name="nm_payer" data-toggle="input" data-toggle="input" data-name="nm_payer" id="nm_payer" value="{{ old('nm_payer') }}" required>
								<small class="text-muted"></small>
							</div>
						</div>

						<div class="form-group">
							<label class="form-label" for="nm_last">Nama Belakang Customer *</label>
							<span class="help">e.g. "Ma"</span>
							<div class="controls">
								<input type="text" class="form-control" data-toggle="input" data-name="nm_last" name="nm_last" id="nm_last" value="{{ old('nm_last') }}" required>
							</div>
						</div>

						<div class="form-group">
							<label class="form-label" for="alamat">Alamat *</label>
							<span class="help"></span>
							<div class="controls">
								<textarea type="text" name="alamat_customer" data-toggle="input" data-name="alamat" id="alamat" required class="form-control" rows="6">{{ old('alamat') }}</textarea>
								<small class="text-muted">* Alamat harus yang lengkap, cantumkan Kode POS</small>
							</div>
						</div>

						<div class="form-group">
							<label class="form-label" for="telpon">Telpon *</label>
							<span class="help">e.g. "022 754321 / 022 1234567"</span>
							<div class="controls">
								<input type="text" class="form-control" data-toggle="input" data-name="telpon" id="telpon" name="telpon" value="{{ old('telpon') }}" required>
							</div>
						</div>

						<div class="form-group">
							<label class="form-label" for="fax">Fax</label>
							<span class="help">e.g. "022 754321 / 022 1234567"</span>
							<div class="controls">
								<input type="text" class="form-control" data-toggle="input" data-name="fax" name="fax" id="fax" value="{{ old('fax') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="form-label" for="email">Email</label>
							<span class="help"></span>
							<div class="controls">
								<input type="email" class="form-control" data-toggle="input" data-name="email" name="email" id="email" value="{{ old('email') }}">
							</div>
						</div>


					</div>
				</div>
				<!-- end content -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
				@if(Auth::user()->permission > 1)
					<button type="button" data-loading-text="Menyimpan..." class="btn btn-primary simpan-pelanggan">Simpan Data Customer</button>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
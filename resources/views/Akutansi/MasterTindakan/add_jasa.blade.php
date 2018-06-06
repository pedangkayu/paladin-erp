@extends('Master.Template')

@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/akunting/masterjasa/add_masterjasa.js') }}"></script>
@endsection

@section('title')
Input Master Jasa
@endsection

@section('content')

<form method="post" action="{{ url('/mastertreatment/masterjasa') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="row">
		<!-- left -->
		<div class="col-sm-12">

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="alamat">Nama Jasa  *</label>
								<input class="form-control" name="nm_service" value="{{isset($jasa->nm_service) ? $jasa->nm_service : ''}}" required rows="4" >
								<input type="hidden" name="service_kode" value="{{isset($jasa->service_kode) ? $jasa->service_kode : ''}}">
								<input type="hidden" name="type" value="2">
							</div>
							   <div class="form-group">
								<label for="supplier">Akun Coa Penjualan *</label>
									<select name="coa" required class="form-control">
						             	 <option value="">Pilih COA</option>
						             		 {!! $select_coa !!}
						             </select>
								</div>
								<div class="form-group">
								<label for="supplier">Akun Coa Disk Penjualan *</label>
									<select name="coa_rs" required class="form-control">
						             	 <option value="">Pilih COA</option>
						             		 {!! $select_coa !!}
						             </select>
								</div>

						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="alamat">Tarif Jasa*</label>
								<input type="number" class="form-control" name="tarif_dasar" value="{{isset($jasa->tarif_dasar) ? $jasa->tarif_dasar : ''}}" placeholder="Masukan Tarif Contoh:1000000"rows="4" >
							</div>
							<div class="form-group">
								<label for="supplier">Akun Coa Biaya  </label>
									<select name="coa_dr"  class="form-control">
						              	<option value="">Pilih COA_DR</option>
						             		 {!! $select_coa !!}
						            </select>
							</div>
							<div class="form-group">
								<label for="supplier">Akun Coa Pendapatan  </label>
									<select name="coa_pendapatan"  class="form-control">
						              	<option value="">Pilih COA Ppendapatan</option>
						             		 {!! $select_coa !!}
						            </select>
							</div>
							<div class="row form-row">
								<!-- <div class="col-md-6">
									<label for="no_faktur">RS (%) *</label>
									<input type="number" min="0" value="{{isset($jasa->persen_rs) ? $jasa->persen_rs : ''}}" name="persen_rs"  class="form-control" required/>
								</div> -->
								<div class="col-md-6">
									<label for="prefix">DR (%)</label>
									<input type="text" min="0" value="{{isset($jasa->persen_dr) ? $jasa->persen_dr : ''}}" name="persen_dr"  class="form-control">
								</div>
							</div>
						</div>
					</div>
					<!-- End Input Header -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th width="30%">Unit Layanan&nbsp;<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Unit Layanani ini yang akan menentukan jasa  akan terlihat di poli mana saja "><i class="glyphicon glyphicon-question-sign"></i></a></th>
									<th width="50%" class="text-left">Butuh Dokter ?&nbsp; <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Pelaku maksudnya butuh dokter/ yang menangani dokter"><i class="glyphicon glyphicon-question-sign"></i></a></th>
									<th></th>
		
								</tr>
							</thead>
							
							<tr>
							<td>
							<input type="hidden" name="id_service_detail[]" value="{{isset($key->id_service_detail) ? $key->id_service_detail : ''}}"></input>
								<select name="unit[]" id="unit" required="required" class="form-control">
									<option value=""> Pilih Unit </option>
									@foreach($unit as $term)
									<option value="{{ $term->id_gudang }}"{{empty($key) ? '' : $term->id_gudang == $key->id_unit ? 'selected="selected"' : ''}}>{{ $term->nm_gudang }}</option>
									@endforeach
								</select>
							</td>
						      <td>
						     <select name="kebutuhan[]" required class="form-control">
			                    <option value="">Pilih </option>
			                    <option value="0" {{empty($key) ? '' : "0" == $key->kebutuhan ? 'selected="selected"' : ''}}>Tidak</option>
			                    <option value="1" {{empty($key) ? '' : "1" == $key->kebutuhan ? 'selected="selected"' : ''}}>Butuh</option>
							</select>
							</td>
							</tr>
						
							
							<tbody class="content-item"></tbody>
						</table>
					</div>

					<!-- footer  -->
					<div class="row" style="padding:10px 0;">
						<div class="col-sm-7">
							<div class="form-group">
								<button type="button" class="btn btn-primary " id="add-new-blank-jasa"><i class="fa fa-plus"></i> Tambah Form Lokasi</button>
							</div>
						</div>
					</div>

					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-2">
								<a href="{{ url('/') }}" class="btn btn-default btn-block">Batal</a>
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


@endsection
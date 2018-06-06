@extends('Master.Template')
@section('meta')

<script type="text/javascript" src="{{ asset('/js/treatment/rawatinap/create.js') }}"></script>
@endsection

@section('title')
  Daftar Pasien CekIn Kamar
@endsection

@section('content')
 <form method="post" action="{{ url('/Rawatinap/rawat') }}">

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
							<label for="supplier">ID Antrian Rawat Inap*</label>
								<input type="text" name="id_antrian"  class="form-control" id="id_antrian" readonly="readonly" value="">
							</div>
							<div class="form-group">
								<label for="alamat">Nama Pasien *</label>
								<input class="form-control" type="text" name="nama_pasien" value="" readonly="readonly" required rows="4" >
								<input type="hidden" name="id_pasien" value="">
							</div>
								<div class="form-group">
								<label for="supplier">Alamat Pasien*</label>
									<input class="form-control" name="alamat_pasien" value="" readonly="readonly" required rows="4" >
								</div>

						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="alamat">Tanggal CekIn Kamar*</label>
								<input type="text" class="form-control" name="tgl_cekin" value="" readonly="readonly">
							</div>
							<div class="form-group">
								<label for="supplier">Pilih Kamar *</label>
									<select name="id_kamar" required class="form-control">
						              	<option value="">Pilih kamar</option>
										@foreach ($items as $kamar)
												<option value="{{$kamar->id_kamar}}">{{$kamar->nm_kamar}}</option>
										@endforeach						             	
						            </select>
							</div>
							<!-- <div class="row form-row">
								<div class="col-md-6">
									<label for="no_faktur">RS (%) *</label>
									<input type="number" min="0" value="{{isset($jasa->persen_rs) ? $jasa->persen_rs : ''}}" name="persen_rs"  class="form-control" required/>
								</div>
								<div class="col-md-6">
									<label for="prefix">DR (%)</label>
									<input type="number" min="0" value="{{isset($jasa->persen_dr) ? $jasa->persen_dr : ''}}" name="persen_dr"  class="form-control">
								</div>
							</div> -->
						</div>
					</div>
					<!-- End Input Header -->
					<!-- <div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th width="30%">Lokasi</th>
									<th width="50%" class="text-left">Butuh Pelaku</th>
									<th></th>
		
								</tr>
							</thead>
							<tr>
							<td>
								<select name="unit[]" id="unit" required="required" class="form-control">
									<option value=""> Pilih Unit </option>
									
								</select>
							</td>
						      <td>
						     <select name="kebutuhan[]" required class="form-control">
			                    <option value="">Pilih </option>
			                    <option value="0">Tidak</option>
			                    <option value="1">Butuh</option>
							</select>
							</td>
							</tr>
							<tbody class="content-item"></tbody>
						</table>
					</div> -->

					<!-- footer  -->
					<div class="row" style="padding:10px 0;">
						<div class="col-sm-7">
							<div class="form-group">
								<button type="button" class="btn btn-primary " data-toggle="modal" data-target="#rawat"><i class="fa fa-plus"></i>Verifikasi Pasien</button>
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



<div class="modal fade" id="rawat" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Data Pasien Rawat Inap</h4>
			</div>
			<div class="modal-body">
					<li class="active" data-toggle="link-tab"><a href="#items"></a></li>
				<div class="tab-content">
					<div class="tab-pane active"  id="items">
						<!--  <div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-id_pasien" class="form-control" placeholder="Nomor Registrasi / Reg Number">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama_pasien" class="form-control" placeholder="Nama  Pasien">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-pasien"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-item"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br /> -->
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Ruangan</th>
										<th>kelas</th>
										<th>No. Reg</th>
										<th>Nama</th>
										<th>Tanggal/Jam MRS</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-rawat-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-rawat-pagin text-center"></div>
					</div>


				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				<input type="hidden" name="home-tab" value="#items">
			</div>
		</div>
	</div>
</div>
@endsection

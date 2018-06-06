@extends('Master.Template')
@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/treatment/createjasa.js') }}"></script>

@endsection
@section('title')
Treatment
@endsection
@section('content')
<form method="post" action="{{url('/jasa/create')}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" value="{{ date('m/d/Y  h:i:s') }}" name="tgl_input" id="tgl_input" class="form-control" readonly="readonly">
	<input type="hidden" value="{{ date('m/d/Y   h:i:s')}}" name="tgl_pemeriksa" id="tgl_pemeriksa" class="form-control" readonly="readonly">
	<div class="row">
		<!-- left -->
		<div class="col-sm-12">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="row center">
					<div class="col-sm-10">
						<table class="table table-bordered table-hover">
								<tr>
									<td>Pilih Dokter</td>
									<td colspan="2">
										<select style="width:100%;" name="id_karyawan" id="id_karyawan" required>
												<option value="">Pilih Dokter</option>
												@foreach($dokter as $dokter)
														<option value="{{ $dokter->id_karyawan }}" >{{ $dokter->nm_depan }}{{ $dokter->nm_belakang}}</option>
												@endforeach
										</select>
									</td>
								</tr>
								<tr>
									<td>Nomor Registrasi / Reg Number</td>
									<td >
										<input type="hidden" class="form-control" readonly="readonly" name="id_pasien" id="id_pasien" value="" required="">
										<input type="text" class="form-control" readonly="readonly" name="ID_PASIEN" id="ID_PASIEN" value="" required>
									</td>
										<td rowspan="2"><button type="button" class="btn btn-primary cari-pasien" data-toggle="modal" data-target="#pasien"><i class="fa fa-search"></i> Cari</button></td>
								</tr>
								<tr>
									<td> Nama</td>
									<td><input class="form-control" name="NAMA_PASIEN"  rows="4" readonly="readonly" required></td>
								</tr>
						</div>
					</div>
					<!-- End Input Header -->
					<div class="table-responsive">
						<table class="table table-hover">
							<div class="jasa-aturan"></div>
							<tr><td><h4>Paket</h4></td></tr>
							<tbody class="content-paket"></tbody>
						</table>
						<table class="table table-hover">
							<tr><td><h4>Tindakan</h4></td></tr>
							<tbody class="content-item1"></tbody> 
							<tr><td ><h4>Jasa</h4></td></tr>
							<tbody class="content-jasaaturan"></tbody>
						</table>
						<table class="table table-hover">
							<tr><td><h4>BHP</h4></td></tr>
							<tbody class="content-item"></tbody>
						</table>
					</div>

					<!-- footer  -->
					<div class="row" style="padding:10px 0;">
						<div class="col-sm-12">
							<div class="form-group">
								<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tindakan"><i class="fa fa-search"></i> Cari Treatment</button> -->
							<!-- 	<button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#Bahan"><i class="fa fa-search"></i> Cari Obat </button> -->
								<button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#produks"><i class="fa fa-search"></i>Cari</button>
								<a href=""><button type="button" class="btn btn-primary">Kembali</button></a>
<!-- 								<button type="button" class="btn btn-danger btn-hapus666" style="display:none;"><i class="fa fa-trash"></i> Hapus Penanganan</button>
								<input type="hidden" name="id_delete" value="0"> -->

								<button type="button" class="btn btn-danger btn-hapus2" style="display:none;"><i class="fa fa-trash"></i> Hapus Tindakan</button>
								<input type="hidden" name="id_delete" value="0">
								<button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus Jasa</button>
								<input type="hidden"  name="id_delete2" value="0">
								<button type="button" class="btn btn-danger btn-hapus4" style="display:none;"><i class="fa fa-trash"></i> Hapus BHP</button>
								<input type="hidden"  name="id_delete4" value="0">
								<button type="button" class="btn btn-danger btn-hapus5" style="display:none;"><i class="fa fa-trash"></i> Hapus Paket</button>
								<input type="hidden"  name="id_hapuspaket" value="0">
							</div>

						</div>
					</div>
			
					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-2">
								<a href="" class="btn btn-default btn-block">Batal</a>
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
<!-- Modal Pasien -->
<div class="modal fade" id="pasien" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Data Pasien</h4>
			</div>
			<div class="modal-body">
					<li class="active" data-toggle="link-tab"><a href="#items">PASIEN HEALTH CORNER</a></li>
				<div class="tab-content">
					<div class="tab-pane active"  id="items">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-id_pasien" class="form-control" placeholder="Nomor Registrasi / Reg Number">
							</div>
							<div class="col-sm-5">
								<input type="text" name="nama_pasien" class="form-control" placeholder="Nama  Pasien">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-pasien"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-pasien"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Nomor Registrasi</th>
										<th>Nama</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-pasien-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-pasien-pagin text-center"></div>
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

<!-- uji coba -->
<div class="modal fade" id="produks" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Jenis Pelayanan</h4>
			</div>
			<div class="modal-body">
				
				<ul class="nav nav-tabs" id="tab-4">
					
					<li class="active" data-toggle="link-tab"><a href="#p">Tindakan</a></li>
					<li data-toggle="link-tab"><a href="#po">Jasa</a></li>
					<li data-toggle="link-tab"><a href="#bhp">BHP</a></li>
				<li  data-toggle="link-tab"><a href="#hlmpaket">Paket</a></li>
				</ul>

				<div class="tab-content">
				<!-- PAKET Modal -->
					<div class="tab-pane " id="hlmpaket">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-paket" class="form-control" placeholder="Kode paket">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama-paket" class="form-control" placeholder="Nama paket">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-paket"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-paket"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Nama</th>
										<th>Harga</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-paket-list">
									<tr>
										<td colspan="3">Memuat.......</td>
									</tr>
								</tbody>


							</table>
						</div>
						<div class="modal-paket-pagin text-center"></div>
					</div>
					
					<div class="tab-pane active"  id="p">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-tindakan" class="form-control" placeholder="Kode Tindakan">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama-tindakan" class="form-control" placeholder="Nama Tindakan">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-tindakan"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-tindakan"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Nama</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-tindakanatur-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-tindakanatur-pagin text-center"></div>
					</div>

					<div class="tab-pane" id="po">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-jasa" class="form-control" placeholder="Kode jasa">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama-jasa" class="form-control" placeholder="Nama  jasa">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-jasa"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-jasa"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Nama</th>
									
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-jasaaturan-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-jasa-pagin text-center"></div>
					</div>
					<!-- BHP -->
					<div class="tab-pane " id="bhp">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-item" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-item" class="form-control" placeholder="Nama Obat">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-item"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-item"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Nama</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-items-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>


							</table>
						</div>
						<div class="modal-items-pagin text-center"></div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				<input type="hidden" name="home-tab" value="#p">
			</div>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="reseptreatment" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Data BHP</h4>
			</div>
			<div class="modal-body">
					<li class="active" data-toggle="link-tab"><a href="#items"></a></li>
				<div class="tab-content">
					<div class="tab-pane active"  id="items">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-itematuran" class="form-control" placeholder="Kode BHP">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-itematuran" class="form-control" placeholder="Nama  BHP">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-itematuran"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-itematuran"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Nama</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-itemsaturan-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-itemsaturan-pagin text-center"></div>
						<input type="hidden" name="id_service" value="0">
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

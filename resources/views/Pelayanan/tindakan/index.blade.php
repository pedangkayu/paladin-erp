@extends('Master.frontend')
@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<!-- <script type="text/javascript" src="{{ asset('js/treatment/treatment.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('js/treatment/create.js') }}"></script>

@endsection
@section('title')
Treatment
@endsection
@section('content')
<form method="post" action="{{url('/treatment/treatment')}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" value="{{ date('m/d/Y  h:i:s') }}" name="tgl_input" id="tgl_input" class="form-control" readonly="readonly">
	<input type="hidden" value="{{ date('m/d/Y   h:i:s')}}" name="tgl_pemeriksa" id="tgl_pemeriksa" class="form-control" readonly="readonly">
	<input type="hidden" value="0" name="id_paket" >
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
							
							<tr>
								<td>Poli</td><td>{{Me::subgudang()->nm_gudang}}</td>
								
							</tr>
							</table>
					
					<!-- End Input Header -->
					<div class="table-responsive contn-treatment">
					@if((Me::subgudang()->id_gudang==5)||(Me::subgudang()->id_gudang==7))
						<!-- kosong -->
					@else
					<table class="table table-hover">
						<tr><td><h4>Paket</h4></td></tr>
						<tbody class="content-paket"></tbody>
					</table>
					@endif
					</div>
					<table class="table ">
						<tr><td><h4>Tindakan</h4></td></tr>
						<tbody class="content-tindakanaturan"></tbody> 
						<!-- <tr><td><h4>BHP</h4></td></tr> -->
						<tbody class="content-items"></tbody>
					</table>
					<div class="row" style="padding:10px 0;">
						<div class="col-sm-12">
							<div class="form-group">
								<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tindakan"><i class="fa fa-search"></i> Cari Treatment</button> -->
								<!-- <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#Bahan"><i class="fa fa-search"></i> Cari Obat </button> -->
								<button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#produks"><i class="fa fa-search"></i>Cari Layanan</button>
								<a href="{{url('/authhc/logout')}}"><button type="button" class="btn btn-primary">Keluar</button></a>
								<button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus Penanganan</button>
								<input type="hidden" name="id_delete" value="0">
								<button type="button" class="btn btn-danger btn-delete" style="display:none;"><i class="fa fa-trash"></i> Hapus Obat</button>
								<input type="hidden" name="id_hapus" value="0">
								<button type="button" class="btn btn-danger btn-hapus2" style="display:none;"><i class="fa fa-trash"></i> Hapus Tindakan</button>
								<input type="hidden" name="id_delete" value="0">
								<button type="button" class="btn btn-danger btn-hapus3" style="display:none;"><i class="fa fa-trash"></i> Hapus Jasa</button>
								<input type="hidden"  name="id_hapus3" value="0">
								<button type="button" class="btn btn-danger btn-hapus4" style="display:none;"><i class="fa fa-trash"></i> Hapus BHP</button>
								<input type="hidden"  name="id_delete4" value="0">
								<button type="button" class="btn btn-danger btn-hapus5" style="display:none;"><i class="fa fa-trash"></i> Hapus Paket</button>
								<input type="hidden"  name="id_hapuspaket" value="0">
							</div>

						</div>
					</div>
					<!-- ---- -->
					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-12">
								<button type="submit" class="btn btn-primary"  >Simpan</button>
								<a href=""><button type="button" class="btn btn-primary">Batal</button></a>
							</div>
						</div>
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
<!-- Modal -->
<!-- <div class="modal fade" id="tindakan"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Tindakan & Jasa</h4>
				
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#items">Silahkan Pilih Tindakan </a></li>
					<li data-toggle="link-tab"><a href="#jasa">Jenis  Jasa</a></li>
					<li data-toggle="link-tab"><a href="#obhp">BHP</a></li>
					<li data-toggle="link-tab"><a href="#paket">Pilih Paket</a></li>
					<li data-toggle="link-tab"><a href="#teratur">UJi COBA</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="items">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-tin" class="form-control" placeholder="Kode Tindakan">
							</div>
							<div class="col-sm-5">
								<input type="text" name="nama-tin" class="form-control" placeholder="Nama  Tindakan">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-tin"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-tin"><i class="fa fa-refresh"></i></button>
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
								<tbody class="modal-tindakan-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-tindakan-pagin text-center"></div>
					</div>

					<div class="tab-pane" id="jasa">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-jasa" class="form-control" placeholder="Kode Jasa">
							</div>
							<div class="col-sm-5">
								<input type="text" name="nama-jasa" class="form-control" placeholder="Nama  Jasa">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-jasa"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-jasa"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>

						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>jasa</th>

										<th></th>
									</tr>
								</thead>
								<tbody class="modal-jasa-list">
									<tr>
										<td colspan="4">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-jasa-pagin text-center"></div>
					</div>
					<div class="tab-pane " id="obhp">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-item" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-item" class="form-control" placeholder="Nama  Obat">
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
										<th>Barang</th>
										<th>Stok</th>
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

					<div class="tab-pane " id="teratur">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-itemc" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-itemc" class="form-control" placeholder="Nama  Obat">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-itemc"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-itemc"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Barang</th>
										<th>Stok</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-cam-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-cam-pagin text-center"></div>

						<table class="table table-striped">
						<thead>
								<tr>
									<th width="15%">Kode</th>
									<th width="35%" class="text-left">Nama Obat</th>
									<th width="20%" class="text-right"></th>
								</tr>
							</thead>
						<tbody class="content-itemc"></tbody>
					<!-- 	<div class="content-itemm"></div> -->
					<!-- <tfoot class="keterangan-campur">
						<tr> -->
							<!--  -->
						<!-- 	<td width="50%">
								<textarea  name="ket_campur[]" id="keterangan" class="form-control" rows="5" cols="2"></textarea>
							</td>
						</tr>
						</tfoot>

						</table>
						<button type="button" class="btn btn-danger btn-delete" style="display:none;"><i class="fa fa-trash"></i> Hapus Campur</button>
					</div>

				<div class="tab-pane" id="paket">
					<div class="row">
						<div class="col-sm-4">
							<input type="text" name="modal-kode-jasa" class="form-control" placeholder="Kode Jasa">
						</div>
						<div class="col-sm-6">
							<input type="text" name="nama-jasa" class="form-control" placeholder="Nama  Jasa">
						</div>
						<div class="col-sm-2">
							<div class="btn-group">
								<button class="btn btn-white btn-search-jasa"><i class="fa fa-search"></i></button>
								<button title="Refresh" class="btn btn-white btn-search-jasa"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>

					<div>
						<table class="table table-striped">
							<thead>
								<tr>

									dev
									<th></th>
								</tr>
							</thead> -->
							<!-- <tbody class="modal-jasa-list"> -->
						<!-- 	Deplov
								<tr>
									<td colspan="4">Memuat...</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="modal-jasa-pagin text-center"></div>
				</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				<input type="hidden" name="home-tab" value="#ite,ms">
			</div>
		</div>
	</div>
</div> -->
 

<!-- Modal antrian-->
<!-- <div class="modal fade" id="antrian" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Daftar Antrian Pasien</h4>
			</div>
			<div class="modal-body">
					<li class="active" data-toggle="link-tab"><a href="#antrian">Daftar Antrian </a></li>
				<div class="tab-content">
					<div class="tab-pane active"  id="antrian">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-no_antrian" class="form-control" placeholder="Nomor Antrian">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama" class="form-control" placeholder="Nama  Pasien">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-antrian"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-antrian"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Nomor Antrian</th>
										<th>Kode Pasien</th>
										<th>Nama</th>
										<th>Alamat</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-antrian-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div> -->
						<!-- <div class="modal-antrian-pagin text-center"></div> -->
		<!-- 			</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				<input type="hidden" name="home-tab" value="#antrian">
			</div>
		</div>
	</div>
</div> -->


<!-- Modal Pasien -->
<!-- <div class="modal fade" id="pasien" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
						</div> -->
						<!-- <div class="modal-pasien-pagin text-center"></div> -->
					<!-- </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				<input type="hidden" name="home-tab" value="#items">
			</div>
		</div>
	</div>
</div> -->
<!-- modal batonn --> 
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
						<!-- <div class="modal-itemsaturan-pagin text-center"></div> -->
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
<!-- modal untuk Bahan Habis Pakai -->
<div class="modal fade" id="tambahbhp" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Produk Obat <b>{{Me::subgudang()->nm_gudang}}</b></h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#items">Silahkan Pilih Obat </a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="item">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-bhp" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-6">
								<input type="text" name="modal-barang-bhp" class="form-control" placeholder="Nama  Obat">
							</div>
							<div class="col-sm-2">
								<div class="btn-group">
									<button class="btn btn-white btn-search-bhp"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-bhp"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Barang</th>
										<th>Stok</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-bhp-list ">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- <div class="modal-bhp-pagin text-center"></div> -->
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
				<h4 class="modal-title" id="myModalLabel">Semua Jenis Pelayanan <b></b></h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#p">Tindakan</a></li>
				<!-- 	<li data-toggle="link-tab"><a href="#po">Jasa</a></li> -->
				<!-- 	<li data-toggle="link-tab"><a href="#bhp">BHP</a></li> -->
				@if((Me::subgudang()->id_gudang==7)||(Me::subgudang()->id_gudang==5))
				@else
				<li  data-toggle="link-tab"><a href="#hlmpaket">Paket</a></li>
				@endif
				</ul>
				<div class="tab-content">

				@if(Me::subgudang()->id_gudang==5)
				<!-- //radiologi -->
				<div class="tab-pane active"  id="p">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-tindakan1" class="form-control" placeholder=" Tindakan">
							</div>
							<div class="col-sm-5">
								<select class="form-control" name="modal-grup-tindakan1">
								<option value="">Semua</option>
									@foreach($grup as $ke)
									<option value="{{$ke->id_grup}}">{{$ke->grup}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-tindakan1"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-tindakan1"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br />
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Nama Tindakan</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-tindakanatur-list1">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						 <div class="modal-tindakanatur-pagin1 text-center"></div> 
					</div>

				@else
				<!-- PAKET Modal -->
					<div class="tab-pane " id="hlmpaket">
						<div class="row">
							<div class="col-sm-5">
								<input type="text" name="modal-nama-paket" class="form-control" placeholder="Nama Tindakan">
							</div>
							<div class="col-sm-3">
									
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
										<th>Aksi</th>
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
					<!-- halaman -->
					<div class="tab-pane " id="hlm">
						<div>
						</div>
						<table class="table table-striped">
						<tr>
							<td colspan="3">Silahka Pilih Tindakan atau Jasa</td>
						</tr><br>
						<tbody class="content-tindakanaturan"></tbody> 
						<tr><td class="btn-primary">Jasa</td></tr>
						<tbody class="content-jasaaturan"></tbody>
						<tr><td>BHP</td></tr>
						<tbody class="content-itematuran"></tbody>
						</table>
						<br>
						<input type="submit" name="button"  class="btn btn-primary"  onclick="kirim();" value="Kirim">
						<button type="button" class="btn btn-danger btn-hapus2" style="display:none;"><i class="fa fa-trash"></i> Hapus Tindakan</button>
						<button type="button" class="btn btn-danger btn-hapus3" style="display:none;"><i class="fa fa-trash"></i> Hapus Jasa</button>
					</div>
				<!-- 	service -->
					<div class="tab-pane active"  id="p">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-tindakan" class="form-control" placeholder=" Tindakan">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama-tindakan" class="form-control" placeholder="Nama Pelayanan">
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
										<th>Nama Tindakan</th>
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
					@endif
					<!-- ednf service -->
					<!-- <div class="tab-pane" id="po">
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
					</div> -->
					<!-- BHP -->
					<div class="tab-pane " id="bhp">
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
								<tbody class="modal-itemsaturan-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>


							</table>
						</div>
					<!-- 	<div class="modal-itemsaturan-pagin text-center"></div> -->
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
@endsection

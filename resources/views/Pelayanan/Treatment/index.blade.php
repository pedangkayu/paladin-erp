@extends('Master.frontend')
@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
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
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="row center">
					<div class="col-sm-12">
						<table class="table table-bordered table-hover">
							<tr>
								<td >
									<h4><b>Welcome</b> &nbsp;{{ Me::data()->nm_depan }}&nbsp;{{ Me::data()->nm_belakang }}</h4>
										<h5>Saat ini Anda berada diLayanan <b>{{$test->gudang_jasa}}</b> dan Menggunakan Obat/Barang<b> {{$test->nm_gudang}}</b></h5>
										Info:&nbsp; gunakan tombol <a href="#" class="link">logout</a> untuk menutup halaman ini 
										<a href="{{url('/authhc/logout')}}" class="btn-danger btn  btn-sm btn-small pull-right">Logout</a>
								</td>
							</tr>
						</table>
					</div>
					<div class="col-sm-10">
						<table class="table table-bordered table-hover">
							<tr>
								<input type="hidden" class="form-control" name="unit_jasa" value="{{$test->id_gudang_jasa}}">
								<input type="hidden" class="form-control" name="unit"  value="{{$test->id_gudang_item}}">
								<td>PASIEN
										<!--  -->
										<input type="hidden" class="form-control" readonly="readonly" name="tipetreatment" id="tipetreatment" value="2">
										<input type="hidden" class="form-control" readonly="readonly" name="no_antrian" id="no_antrian" value="{{isset($pa->NO_ANTRIAN) ? $pa->NO_ANTRIAN : 'PBA0209'}}" required>
								</td>
								<td>
										<input type="text" class="form-control" readonly="readonly" name="NAMA_PASIEN" id="NAMA_PASIEN" value="{{isset($pa->NAMA_PASIEN) ? $pa->NAMA_PASIEN : 'Jontor'}}" required>
										<input type="hidden" class="form-control" name="id_pasien" value="{{isset($pa->ID_PASIEN) ? $pa->ID_PASIEN : 'DUMMY001'}}">
										<input type="hidden" class="form-control"  readonly="readonly" name="id_layanan_rs" value="{{isset($pa->id_layanan_rs) ? $pa->id_layanan_rs : '2'}}">
								</td>
							</tr>
							<!-- batas -->								
								@if(($test->id_gudang_jasa==5)||($test->id_gudang_jasa==3))
									@foreach($kelas as $ke)
									<input type="hidden" value="{{$ke->id_kelas}}" name="id_kelas">
									@endforeach
							
								@else
							<tr>
								<td>Kelas</td>
								<td>
								    <div class="form-group">
										<div class="form-label">Pilih Kelas</div>
										<div class="control">
											<select class="form-control" id="id_kelas" required name="id_kelas">
												<option value="">Pilih Kelas </option>
												@foreach($kelas as $ke)
												<option value="{{$ke->id_kelas}}" {{empty($ke) ? '' : "1" == $ke->id_kelas ? 'selected="selected"' : ''}}>{{$ke->nm_kelas}}</option>
												@endforeach
											</select>
										</div>
									</div>
								@endif
								 </td>
							</tr>
								 	@if(($test->id_gudang_jasa==5)||($test->id_gudang_jasa==13)||($test->id_gudang_jasa==14))
								 		<input type="hidden" name="flat" value="1">
                     				@else
                     					<input type="hidden" name="flat" value="0">
                     				@endif
						</table>
					
					<!-- End Input Header -->
							<div class="table-responsive contn-treatment">
								<table class="table table-hover">
									<!-- <tr><td><h4>Paket</h4></td></tr> -->
									<tbody class="content-jasaku"></tbody>
									<tbody class="content-paket"></tbody>
								</table>
							
							</div>
								<table class="table ">
									<tr><td><h4>Tindakan / Barang</h4></td></tr>
									<tbody class="content-tindakanaturan"></tbody> 
									<tbody class="content-items"></tbody>
									<tbody class="content-barang1"> </tbody>
								</table>
							<div class="row" style="padding:10px 0;">
								<div class="col-sm-12">
									<div class="form-group">
										<button type="button" class="btn btn-success"  data-toggle="modal" data-target="#produks"><i class="fa fa-search"></i>Cari Tindakan/ Obat</button>
										<!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#barang">Cari Barang</button> -->
										<button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus Penanganan</button>
										<input type="hidden" name="id_delete" value="0">
										<button type="button" class="btn btn-danger btn-delete" style="display:none;"><i class="fa fa-trash"></i> Hapus Obat</button>
										<input type="hidden" name="id_hapus" value="0">
										<button type="button" class="btn btn-danger btn-hapus2" style="display:none;"><i class="fa fa-trash"></i> Hapus Tindakan</button>
										<input type="hidden" name="id_delete" value="0">
										<button type="button" class="btn btn-danger btn-hapusjasaku" style="display:none;"><i class="fa fa-trash"></i> Hapus Jasa</button>
										<input type="hidden"  name="id_hapusjasaku" value="0">
										<button type="button" class="btn btn-danger btn-hapus4" style="display:none;"><i class="fa fa-trash"></i> Hapus BHP</button>
										<input type="hidden"  name="id_delete4" value="0">
									<!-- 	<button type="button" class="btn btn-danger btn-hapus5" style="display:none;"><i class="fa fa-trash"></i> Hapus Paket</button> -->
										<input type="hidden"  name="id_hapuspaket" value="0">
									</div>
									<div class="form-group">
										<label for="keterangan">Catatan :</label>
										<textarea name="keterangan" id="keterangan" class="form-control" value="" placeholder="tulisakan keterangan disini jika ada yang akan di sampaikan ke kasir" rows="5"></textarea>
									</div>
								</div>
							</div>

							<!-- ---- -->
							<div class="grid-footer">
							<table>
								<tr>
									<!-- <td width="50%" class="text-right"><strong>GrandTotal  :</strong></td> -->
									<input type="hidden" name="grand_total" value="0">
									<input type="hidden" name="grand_totalbhp" value="0">
									<!-- <td width="70%" class="resep-subtotal text-right">0,00</td> -->
								</tr>
							</table>
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
						<input type="hidden" name="service_kode" value="0">
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
<div class="modal fade" id="barang" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Produk Obat <b></b></h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#items">Silahkan Pilih Barang </a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="item">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-bhp" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-bhp" class="form-control" placeholder="Nama  Obat">
							</div>
							<div class="col-sm-3">
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
						<div class="modal-bhp-pagin text-center"></div>
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
			
				<li  data-toggle="link-tab"><a href="#jasa">Jasa</a></li>
			
				</ul>
				<div class="tab-content">
				@if($test->id_gudang_jasa==5)
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
					<div class="tab-pane active"   id="p">
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
					@endif
					<div class="tab-pane "  id="jasa">
						<div class="row">
							<!-- <div class="col-sm-4">
								<input type="text" name="modal-kode-jasa" class="form-control" placeholder="Jasa ">
							</div> -->
							<div class="col-sm-8">
								<input type="text" name="modal-nama-jasa" class="form-control" placeholder="Jasa">
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
										<th>Nama Jasa</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="modal-jasa-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
					 <div class="modal-jasa-pagin text-center"></div> 
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

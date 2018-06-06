@extends('Master.Template')
@section('csstop')

@endsection
@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/treatment/master/create.js') }}"></script>
<style type="text/css">
		.btn-a:focus{
			background: #333;
		}
	</style>

@endsection

@section('title')
Tambah Paket Layanan &nbsp; {{Me::subgudang()->nm_gudang}}
@endsection
@section('content')
<div class="col-md-12">
	<div class="tab-content">
		<div class="tab-pane active">
			<form action="{{url('/mastertreatment/grup')}}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="id" value="">
						<div class="col-sm-5">
						<div class="form-label">Grup Layanan &nbsp;</div>	
							<div>
								<select class="form-control" id="id_grup" name="id_grup" required>
									<option value="">-Pilih-</option>
									@foreach($grup as $row)
										<option value="{{ $row->id_grup }}"{{ $row->id_grup==3 ? 'selected' : '' }}> {{$row->grup}} </option>
									@endforeach
								</select>
							</div>
							</div>
							<br><br>
							<!--  -->
							<div class="form-group">
								<table class="table ">
									<tbody class="content-tindakanaturan"></tbody> 
									<!-- <tr><td><h4>BHP</h4></td></tr> -->
									<tbody class="content-items"></tbody>
								</table>
							</div>
						<div class="col-sm-12">
							<div class="form-group">
								<button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus Penanganan</button>
								<input type="hidden" name="id_delete" value="0">
								<button type="button" class="btn btn-danger btn-delete" style="display:none;"><i class="fa fa-trash"></i> Hapus Obat</button>
								<input type="hidden" name="id_hapus" value="0">
								<button type="button" class="btn btn-danger btn-hapusjasa" style="display:none;"><i class="fa fa-trash"></i> Hapus Jasa</button>
								<input type="hidden"  name="id_deletejasa" value="0">
							</div>

						</div>
					<div class="form-group">
						<button type="button" class="btn btn-danger btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus Pelayanan</button>
						<input type="hidden" name="id_delete" value="0">
					</div>
						<div class="form-group">
							<div class="control">
								<button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#produks"><i class="fa fa-search"></i>Cari Data Tindakan</button>
								<button class="btn btn-primary" type="submit"> Simpan</button>	
								<button type="button" class="btn btn-danger btn-hapus2" style="display:none;"><i class="fa fa-trash"></i> Hapus Tindakan</button>
								<input type="hidden" name="id_delete" value="0">
								<button type="button" class="btn btn-danger btn-hapus4" style="display:none;"><i class="fa fa-trash"></i> Hapus BHP</button>
								<input type="hidden"  name="id_delete4" value="0">
								<button type="button" class="btn btn-danger btn-hapus3" style="display:none;"><i class="fa fa-trash"></i> Hapus Jasa</button>
								<input type="hidden"  name="id_hapus3" value="0">
							</div>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection
@section('footer')
<!-- Modal -->


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
								<input type="text"  name="modal-barang-itematuran" tabindex="1" class="form-control btn-search-itematuran1" placeholder="Nama  BHP">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-itematuran btn-a"><i class="fa fa-search"></i></button>
									<button title="Refresh" tabindex="2" class="btn btn-white btn-search-itematuran btn-a"><i class="fa fa-refresh"></i></button>
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

<!-- jasa -->
<div class="modal fade" id="jasa" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Data Jasa </h4>
			</div>
			<div class="modal-body">
					<li class="active" data-toggle="link-tab"><a href="#items"></a></li>
				<div class="tab-content">
					<div class="tab-pane active"  id="items">
						<div class="row">
							<div class="col-sm-9">
								<input type="text" name="modal-nama-jasa" class="form-control" placeholder="Nama jasa">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-jasa"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-jasa"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
						<br/>
						<div>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Nama</th>
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

<!-- uji coba -->
<div class="modal fade" id="produks" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Jenis Nama Paket <b></b></h4>
			</div>
			<div class="modal-body">
				<div class="tab-content">
				<!-- PAKET Modal -->
				<!-- 	service -->
					<div class="tab-pane active"  id="p">
						<div class="row">
							<div class="col-sm-9">
								<input type="text" name="modal-nama-tindakan" class="form-control" placeholder="Nama Paket">
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

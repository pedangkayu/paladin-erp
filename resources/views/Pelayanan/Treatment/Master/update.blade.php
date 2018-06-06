@extends('Master.Template')

@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/treatment/master/update.js') }}"></script>
@endsection

@section('title')
{{$title}}
@endsection

@section('content')
<form method="post" action="{{ url('/mastertreatment/update') }}">

	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="row">
		<!-- left -->
		<div class="col-sm-12">

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<i>Jika ingin menambah Barang/Obat/Jasa silahkan Klik salah satu tombol di bawah ini sesuai dengan yang diinginkan </i>
									<th width="35%" class="text-left">Nama Paket : <i>{{ $service->nm_service }}</i>
									<input type="hidden" name="ketua" value="{{$service->id_service}}">
									<input type="hidden" name="grup" value="{{$service->id_grup}}">
									<input type="hidden" name="id_unit" value="{{$service->unit}}">
									 <button type="button" class="btn-jasa btn-langer btn-sm" onclick="jasa({{$service->id_service}},1);">Tambah Jasa</button></th>	
									<td><button type="button" class="btn-bhp btn-langer btn-sm" onclick="bhp({{$service->id_service}},1);">Tambah Bhp</button></td>
								</tr>
							</thead>
							<div class="row">
							<tbody>
								@foreach($jasa as $item)
								<tr class="masterpak_{{$item->id_service}}">
									<input type="hidden" name="id_service[]" value="{{$item->id_service}}">
									<td>
										{{ $item->nm_service }}
										<input type="hidden" value="{{ $item->service_kode}}" name="service_kode[]">
									</td>
									<td></td>
									<td><a href="javascript:;" onclick="destroy({{ $item->id_service}});" class="text-danger">Hapus</a></td>
								</tr>
								@endforeach
								<tbody class="content-jasa"></tbody>
							<tbody>
				              @foreach ($items as $obt)
				              <tr class="bhp_{{$obt->id_service_item}}">
				              	<input type="hidden" name="id_service_item[]" value="{{$obt->id_service_item}}">
				              	<input type="hidden" name="id_barang[]" value="{{$obt->id_barang}}">
				                <td class="text-center">{{$obt->nm_barang}}</td>
							          <td class="col-sm-3">
										<div class="input-group input-group-sm">
											<input type="number"   value="{{$obt->qty}}" name="qty[]" class="form-control text-left"  required />
										  	<span class="input-group-addon">{{$obt->nm_satuan}}</span>
										  	<input type="hidden" name="id_satuan[]" value="{{$obt->id_satuan}}">
										</div>
									</td>
				                <td><a href="javascript:;" onclick="sembunyi({{ $obt->id_service_item}});" class="text-danger">Hapus</a></td>
				              </tr>
				            @endforeach
							</tbody>
							<tbody class="content-bhp"></tbody>
						</table>
					</div>
					</div>
					<br>
					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-2">
								<a href="{{ url('/mastertreatment/listgrup') }}" class="btn btn-default btn-block">Batal</a>
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

<div class="modal fade" id="bhp" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
								<input type="text" name="modal-kode-bhp" class="form-control" placeholder="Kode BHP">
							</div>
							<div class="col-sm-5">
								<input type="text"  name="modal-barang-bhp" tabindex="1" class="form-control" placeholder="Nama  BHP">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-bhp"><i class="fa fa-search"></i></button>
									<button title="Refresh" tabindex="2" class="btn btn-white btn-search-bhp"><i class="fa fa-refresh"></i></button>
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
								<tbody class="modal-bhp-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-bhp-pagin text-center"></div>
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
<!---///JASA-->
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
@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('js/pengadaan/mutasi/create.js') }}"></script>
	<script type="text/javascript">
		$(function(){
			$('#hlp').tooltip({
				container: 'body',
				placement : 'left',
				trigger : 'focus',
				title : 'Semua Barang yang bertipe Obat akan di mutasikan ke gudang yang terdaftar pada list gudang di bawah ini.'
			});

			$('[name="deadline"]').datepicker({
					format: "dd-mm-yyyy",
					autoclose: true,
					todayHighlight: true
		   });

		});
	</script>
@endsection

@section('title')
	Buat Permohonan Mutasi Obat & Barang
@endsection

@section('content')
	<form method="post" action="">
		<input type="hidden" value="{{ csrf_token('/Mutasi/create') }}" name="_token">

		<div class="row">
			<div class="col-sm-9">
				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<p>
							
							<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#barang"><i class="fa fa-plus"></i> Tambah</button>
							
						</p><br><br>
						<div class="table-responsive">
							<table id="table-permohoanan" class="table table-bordered" style="display:none">
								<thead>
									<tr>
										<th width="25%">Kode</th>
										<th width="25%">Nama</th>
										<th width="15%" class="text-right">Qty / Satuan</th>
										<th width="1%"></th>	
									</tr>
								</thead>
								<tbody class="content-items"></tbody>
							</table>
							<small id="table-permohoanan" class="text-muted ">* tentukan nama barang yang akan di mutasi.</small>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				
				<div class="grid simple">
					<div class="grid-title no-border">
						<h4>&nbsp;</h4>
						<div class="tools">
			          		<a href="javascript:;" class="collapse"></a> 
			          	</div>
					</div>
					<div class="grid-body no-border">
						<address>
							<strong>Oleh</strong>
							<p>{{ Me::fullname() }}</p>
							<strong>Tanggal</strong>
							<p>{{ Format::indoDate(date('Y-m-d')) }}</p>
							<strong>Departemen</strong>
							<p>{{ Me::departemen() }}</p>
						</address>
					</div>
				</div>

				<div class="grid simple">
					<div class="grid-title no-border"></div>
						<div class="grid-body no-border">

							<div class="form-group">
								<label for="id_gudang">Tujuan Gudang <a href="javascript:;" title="silahkan pilih gudang yang akan di minta barangnya " id="hlp"><i class="glyphicon glyphicon-question-sign"></i></a></label>
								<select class="form-control" name="id_gudang_tujuan" required>
									<option value="">Pilih Gudang</option>
									@foreach($gudangs as $gudang)
										<option  value="{{ $gudang->id_gudang }}">{{ $gudang->nm_gudang }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label for="deadline">Deadline</label>
				                <input type="text" id="deadline" class="form-control" name="deadline" value="{{ date('d-m-Y', strtotime('3 day', time())) }}" readonly="readonly">
							</div>
								<input type="hidden" name="id_unit" value="{{Me::subgudang()->id_gudang}}">	
						<div class="form-group">
							<textarea class="form-control" name="ket" placeholder="Tambah Keterangan..." rows="4"></textarea>
						</div>
					</div>
				</div>

				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						@if(Auth::user()->permission > 1)
						<button class="btn btn-primary btn-block" type="submit">Kirim Permohonan</button>
						@endif
						
					</div>
				</div>

			</div>

		</div>
	</form>
@endsection
@section('footer')
<div class="modal fade" id="barang" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Produk Obat / Barang </h4>
			</div>
			<div class="modal-body">
			    <ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#items">Silahkan Pilih Barang </a></li>
				</ul>
 
				<div class="tab-content">
					<div class="tab-pane active" id="item">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-barang" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-nama-barang" class="form-control" placeholder="Nama  Obat">
							</div>
							{{-- <div class="col-sm-3">
								<select class="form-control" name="modal-gudang">
								<option value="">Silahkan Pilih</option>
									@foreach($gudangs as $ke)
									<option value="{{$ke->id_gudang}}">{{$ke->nm_gudang}}</option>
									@endforeach
								</select>
							</div> --}}
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-barang"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-barang"><i class="fa fa-refresh"></i></button>
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
								<tbody class="modal-barang-list ">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-barang-pagin text-center"></div>
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
<script type="text/javascript">
	var $htm = '';
</script>
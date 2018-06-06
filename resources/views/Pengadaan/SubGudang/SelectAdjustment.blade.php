@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/pengadaan/subgudang/stockadj.js') }}"></script>
	<script type="text/javascript">
		$(function(){
			$('.parent-item-selected').slimscroll();
		});
	</script>
	<script type="text/javascript">
		$(function(){
		
			agree = function(){
				swal('Peringatan!', 'Pastikan data yang dimasukan sudah benar! Setelah penyesuaian dibuat, Penyesuaian tidak bisa diperbaharui dan dibatalkan.');
			}	

			$('#hlp').tooltip({
				container: 'body',
				placement : 'left',
				trigger : 'focus',
				title : 'Semua Barang yang bertipe Obat akan di mutasikan ke gudang yang terdaftar pada list gudang di bawah ini.'
			});

			$('[name="tanggal"]').datepicker({
					format: "yyyy-mm-dd",
					autoclose: true,
					todayHighlight: true
		   });

			$('[type="number"]').change(function(){
				var n = $(this).val();
				if(n < 0)
					$(this).val(0);
			});

			$.getJSON(_base_url + '/pmbumum/satuans', {

				ids : {!! $ids !!},
				tipe : 1

			}, function(json){
				// alert('hhhh');
				for(var i=0; i < json.ids.length; i++){
					if(json.result[json.ids[i]] == true){
						$('.satuan-item' + json.ids[i] ).html(json.content[json.ids[i]]);
					}else{
						$('.satuan-item' + json.ids[i] ).html('<div class="text-center" title="Satuan belum di seting silahkan hubungi Logistik"><i class="fa fa-times text-danger"></i> no set</div>');
						$('.input-' + json.ids[i]).attr('disabled', 'disabled').removeAttr('required').removeAttr('name');
					}
				}

			});
		});

	</script>
	<style type="text/css">
		.oneitem a{
			color :#fff;
		}
		.oneitem{
			position: absolute;
			right: 0;
			top: 0;
			bottom: 0;
			background: #ff0000;
			width: 50px;
			padding-top: 8px;
			display: none;
		}
	</style>

@endsection

@section('title')
	{{ $title }}
@endsection

@section('content')
	<form method="post" action="{{ url('/subgudang/createadj') }}" id="prosesPRQ">
	<input type="hidden" value="{{ csrf_token() }}" name="_token">
<input type="hidden" value="{{ $tipe }}" name="tipe">
	<div class="row">
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>{{ number_format($items->total(),0,',','.') }} <span class="semi-bold">barang ditemukan</span></h4>
					<div class="tools">
		          		<a href="javascript:getItems(1);" class="reload" data-toggle="tooltip" data-placement="bottom" title="Refresh"></a> 
		          	</div>
				</div>
				<div class="grid-body no-border">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
						
								<tr>
									<th width="20%" class="text-middle">Nama Barang</th>
									<th width="10%" class="text-middle">CURRENT QTY</th>
									<th width="10%" class="text-middle">NEW QTY</th>
									<th width="15%" class="text-middle">Satuan</th>
									<th width="20%" class="text-middle">Keterangan</th>
								</tr>
							</thead>
							<tbody class="content-barang">
								
							@forelse($items as $item)
								<tr class="item_{{ $item->id_barang }}">
									<td width="20%">
										<a href="javascript:;" data-toggle="tooltip" data-placement="bottom" title="{{ $item->nm_barang }}">{{ Format::substr($item->nm_barang,25) }}</a>
										<div class="text-muted"><small>{{ $item->kode }}</small></div>
										<input type="hidden" value="{{ $item->id_barang }}" name="id_barang[]">
										<input type="hidden" value="{{ $item->id_satuan }}" name="satuan_default[]">
										<input type="hidden" value="{{ ($item->masuk - $item->keluar) }}" name="current_qty[]">
									</td>
									<td width="10%" class="text-right">{{ number_format(($item->masuk - $item->keluar),0,',','.') }} {{ $item->nm_satuan }}</td>
									<td width="5%"><input type="number" name="qty[]" class="form-control text-right input-{{ $item->id_barang }}" ></td>
									<td class="satuan-item{{ $item->id_barang }}">
												Memuat...
									</td>
									<td width="20%">
										<input type="text" maxlength="50" name="kets[]" class="form-control input-{{ $item->id_barang }}" >
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="4"><div class="well">Tidak ditemukan</div></td>
								</tr>
							@endforelse
							
							</tbody>
							<tr >
								<td colspan="6" class="item-"></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-primary btn-block" data-toggle="modal"  data-target="#tambah-item" onclick="loaditem(1);">Tambah Item</button>
						
					</div>
					<small class="text-muted">* Penambahan item di unit hanya dilakukan untuk tambah item secara manual.</small>
					<div class="pagins text-right">
						{!! $items->render() !!}
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
				<div class="grid-title no-border">
					
					<div class="form-group">
						<input type="text" name="kode" placeholder="Kode" class="form-control">
					</div>
					<div class="form-group">
						<input type="text" name="nm_barang" placeholder="Nama Obat / Barang" class="form-control">
					</div>
					<div class="form-group">
						<select name="limit" class="form-control">
							<option value="10">Limit 10</option>
							<option value="50">Limit 50</option>
							<option value="100">Limit 100</option>
							<option value="500">Limit 500</option>
							<option value="{{$items->total()}}">Semua</option>
						</select>
						<input type="hidden" name="jenis" value="1">
					</div>
					<div class="form-group">
						<button class="btn btn-block btn-primary Searching" title="Advance Searching"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
			@if(Auth::user()->permission > 1)
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">

					<div class="form-group">
						<label for="tanggal">Tanggal Buat</label>
						 <input type="text" class="form-control tgl" name="tanggal" id="tanggal" readonly="readonly" value="{{ date('Y-m-d') }}">
					</div>

					<div class="form-group">
						<textarea class="form-control" name="ket" placeholder="Tambah Keterangan..." rows="4"></textarea>
					</div>
					<div class="form-group">
						<div class="">
							<label for="agree">
								<input type="checkbox" name="agree" value="1" id="agree" required>
								Saya mengerti <a href="javascript:void(0);" onclick="agree();" class="text-success">dengan keterangan !</a>
							</label>
						</div>
					</div>
					<input type="hidden" value="{{ $me->id_gudang }}" name="id_gudang">
					<button type="submit" class="btn btn-primary btn-block">Buat Penyesuaian</button>
				</div>
			</div>
				@endif
		</div>
	</div>
	</form>
@endsection

@section('footer')
<div class="modal fade" id="tambah-item" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Jenis Barang <b></b></h4>
			</div>
			<div class="modal-body">
				<div class="tab-content">
				<!-- PAKET Modal -->
				<!-- 	service -->
					<div class="tab-pane active"  id="p">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-koe" class="form-control" placeholder="Kode Barang">
							</div>
							<div class="col-sm-4">
								<input type="text" name="modal-barang" class="form-control" placeholder="Nama Barang">
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
									</tr>
								</thead>
								<tbody class="modal-item-list">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-item-pagin text-center"></div>
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
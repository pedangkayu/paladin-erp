@extends('Master.Template')
@section('meta')
<script type="text/javascript" src="{{ asset('js/resep/resepacc.js') }}"></script>
<script type="text/javascript">
		$(function(){
			$('[type="number"]').change(function(){
				var val = $(this).val();
				var max = $(this).data('max');
				var qty = $(this).data('qty');
				if(val > max){
					$(this).val(qty);
					swal('PERINGATAN!', 'Anda memasukan jumlah melebih stok yang tersedia sekarang! .');
				}if(val < 0){
					$(this).val(qty);
				}
			});
		});
	</script>
@endsection

@section('title')
	Detail Resep Obat
@endsection

@section('content')

	<div class="row">
		<div class="col-sm-13">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
				    
					<h5>Ditangani oleh {{ $data->nm_depan }} {{$data->nm_belakang}} pada tanggal {{ Format::indoDate2($data->created_at) }} 
					, {{ Format::hari($data->created_at) }}, {{ Format::jam($data->created_at) }}</h5>

					<h3> {{ $data->nama_pasien }}</h3>
					<h5> {{ $data->alamat_pasien}}, {{$data->kota_pasien}} </h5>
					<h5> {{ $data->hp_pasien}}</h5>

					<div class="text-right">
						<tr class="sr_{{ $data->id_resep }}">
							<a href="{{ url('/resep') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
							{{-- <a href="javascript:;" onclick="ubahstatus({{ $data->id_resep }});" class="text-danger"><button class="btn btn-primary ">Batal</button></a> --}}
							<!-- <a href="{{ url('/resep/print/' ) }}" class="btn btn-primary "><i class="fa fa-lock"></i>Lock</a> -->
						</tr>
					</div>
				</div>
			</div>
		<form method="post" action="{{ url('/resep/accresep') }}">
			<input type="hidden" value="{{ csrf_token() }}" name="_token">
			<input type="hidden" value="{{ $data->id_resep }}" name="id_resep">
			<input type="hidden" name="nomor_resep" value="{{$data->nomor_resep}}">

			<div class="grid simple">
				<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th width="20%" class="text-right text-middle">Nama <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahobat"><i class="fa fa-plus"></i> </button></th>
										<th width="13%" class="text-right text-middle">Stok Apotik</th>
										<th width="13%" class="text-right text-middle">Jumlah</th>
										<th width="20%" class="text-right text-middle">Cara Pakai</th>
										<th width="15%" class="text-right text-middle">Harga</th>
										<th width="25%" class="text-right text-middle">Subtotal</th>
									</tr>
								</thead>
									
								<?php $i= 1; ?>
								<tbody class="content-item"></tbody>
									@foreach($pasiendetail as $item)
									@if($item->id_barang > 0)
										<tr class="rsp_{{ $item->id_resep}}">
											<td>
												<input type="hidden" value="3" name="status_resep" id="status_resep">
												<input type="hidden" value="{{ $item->id_satuan}}" name="id_satuan[]" id="id_satuan">
												<input type="hidden" value="{{ $item->keterangan }}" name="keterangan[]" readonly="readonly">
												<input type="hidden" value="{{ $item->id_barang }}" name="id_barang[]" readonly="readonly">
												<input type="hidden" name="id_resep_item[]" value="{{$item->id_resep_item}}">
												{{$item->nm_barang }}
												<div class="link text-muted">
													<small>
													<div class="text-danger"><small>{{ (($item->m - $item->k )) }}&nbsp; &nbsp;{{ $item->nm_satuan }} || 
														<a href="javascript:;" onclick="destroy({{ $item->id_resep_item }});" class="text-danger">Hapus</a>
													</small>
														</div>
													</small>
												</div>
											</td>
											<td>
												<?php $stok= ($item->masuk- $item->keluar);?>
													@if($stok > 1)
														<span class="label label-success">
													@else
														<span class="label label-important">
													@endif
														{{ $stok}} &nbsp; {{$item->nm_satuan}}</span>
											</td>
											<td class="text-right">
												<div class="input-group input-group-sm">
												
													<input data-form="qty"
														type="number"
														name="jumlah_out[]"
														class="form-control text-right"
														required
														value="{{ $item->qty}}"
														data-max="{{ ($item->masuk - $item->qty) }}"
														data-qty="{{ $item->qty > ($item->masuk - $item->keluar) ? ((($item->masuk - $item->keluar) - $item->qty) + $item->qty) : $item->qty }}"/>
														<!-- <input type="number" value="{{ $item->jumlah_out }}" name="jumlah_out[]" class="form-control text-right"> -->
														<span class="input-group-addon"> {{ $item->nm_satuan }}</span>
												</div>
											</td>
											<td class="text-right">
												 <select class="form-control" name="id_resep_aturan[]" value="" id="id_resep_aturan" >
							                          @foreach($pakais as $cara)
															<option  value="{{ $cara->id_resep_aturan }}" {{$cara->id_resep_aturan == $item->id_resep_aturan  ? 'selected' : ''}}>{{ $cara->resep_aturan }}</option>
														@endforeach
							                    </select>
												</td>
											<td class="text-right">
												<input type="hidden" value="{{$item->id_item_gudang }}" name="id_item_gudang[]">
												<input type="text" data-form="harga_jual" value="{{ $item->harga_jual }}" name="harga_jual[]" readonly="readonly" >
											</td>
											<td>
												<input type="text" data-form="total" value="{{ number_format($item->total) }},00" name="total[]" class="form-control text-right" readonly="readonly" required>
											</td>
										</tr>
									@endif
										
								@if($item->id_barang < 1)
							</table>
							<h3 class="semi-bold"><button type="button" class="btn btn-primary" data-toggle="modal" onclick="tambahcampur({{$item->id_resep_item}} ,1)">Obat Campur {{$item->nama_campur}}><i class="fa fa-plus"></i> </button></h3></th>
							<table  class="table table-bordered">
								<thead>
									<tr>
										<th width="20%">Kode </th>
										<th width="15%" class="text-center text-middle">Nama</th>
										<th width="15%" class="text-center text-middle">Stok</th>
										<th width="15%" class="text-center text-middle">Jumlah</th>
									</tr>
								</thead>
										<tbody>	
										<?php
										$items = $item->campur()
										->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
										->join('data_item_gudang', 'data_item_gudang.id_item_gudang', '=' ,'data_resep_campur.id_item_gudang') //<-- untuk join² gudankan id_item_gudang. Perlu di ingat id_item_gudang tidak sama dengan id_barang
										->leftjoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_campur.id_satuan_campur')
										->join('data_resep_item','data_resep_item.id_resep_item', '=', 'data_resep_campur.id_resep_item')
										->join('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan')
										->select(
											'data_resep_campur.*','data_barang.kode',
											'data_barang.nm_barang',
											'data_resep_item.keterangan',
											'data_resep_item.id_resep_aturan',
											'data_barang.harga_jual as jual',
											'ref_resep_aturan.resep_aturan',
											'data_resep_item.nama_campur',
											'ref_satuan.nm_satuan',
											'data_item_gudang.id_item_gudang as item_gud',
											 'data_item_gudang.in AS masuk',
                        					 'data_item_gudang.out AS keluar'
											)
									->get();  ?>
										 <tr>
							                  <td colspan="10" class="itemhpb-{{$item->id_resep_item}}"></td>
												<input type="hidden" value="{{ $item->id_satuan}}" name="id_satuan[]" id="id_satuan">
												<input type="hidden" value="{{ $item->keterangan }}" name="keterangan[]" readonly="readonly">
												<input type="hidden" value="{{ $item->id_barang }}" name="id_barang[]" readonly="readonly">
												<input type="hidden" name="id_resep_item[]" value="{{$item->id_resep_item}}">
												<input type="hidden" name="jumlah_out[]" value="{{$item->jumlah_out}}">
												<input type="hidden" value="{{$item->id_item_gudang }}" name="id_item_gudang[]">
												<input type="hidden" data-form="harga_jual" value="{{ $item->harga_jual }}" name="harga_jual[]" readonly="readonly" >
												<input type="hidden" data-form="total" value="{{ number_format($item->total) }},00" name="total[]" class="form-control text-right" readonly="readonly" required>
											
							             </tr>
										@foreach($items as $campur)
											<tr class="campur_{{ $campur->id_resep_campur}}">
												<td>{{ $campur->kode }}</td>
													<input type="hidden" value="{{$campur->id_resep_campur }}" name="id_resep_campur[]">
												<td class="text-center">{{ $campur->nm_barang }}
													<input type="hidden" value="{{$campur->id_barang }}" name="id_barang_campur[]">
													<div class="text-danger">
														<small>
															<a href="javascript:;" onclick="destroy_cam({{ $campur->id_resep_campur }});" class="text-danger">Hapus</a>
														</small>
													</div>
												</td>
												<td>
												<?php $stok= ($campur->masuk- $campur->keluar);?>
													@if($stok > 1)
														<span class="label label-success">
													@else
														<span class="label label-important">
													@endif
													{{ (($campur->masuk - $campur->keluar))}} &nbsp; {{$campur->nm_satuan}}</span>
													
												</td>
												<td class="text-right">
													<div class="input-group input-group-sm">
														<input type="hidden" value="{{$campur->qty }}" name="qty_campur[]">
														<input type="number" value="{{$campur->qty }}" name="akhir_campur[]">
														<span class="input-group-addon">{{$campur->nm_satuan}}</span>
														<input type="hidden" value="{{ $campur->id_satuan_campur}}" name="id_satuan_campur[]" id="id_satuan_campur">
														<input type="hidden" value="{{ $campur->jual}}" name="jual[]" id="jual">	
												</td>
													<input type="hidden" value="{{$campur->id_resep_item }}" name="id_resep_item_campur[]">
													<input type="hidden" value="{{ $campur->item_gud}}" name="item_gud[]" id="item_gud">
											</tr>
											@endforeach
											<tr>
												<td colspan="3" class="semi-bold center">Cara Pakai</td>
												<td class="text-right semi-bold ">
													 <select class="form-control" name="id_resep_aturan[]" value="" id="id_resep_aturan" >
							                         		 @foreach($pakais as $cara)
																<option  value="{{ $cara->id_resep_aturan }}" {{$cara->id_resep_aturan== $item->id_resep_aturan  ? 'selected' : ''}}>{{ $cara->resep_aturan }}</option>
															@endforeach
							                   		 </select>
												</td>
											</tr>
											<?php $i++; ?>
										@endif
										</tbody>
									@endforeach	
								{{-- <tbody class="content-campur"></tbody> --}}
							</table>
							<table>
								{{-- <tr>
									<td colspan="4" class="text-center"><b> Total</b> </td>
									<td width="40%"  colspan="3" class="resep-subtotal text-right"></td>
								</tr> --}}
							</table>	
							<div class="grid-body no-border">
								<button class="btn btn-primary" type="submit">Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- modal tambah obta paten -->
	<div class="modal fade" id="tambahobat" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Produk Obat</h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#items">Silahkan Pilih Obat </a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="item">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-obat" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-5">
								<input type="text" name="modal-barang-obat" class="form-control" placeholder="Nama  Obat">
							</div>
							<div class="col-sm-3">
								<div class="btn-group">
									<button class="btn btn-white btn-search-obat"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-obat"><i class="fa fa-refresh"></i></button>
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
								<tbody class="modal-obat-list ">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-obat-pagin text-center"></div>
						<input type="hidden" name="id_resep_item" value="0">
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

<!-- tambah obat campur -->
<div class="modal fade" id="tambahcampur" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Semua Produk Obat</h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" id="tab-4">
					<li class="active" data-toggle="link-tab"><a href="#items">Silahkan Pilih Obat </a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="campur">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" name="modal-kode-campur" class="form-control" placeholder="Kode Obat">
							</div>
							<div class="col-sm-6">
								<input type="text" name="modal-barang-campur" class="form-control" placeholder="Nama  Obat">
							</div>
							<div class="col-sm-2">
								<div class="btn-group">
									<button class="btn btn-white btn-search-campur"><i class="fa fa-search"></i></button>
									<button title="Refresh" class="btn btn-white btn-search-campur"><i class="fa fa-refresh"></i></button>
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
								<tbody class="modal-campur-list ">
									<tr>
										<td colspan="3">Memuat...</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="modal-campur-pagin text-center"></div>
						<input type="hidden" name="id_resep_item" value="0">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
				<input type="hidden" name="home-tab" value="#campur">
			</div>
		</div>
	</div>
</div>
@endsection

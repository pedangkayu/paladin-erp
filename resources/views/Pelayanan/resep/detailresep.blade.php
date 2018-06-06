@extends('Master.Template')
@section('meta')
<script type="text/javascript" src="{{ asset('js/resep/resepacc.js') }}"></script>
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
				    <button type="button" class="btn btn-danger btn-cons"> {{ $data->nomor_resep}}</button>

					<h5>Ditangani oleh {{ $data->nm_depan }} {{$data->nm_belakang}} pada tanggal {{ Format::indoDate2($data->created_at) }} 
					, {{ Format::hari($data->created_at) }}, {{ Format::jam($data->created_at) }}</h5>

					<h3> {{ $data->nama_pasien }}</h3>
					<h5> {{ $data->alamat_pasien}}, {{$data->kota_pasien}} </h5>
					<h5> <i class="fa fa-phone"></i> {{ $data->hp_pasien}}</h5>
					
					<div class="text-right">
						<tr class="sr_{{ $data->id_resep }}">
							@if ($data->status_resep > 0)
								<a href="{{ url('/resep/print/' . $data->id_resep) }}" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
								<a href="{{ url('/resep/printetiket/' . $data->id_resep) }}" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print E-tiket</a>
							@endif
								<a href="{{ url('/resep') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
						</tr>
					</div>
				</div>
			</div>
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="20%">Kode</th>
									<th width="20%" class="text-middle">Nama Obat</th>
									<th width="15%" class="text-right text-middle">Jumlah</th>
									<th width="25%" class="text-right text-middle">Cara Pakai</th>
								</tr>
							</thead>
									<tbody>
											<?php $i = 1; ?>
											@foreach($pasiendetail as $item)
												@if($item->id_barang > 0)
												<tr>
													<td>{{ $item->kode }}
													<a href="{{ url('/resep/printetiket/' . $item->id_resep_item) }}" target="_blank" ><i class="fa fa-print"></i> </a>
													</td>
													<td class="text-middle">{{ $item->nm_barang }}</td>
													<td class="text-right">{{ $item->qty }} {{ $item->nm_satuan }}</td>
													<td class="text-right">{{ $item->resep_aturan }}</td>
												</tr>
											@endif
									@if($item->id_barang < 1)
								</tbody>
							</table>
								<h3 class="semi-bold">Obat Campur {{$item->nama_campur}}&nbsp;{{ $i }}</h3>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th width="20%">Kode</th>
										<th width="20%" class="text-right text-middle">Nama</th>
										<th width="15%" class="text-right text-middle">Jumlah</th>
									</tr>
								</thead>
										<tbody>
											<?php
												$items = $item->campur()
														->join('data_barang', 'data_barang.id_barang', '=', 'data_resep_campur.id_barang')
															->join('data_item_gudang', 'data_item_gudang.id_item_gudang', '=' ,'data_resep_campur.id_item_gudang') //<-- untuk join² gudankan id_item_gudang. Perlu di ingat id_item_gudang tidak sama dengan id_barang
														->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_campur.id_satuan_campur')
														->join('data_resep_item','data_resep_item.id_resep_item', '=', 'data_resep_campur.id_resep_item')
														->join('ref_resep_aturan', 'ref_resep_aturan.id_resep_aturan', '=', 'data_resep_item.id_resep_aturan')
														->select(
															'data_resep_campur.*',
															'data_barang.kode',
															'data_barang.nm_barang',
															'data_resep_item.keterangan',
															'data_resep_item.id_resep_aturan',
															'ref_resep_aturan.resep_aturan',
															'data_resep_item.nama_campur',
																'ref_satuan.nm_satuan'
															)
													->get();
											?>

										@foreach($items as $campur)
										<tr>
											<td>{{ $campur->kode }}</td>
											<td class="text-center">{{ $campur->nm_barang }}</td>
											<td class="text-right">{{ $campur->qty }} &nbsp;{{$campur->nm_satuan}}</td>
										</tr>
										@endforeach
										<tr>
											<td colspan="2" class="semi-bold center">Cara Pakai</td>
											<td class="text-right semi-bold ">{{ $campur->resep_aturan }}</td>
										</tr>
										<?php $i++; ?>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

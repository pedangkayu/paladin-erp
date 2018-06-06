@extends('Master.Print')

@section('content')
	<div>
		<table class="printparent">
			<tr>
				<td><strong>No. Resep</strong></td>
				<td>:{{ $data->nomor_resep }} </td>
			</tr>
			<tr>
				<td><strong>Nama</strong></td>
				<td>: {{ $data->nama_pasien }} </td>
			</tr>
			<tr>
				<td><strong>Alamat</strong></td>
				<td>:{{ $data->alamat_pasien}} </td>
			</tr>
			<tr>
				<td><strong>No. Hp</strong></td>
				<td>: {{  $data->hp_pasien}}</td>
			</tr>
			<tr>
				<td><strong>Kota</strong></td>
				<td>:{{$data->kota_pasien}}</td>
			</tr>
			<tr>
				<td><strong>Tanggal Pemeriksaan</strong></td>
				<td>:{{ Format::indoDate2($data->created_at) }} &nbsp;{{ Format::hari($data->created_at) }}, {{ Format::jam($data->created_at) }} </td>
			</tr>
			<tr>
				<td><strong>Dokter</strong></td>
				<td>: <b>{{ $data->nm_depan }} &nbsp; {{$data->nm_belakang}}</b></td>
			</tr>
		</table>
	</div>

	<div>
		<table class="table table-bordered" cellspacing="0">
			<thead>
				<tr>
					<th width="20%" rowspan="2">Kode Barang</th>
					<th width="25%" rowspan="2">Nama Barang</th>
					<th width="10%" rowspan="2">Jumlah</th>
					<th rowspan ="2">Aturan Pakai</th>
					<th width="15%" rowspan="2">Keterangan</th>
				</tr>
			</thead>
			<tbody>
			<?php $i = 1; ?>
						@foreach($pasiendetail as $item)

							@if($item->id_barang > 0)

							<tr>
							<td class="text-center">{{ $item->kode }}</td>
							<td class="text-center">{{$item->nm_barang }}</td>
							<td class="text-center">{{ $item->qty }} {{ $item->nm_satuan }}</td>
							<td class="text-center">{{ $item->resep_aturan }}</td>
							<td class="text-center">.................</td>
							</tr>
						@endif
				@if($item->id_barang < 1)
			</tbody>
		</table>
		<h3 class="semi-bold">Obat Campur {{$item->nama_campur}}&nbsp;</h3>
					<table class="table table-bordered" cellspacing="0">
								<thead>
									<tr>
										<th width="20%" rowspan="2">Kode</th>
										<th width="20%" rowspan="2">Nama</th>
										<th width="20%" rowspan="2">Jumlah</th>

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
											<td class="text-center">{{ $campur->kode }}</td>
											<td class="text-center">{{ $campur->nm_barang }}</td>
											<td class="text-center">{{ $campur->qty }} &nbsp;{{$campur->nm_satuan}}</td>
										</tr>
										@endforeach
										<tr>
											<td colspan="2" class="semi-bold text-center">Cara Pakai</td>
											<td class="text-center semi-bold ">{{ $campur->resep_aturan }}</td>
										</tr>
										<tr>
											<td colspan="2" class="semi-bold text-center"> Keterangan</td>
											<td class="text-center semi-bold ">{{ $campur->keterangan}}</td>
										</tr>
										<?php $i++; ?>
									@endif
								@endforeach
							</tbody>
						</table>
	</div>
	<div>
		<table class="ttd">
			<tr>
				<td>Dibuat oleh,</td>
				<td>Mengetahui,</td>
				<td>Menerima,</td>
			</tr>
			<tr>
				<td colspan="2"><br/><br/></td>
			</tr>
			<tr>
				<td></td>
				<td>...............................</td>
				<td></td>
			</tr>
			<tr>
				<td>(Staff Unit)</td>
				<td></td>
				<td>(Pasien)</td>
			</tr>
		</table>
	</div>
@endsection
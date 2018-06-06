@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/biling/pasien.js') }}"></script>
@endsection

@section('title')
	Daftar Pasien
@endsection

@section('content')

	<div class="row">
		<!-- left -->
		<div class="col-sm-9">

			<div class="grid simple">
				<div class="grid-title no-border">
					<h4><span class="total-pasien">{{ $items->total() }}</span> ditemukan</h4>
				</div>
				<div class="grid-body no-border">

				<table class="table">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Pasien</th>
							<th>Tanggal</th>
							<th>Status</th>
							<th>Status Validasi</th>
							<th></th>
						</tr>
					</thead>
					<tbody class="list-pasien">
						<?php $no = 1; ?>
						@forelse($items as $item)
							<tr>
								<td>{{ $no }}</td>
								<td>{{ $item->nama_pasien }}</td>
								<td>{{ Format::indoDate2($item->waktu_transaksi) }}</td>
								<td>{{ $status[$item->status] }}</td>
								<td>{{ $item->status_validasi }}</td>
								<td>
									@if($item->status == 1)
										<a href="{{ url('/biling/create/' . $item->id_pasien) }}" class="btn btn-white btn-md">Bayar</a>
									@endif
								</td>
							</tr>
							<?php $no++; ?>
						@empty
							<tr>
								<td colspan="4">Tidak ditemukan</td>
							</tr>
						@endforelse
					</tbody>
				</table>

				<div class="pagin text-right">
					{!! $items->render() !!}
				</div>

				</div>
			</div>

		</div>

		<!-- right -->
		<div class="col-sm-3">

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<p> <a class="btn btn-primary btn-block" href="{{ url('/biling/create') }}">Biling</a></p>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">

					<div class="form-group">
						<label for="no_faktur">Nama Pasien</label>
						<input type="text" id="nama_pasien" name="nama_pasien" class="form-control">
					</div>

					<div class="form-group">
						<label for="tanggal">Tanggal</label>
						<div class="input-group">
					      <input type="text" class="form-control tgl" name="tanggal" id="tanggal" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default btn-tanggal" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>

					<div class="form-group">
						<label for="status">Status</label>
				      	<select name="status" id="status" class="form-control">
				      		<option value="0"> Semua </option>
				      		<option value="1"> Belum Lunas </option>
				      		<option value="2"> Lunas </option>
				      	</select>
					</div>

					<div class="form-group">
						<label for="limit">Limit / Page</label>
						<select class="form-control" name="limit" id="limit">
							<option value="5">5</option>
							<option value="10" selected="selected">10</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
						</select>
					</div>

					<div class="form-group">
						<button class="btn btn-primary btn-block cari"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>

		</div>

	</div>

@endsection

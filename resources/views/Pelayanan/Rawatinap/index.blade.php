@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('/js/rawatinap/view.js') }}"></script>
	<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-rawat tr:hover td .link{
			display: block;
		}
	</style>
@endsection

@section('title')
	Data Pasien Rawat Inap
@endsection

@section('content')
<div class="row">
		<div class="col-sm-9">
			<div class="grid simple">
				<div class="grid-title no-border">
				<h4><span class="total">{{ $items->total() }}</span> Pasien <strong>ditemukan</strong></h4>
			</div>
				<div class="grid-body no-border">

					<div class="table-responsive">
						<table class="table table-striped daftar-rawat">
							<thead>
								<tr>
									<th>No.</th>
									<th>No Antrian</th>
									<th>Kode Pasien</th>
									<th>Nama</th>
									<th>Alamat</th>
									<th>Tanggal Masuk</th>
									<th>Kamar</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody class="allrawat">
							<?php $no = $items->currentPage() == 1 ? 1 : ($items->perPage() + $items->currentPage()) -1 ; ?>
							@if(count($items))
							@forelse($items as $item)
									<tr class="rawat_{{ $item->id_rinap }}">
										<td>{{ $no }}</td>
										<td>{{ $item->id_antrian}}</td>
										<td>{{ $item->id_pasien}}</td>
										<td>{{ $item->nama_pasien}}</td>
										<td>{{ $item->alamat_pasien}}</td>
										<td> 
											@if($item->tgl_pakai >0)
											{{ Format::indoDate2($item->tgl_pakai)}} {{Format::hari($item->tgl_pakai)}} {{Format::jam($item->tgl_pakai)}}
											@else
											
											@endif
										</td>
										<td>{{ $item->nm_kamar}}</td>
										<td>{{ $No_trans[$item->No_trans] }}</td>
									</tr>
								<?php $no++; ?>
							@empty
							@endforelse
							@else
						<tr>
							<td colspan="8"><i>Data Tidak ditemukan</i></td>
						</tr>
						@endif
							</tbody>
						</table>
					</div>

					<div class="text-right paginrawat">
					{!! $items->render() !!}
					</div>

				</div>
			</div>
		</div>

		<!-- halaman kanan layar -->
		<div class="col-sm-3">
		<div class="grid simple">
	
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				<div class="form-group">
					<label>Nomor Antrian</label>
					<input type="text" name="id_antrian" class="form-control">
				</div>
				<div class="form-group">
					<label>Nama Pasien</label>
					<input type="text" name="id_pasien" class="form-control">
				</div>
				<!-- <div class="form-group">
					<label>Nama</label>
					<in --><!-- put type="text" name="nama_pasien" class="form-control">
				</div> -->
				<div class="form-group">
					<label>Status</label>
					<select name="No_trans" class="form-control">
						<option value="">Semua</option>
						<option value="0" >Check-In</option>
						<option value="1">Check-Out</option>
						<option value="2">Lunas</option>
					</select>
				</div>
				<div class="form-group">
					<label>Limit / Page</label>
					<select name="limit" class="form-control">
						<option value="5">5</option>
						<option value="10" selected="selected">10</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="500">500</option>
					</select>
				</div>

				<div class="form-group">
					<butto class="btn btn-block btn-primary carirawat"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
	@endsection

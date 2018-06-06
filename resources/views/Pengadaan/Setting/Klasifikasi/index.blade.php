@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('/js/refrensi/klasifikasi.js') }}"></script>
<style type="text/css">
	.items:hover td .tbl-opsi{
		display: block !important;
	}
</style>
@endsection

@section('title')
	Klasifikasi Item
@endsection

@section('content')
<div class="row">
	<div class="col-sm-8">
		<div class="grid simple">
			<div class="grid-title no-border">
				<h4>{{ $items->total() }} Klasifikasi <span class="semi-bold">ditemukan</span></h4>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a> 
					<a href="javascript:getItems(1);" class="reload"></a>
				</div>
			</div>
			<div class="grid-body no-border">
				<table class="table table-striped table-flip-scroll cf">
					<thead>
						<tr>
							<th>No</th>
							<th><a href="javascript:;">Kode</a></th>
							<th><a href="javascript:;">Klasifikasi</a></th>
							<th><a href="javascript:;">Created At</a></th>
							<th></th>
						</tr>
					</thead>
					<tbody class="contents-items">
						<?php $no = $items->currentPage() == 1 ? 1 : ($items->perPage() + $items->currentPage()) -1 ; ?>
						@if($items->total() > 0)
						@forelse($items as $item)
						<tr class="item_{{ $item->id_klasifikasi }} items">
							<td>{{ $no }}</td>
							<td>
								{{ $item->kode }}
								<div style="display:none;" class="tbl-opsi">
									<small>[
										@if(Auth::user()->permission > 2)
										<!-- hanya admin yang memiliki akses Write dan Execute -->
										<a href="{{ url('klasifikasi/update/'.$item->id_klasifikasi) }}">Edit</a> 
										@endif
										]</small>
									</div>
								</td>
								<td>{{ $item->nm_klasifikasi }}</td>
								<td>
									<div>
										{{ Format::indoDate($item->created_at) }}
									</div>
									<small class="text-muted">{{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small>
								</td>
								<td class="text-right">
									@if(Me::data()->id_karyawan != $item->id_karyawan)
									@if(Auth::user()->permission > 2)
									<button type="button" class="close hapusKlasifikasi" data-id="{{ $item->id_klasifikasi }}"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									@endif
									@else

									@endif
								</td>
							</tr>
							<?php $no++; ?>
							@empty

							@endforelse
							@else
							<tr>
								<td colspan="5"><i>Tidak Ada Data, Silakan melakukan penambahan data</i></td>
							</tr>	
							@endif
						</tbody>
					</table>

					<div class="text-right pagins">
						{!! $items->render() !!}
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>Tambah<span class="semi-bold"> Klasifikasi</span></h4>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a> 
					</div>
				</div>
				<div class="grid-body no-border">
					<form action="{{ url('klasifikasi/create') }}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<label>Kode</label>
							<input type="text" name="kode" class="form-control" >
						</div>
						<div class="form-group">
							<label>Klasifikasi</label>
							<input type="text" name="nama" class="form-control" >
						</div>
						<div class="form-group">
							<button class="btn btn-primary" type="submit">Simpan</button>
						</div>
					</form>
				</div>

			</div>
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>Cari <span class="semi-bold">Klasifikasi</span></h4>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a> 
					</div>
				</div>
				<div class="grid-body no-border">
					<p>
						<div class="form-group">
							<div class="input-group transparent">
								<span class="input-group-addon ">
									<i class="fa fa-search"></i>
								</span>
								<input type="text" class="form-control" placeholder="Cari Klasifikasi..." name="src">
							</div>
						</div>

						<div class="form-group">
							<label>Urutan</label>
							<select id="source" style="width:100%" name="orderby">
								<option value="asc">A - Z</option>
								<option value="desc">Z - A</option>
							</select>
						</div>
						<div class="form-group">
							<label>Limit / Page</label>
							<select id="source" style="width:100%" name="limit">
								<option value="10">10</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="500">500</option>
							</select>
						</div>

					</p>
					<br />
					<button class="btn btn-block btn-primary cari-barang" type="button"><i class="fa fa-search"></i> Cari</button>
				</div>
			</div>
		</div>
	</div>
	@endsection
@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('/js/treatment/master/action.js') }}"></script>
<style type="text/css">
	.items:hover td .tbl-opsi{
		display: block !important;
	}
</style>
@endsection

@section('title')
Master Service
@endsection

@section('content')
<div class="row">
	<div class="col-sm-9">
		<div class="grid simple">
			<div class="grid-title no-border">
				 <a href="{{ url('mastertreatment/paket') }}"><button type="button" class="btn btn-primary"> Data Master Tindakan</button></a>
				 <a href="{{ url('mastertreatment/listgrup') }}"><button type="button" class="btn btn-primary"> Data Master Paket</button></a>
			</div>

			<div class="grid-body no-border">
				<table class="table">
					<thead>
						<tr style="background: #FFFBD4; margin-top:50px;">
							<th>No.</th>
							<th><a href="javascript:;">Nama Paket</a></th>
							<th></th>
						</tr>
					</thead>

					<tbody class="alldata">

					<?php $no = $items->currentPage() == 1 ? 1 : ($items->perPage() * $items->currentPage()) - $items->perPage() + 1; ?>
					@forelse($items as $item)
						<tr class="data1_{{ $item->service_kode }}">
							<td >{{$no}}</td>
							<td >
								{{$item->nm_service}}
							</td>
							<td>
								<div class="link text-muted">
									<small>
									<a href="{{url('mastertreatment/editkode/'.$item->service_kode)}}">Edit</a>
									<!-- <a href="javascript:;" onclick="hapus({{ $item->service_kode }});" class="text-danger">Hapus</a> -->
									</small>
							    </div>
							</td>
						</tr>
						<?php $no++; ?>
						   @empty
				              <tr>
				                <td colspan="3">Tidak ditemukan</td>
				              </tr>
				           @endforelse

					</tbody>
				</table>
				<div class="text-right pagin">
				{!! $items->render() !!}
			</div>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				<a class="btn btn-block btn-primary" href="{{ url('mastertreatment/grup') }}"><i class="fa fa-plus"></i> Buat Master Paket</a>
				<!-- Single button -->
			</div>
		</div>

		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				<div class="form-group">
					<label>Nama Tindakan</label>
					<input type="text" name="nm_service" class="form-control">
				</div>
				@if((Me::subgudang()->id_gudang==20)||(Me::subgudang()->id_gudang==0))
				<div class="form-group">
				<label>Pilih Unit / Gudang</label>
				<select style="width:100%;" name="id_unit"  required>
				<option value=""> semua</option>
				@foreach($gudangs as $gudang)
				<option value="{{ $gudang->id_gudang }}">{{ $gudang->nm_gudang }}</option>
				@endforeach
			</select>
				</div>
				@else
				@endif
				<div class="form-group">
					<label> Pilih Status</label>
					<select name="status" class="form-control">
						<option value="">Semua</option>
						<option value="2"> Aktif</option>
						<option value="1"> Tidak Aktif</option>
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
					<butto class="btn btn-block btn-primary caridata"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>




@endsection

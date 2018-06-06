@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('js/treatment/master/action.js') }}"></script>
<style type="text/css">
	.items:hover td .tbl-opsi{
		display: block !important;
	}
</style>
@endsection

@section('title')
Master Paket Tindakan
@endsection

@section('content')
<div class="row">
	<div class="col-sm-9">
		<div class="grid simple">
			

			<div class="grid-body no-border">
			<br><br>
				<table class="table">
					<thead>
						<tr style="background: #FFFBD4; margin-top:50px;">
							<th>No</th>
							<th><a href="javascript:;">Nama Tindakan</a></th>
							<th><a href="javascript:;">Dibuat</a></th>
							<th>Status</th>
							
						</tr>
					</thead>

					<tbody class="allpaket">
					<?php $no = $data->currentPage() == 1 ? 1 : ($data->perPage() * $data->currentPage()) - $data->perPage() + 1; ?>
					@foreach($data as $item)
						<tr class="tin_{{$item->id_service}}">
							<td >{{$no}}</td>
							<td >
								{{$item->nm_service}}
								<div class="link text-muted">
									<small>
										<a href="{{url('mastertreatment/update/'.$item->id_service)}}">Edit</a>||
										<a href="#" onclick="detail_tinda({{ $item->id_service }});" data-toggle="modal" data-target="#detail">Lihat</a>
									</small>
							</div>
							</td>
							<td><div>{{ Format::indoDate2($item->created_at) }}</div>
								<div class="text-muted"><small>{{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small></div>
							</td>	
							<td><div>{{ $status[$item->status]}}</div>
							  <div class="text-muted"><small>
							@if($item->status==2)
									<a href="javascript:;" onclick="aktif({{ $item->id_service }});" class="text-primary"><button class="btn btn-default btn-xs btn-mini">Aktif</button></a>
							@else
									<a href="javascript:;" onclick="nonaktif({{ $item->id_service }});" class="text-danger"><button class="btn btn-default btn-xs btn-mini">Non Aktif</button></a>
							</small></div>
							@endif
							</td>
						</tr>
						<?php $no++; ?>
					@endforeach
					</tbody>
				</table>
				<div class="text-right pagin">
				{!! $data->render() !!}
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
					<butto class="btn btn-block btn-primary caripaket"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section('footer')

<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><!-- Data Treatment/Tindakan --> <span class="service"></span></h4>
				</div>
				<div class="modal-body">
					<div class="detail-tindakan">Memuat...</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
					<span class="btn-acc"></span>
				</div>
			</div>
		</div>
	</div>
@endsection
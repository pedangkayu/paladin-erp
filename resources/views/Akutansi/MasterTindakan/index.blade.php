@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('js/akunting/masterjasa/add_masterjasa.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/akunting/masterjasa/view.js') }}"></script>
<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-jasa tr:hover td .link{
			display: block;
		}
	</style>
@endsection

@section('title')
Master Jasa / Treatment
@endsection

@section('content')
<div class="row">
	<div class="col-sm-9">
		<div class="grid simple">
			<div class="grid-title no-border">
				<h4><span class="total">{{ $data->total() }}</span> Jasa <strong>ditemukan</strong></h4>
			</div>

			<div class="grid-body no-border ">
			<br><br>
				<table class="table table-striped  daftar-jasa">
					<thead>
						<tr style="background: #FFFBD4; margin-top:50px;">
							<th width="5%" class="text-middle" rowspan="2">No.</th>
							<th width="40%" class="text-middle" rowspan="2">Nama Jasa / Treatment</th>
							<th colspan="4" class="text-center">Akun COA </a></th>
							<!-- <th rowspan="2">RS (%)</th> -->
							
						</tr>
						<tr style="background: #FFFBD4; margin-top:50px;">
							<th width="15%" class="text-middle">Penjualan</th>
							<th width="15%" class="text-middle"> Disk Penjualan</th>
							<th width="15%" class="text-middle"> Biaya Dokter</th>
							<th width="15%" class="text-middle" rowspan="2">DR (%)</th>
						</tr>
					</thead>

			<tbody class="alljasa">
				<?php $no = $data->currentPage() == 1 ? 1 : ($data->perPage() + $data->currentPage()) -1 ; ?>
					@if(count($data))
							@forelse($data as $item)
						<tr class="jasa_{{$item->service_kode}}">
							<td >{{$no}}</td>
							<td >
							  {{$item->nm_service}}
								<div class="link">
									<small>
										<a href="{{url('mastertreatment/editjasa/'.$item->service_kode)}}">Edit</a> 
										<a href="#" onclick="detail_jasa({{ $item->service_kode }});" data-toggle="modal" data-target="#detail"> &middot; Lihat</a>
										<!-- <a href="javascript:void(0);" onclick="hapus({{ $item->service_kode }});" class="text-danger">Hapus</a> -->
									</small>
								</div>
							</td>
							<td>{{$item->kode}}</td>
							<td>{{$item->rs_coa}}</td>
							<td>{{$item->dr_coa}}</td>
							<!-- <td>{{ $item->persen_rs}}&nbsp;(%)</td> -->
							<td>{{ $item->persen_dr}} &nbsp;(%)</td>
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
					<div class="text-right paginjasa">
					{!! $data->render() !!}
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				<a class="btn btn-block btn-primary" href="{{ url('mastertreatment/addmaster') }}"><i class="fa fa-plus"></i> Buat Master Jasa</a>
				<!-- Single button -->
			</div>
		</div>

		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				<div class="form-group">
					<label>Nama Jasa</label>
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
					<butto class="btn btn-block btn-primary carijasa"><i class="fa fa-search"></i> Cari</button>
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
					<div class="detail-jasa">Memuat...</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
					<span class="btn-acc"></span>
				</div>
			</div>
		</div>
	</div>
@endsection
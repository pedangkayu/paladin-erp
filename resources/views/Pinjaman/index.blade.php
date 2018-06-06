@extends('Master.Template')

@section('meta')
	 <!--  -->
<script type="text/javascript" src="{{ asset('js/pinjaman/index.js') }}"></script>


<script type="text/javascript">
   function detailpinjaman(id) {
    $("#detail").load(_base_url + '/Pinjaman/detailpinjaman/'+id,function() {
        $(this).modal("show");
    });
}
function btn_edit(id){
	$('#edit').load(_base_url + '/Pinjaman/edit/'+id,function(){
		$(this).modal("show");
	});
}
</script>
	<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-skb tr:hover td .link{
			display: block;
		}

	</style>
@endsection

@section('title')
	Pinjaman Karyawan (Loan)
@endsection

@section('content')
<div class="row">
		<div class="col-sm-9">
			<div class="grid simple">
				<div class="grid-title no-border">
				</div>
				<div class="grid-body no-border">

					<div class="table-responsive">
						<table class="table table-striped daftar-skb">
							<thead>
								<tr>
								    <th width="5%"  class="text-middle">No. </th>
									<th width="20%" class="text-middle">Nama</th>
									<th width="15%" class="text-middle">No. Loan</th>
									<th width="15%" class="text-middle">Loan (Rp)</th>
									<th width="15%" class="text-middle">Sisa (Rp)</th>
									<th width="25%" class="text-middle">Durasi</th>
									<th width="5%" class="text-middle">Status</th>
								</tr>
							</thead>

							<tbody class="allpinjaman">
								<?php $no = $pinjaman->currentPage() == 1 ? 1 : ($pinjaman->perPage() + $pinjaman->currentPage()) -1 ; ?>
								@forelse($pinjaman as $item)
								<tr class="pin_{{ $item->id_loan}}">
										<td>{{ $no }}</td>
										<td width="20%">
										{{ $item->nd}} {{$item->nb}}
											<div class="link text-muted">
												<small>
													 <a href="#" onclick="event.preventDefault();detailpinjaman({{ $item->id_loan }});" data-toggle="modal" data-target="#detail">Lihat</a>
													@if($item->status == 2)
														&middot; <a href="{{ url('/Pinjaman/printpinjaman/' . $item->id_loan) }}" target="_blank" ><i class="fa fa-print"></i> </a>
														&middot; <a href="{{url('/Pinjaman/kembali/'.$item->id_loan)}}">Kredit Pinjaman</a>
                                                    @elseif($item->status == 3)
                                                    &middot; <a href="{{ url('/Pinjaman/printpinjaman/' . $item->id_loan) }}" target="_blank" ><i class="fa fa-print"></i> </a>

													@else
														<!-- |<a href="#" onclick="event.preventDefault();btn_edit({{ $item->id_loan }});" data-toggle="modal" data-target="#edit">Edit</a> -->
													@endif

													<!-- |<a href="{{url('/pinjaman/createpengembalian/'.$item->id_loan)}}">Kredit pinjamans</a> -->
												</small>
											</div>
										</td>
										<td><small> {{ $item->no_pinjaman  }} </small></td>
										<td>{{ number_format($item->nominal,0,',','.') }}</td>
										<td>{{ number_format($item->nominal - $item->total_terbayar,0,',','.') }}</td>
										<td><small>{{Format::indoDate2($item->start_time) }} s/d {{Format::indoDate2($item->end_time)}}</small></td>
										<td>{{$status[$item->status]}}</td>
									</tr>
									<?php $no++; ?>
								@empty
								<tr>
									<td colspan="6">Tidak ditemukan</td>
								</tr>
								@endforelse

								</tbody>
							</table>
						</div>

						<div class="text-right paginpinjaman">
							{!! $pinjaman->render() !!}
						</div>
				</div>
			</div>
		</div>

		<!-- halaman kanan layar -->
		<div class="col-sm-3">
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				<div class="btn-group" style="width:100%;">
				<!-- <a href="#" class="btn btn-primary btn-block dropdown-toggle" data-toggle="modal"  onclick="event.preventDefault();btn_pinjaman(1);">Tambah Pinjaman <span class="caret"></span></a> -->
				<a href="{{ url('/Pinjaman/create') }}" class="btn btn-primary btn-block dropdown-toggle" d>Tambah Pinjaman <span class="caret"></span></a>
				</div>
			</div>
		</div>

		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				<div class="form-group">
					<label>No.Pinjaman</label>
					<input type="text" name="no_pinjaman" class="form-control">
				</div>
				<div class="form-group">
					<label>Nama</label>
					<input type="text" name="nm_depan" class="form-control">
				</div>
				<div class="form-group">
					<label>Status Pinjaman </label>
					<select name="status" class="form-control">
						<option value="">Semua</option>
						<option value="1">Baru</option>
						<option value="2">Setujui</option>
						<option value="3">Lunas</option>
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
					<button class="btn btn-block btn-primary caripinjaman"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" ria-hidden="true"></div>
@endsection

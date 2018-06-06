@extends('Master.Template')

@section('meta')
	 <!--  -->
<script type="text/javascript" src="{{ asset('js/treatment/search.js') }}"></script>

<style type="text/css">
	td > .link{
		display: none;
	}
	table.daftar-pmb tr:hover td .link{
		display: block;
	}
</style>
	
@endsection

@section('title')
	Data Treatment
@endsection

@section('content')

<div class="row">
	<div class="col-sm-9">

		<div class="grid simple">
			{{--  --}}
			<div class="grid-body no-border">
					<div class="table-responsive">
							<table class="table table-striped daftar-pmb">
								<thead>
									<tr>
										<th class="text-middle" width="5%">No.</th>
										<th class="text-middle text-center" width="20%">No Treatment</th>
										<th class="text-middle text-center" width="21%">Kode Pasien</th>
										<th class="text-middle text-center" width="15%">Nama</th>
										@if((Me::subgudang()->id_gudang < 1)||(Me::subgudang()->id_gudang ==20))
										<th class="text-middle text-center" width="15%">Unit</th>
										@else
										@endif
										<th class="text-middle text-center" width="17%">Tanggal</th>
										<th class="text-middle text-center" width="10%" class="text-center">Status</th>
									</tr>
								</thead>
								<tbody class="alltreatment">
									<?php $no = $items->currentPage() == 1 ? 1 : ($items->perPage() + $items->currentPage()) -1 ; ?>
							
								@if(count($items))
									@forelse($items as $item)
									<tr class="tr_{{ $item->id_treatment }}">
										<td>{{ $no }}</td>
										<td>
											<div>
												{{ $item->nomor_treatment}}
												{{-- {!! empty($item->id_acc) ? '<i class="fa fa-times text-muted pull-right" title="Belum terverifikasi"></i>' : '<i title="Terverifikasi" class="fa fa-check-circle text-success pull-right"></i>' !!} --}}
											</div>
											<div class="link text-muted">
												<small>
													<a href="{{ url('/treatment/detail/'.$item->id_treatment) }}" >Lihat &middot;</a>
													@if((Me::subgudang()->id_gudang < 1)||(Me::subgudang()->id_gudang ==20))
													@else
										
														@if($item->status < 2)
														<a href="{{url('/treatment/updatehc/'. $item->id_treatment)}}" >Edit</a>
														@else
														@endif
													@endif
												</small>
											</div>
										</td>
										<td>
											<div>{{ $item->id_pasien }}</div>
											{{-- <div class="text-muted"><small>Dept : {{ $item->nm_departemen }}</small></div> --}}
										</td>
										<td>{{ $item->nama_pasien}}</td>
										@if(Me::subgudang()->id_gudang < 1)
										<td>{{$item->unit}}</td>
										@else
										@endif
										<td>
											<div>{{ Format::indoDate2($item->created_at) }}</div>
											<div class="text-muted"><small>{{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small></div>
										</td>
										</td>
										<td class="text-center">
											{{ $status[$item->status] }}
										</td>
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

						
							<div class="text-right pagintreatment">
								{!! $items->render() !!}
							</div>
					</div>
			</div>
		</div>

	</div>
	<div class="col-sm-3">
		{{-- <div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				 
			</div>
		</div> --}}

		<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
						<div class="form-group">
							<label>NO TREATMENT</label>
							<input type="text" name="nomor_treatment" class="form-control">
						</div>
						<div class="form-group">
							<label>Nomor Pasien</label>
							<input type="text" name="id_pasien_hc" class="form-control">
						</div>

						<div class="form-group">
							<label>Status </label>
							<select name="status" class="form-control">
								<option >Semua</option>
								<option value="0">Baru</option>
								<option value="1">Proses</option>
								{{-- <option value="3">Selesai</option> --}}
								<option value="2">Selesai</option>
							</select>
						</div>
						@if((Me::subgudang()->id_gudang < 1)||(Me::subgudang()->id_gudang ==20))
							<div class="form-group">
								<label>Unit </label>
								<select name="id_gudang" class="form-control">
									<option value="">Semua</option>
									@foreach($cek as $ke)
										<option value="{{$ke->id_gudang}}">{{$ke->nm_gudang}}</option>
									@endforeach
								</select>
							</div>
						@else
						@endif

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
							<butto class="btn btn-block btn-primary caritreatment"><i class="fa fa-search"></i> Cari</button>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
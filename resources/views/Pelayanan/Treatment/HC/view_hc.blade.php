@extends('Master.frontend')


@section('meta')
	 <!--  -->
<script type="text/javascript" src="{{ asset('js/treatment/hc/view.js') }}"></script>

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
    <div class="col-md-12">
      <div class="grid simple ">
        <div class="grid-body no-border">
          <div class="row-fluid">
            <h3>Catatan <span class="semi-bold"></span></h3>
            <br>
            <div class="alert">
            <tr>
            	<td>
	            	<h4><b>Welcome</b> &nbsp;{{ Me::data()->nm_depan }}&nbsp;{{ Me::data()->nm_belakang }}</h4>
					<h5>Saat ini Anda berada diLayanan <b>{{$test->nm_layanan}}</b> dan Menggunakan Obat/Barang<b> {{$test->nm_gudang}}</b>
					</h5>
	              Info:&nbsp; gunakan tombol <a href="#" class="link">logout</a> untuk menutup halaman ini 
              <a href="{{url('/authhc/logout')}}" class="btn-danger btn  btn-sm btn-small pull-right">Logout</a>
            		
            	</td>
            </tr>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
										<th class="text-middle text-center" width="17%">Tanggal</th>
										<th class="text-middle text-center" width="10%" class="text-center">Status</th>
									</tr>
								</thead>
								<tbody class="alltreatment">
								<?php $no = 1; ?>
									@forelse($items as $item)
									<tr class="tr_{{ $item->id_treatment }}">
										<td>{{ $no }}</td>
										<td>
											<div>
												{{$item->id_unit}}{{ $item->nomor_treatment}}
												{{-- {!! empty($item->id_acc) ? '<i class="fa fa-times text-muted pull-right" title="Belum terverifikasi"></i>' : '<i title="Terverifikasi" class="fa fa-check-circle text-success pull-right"></i>' !!} --}}
											</div>
											<div class="link text-muted">
												<small>
													[
													<a href="{{ url('/treatment/detailhc/'.$item->id_treatment) }}" >Lihat</a>
													@if($item->status < 2)
													| <a href="{{url('/treatment/updatehc/'. $item->id_treatment)}}" >Edit</a>
													@else
													@endif
													]
												</small>
											</div>
										</td>
										<td>
											<div>{{ $item->id_pasien }}</div>
											{{-- <div class="text-muted"><small>Dept : {{ $item->nm_departemen }}</small></div> --}}
										</td>
										<td>{{ $item->nama_pasien}}</td>
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
									<tr>
										<td colspan="7"><div class="">Tidak ditemukan</div></td>
									</tr>
									@endforelse
								</tbody>
							</table>

							<div>

							</div>
							<div class="text-right paginspb">
							
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
							<label>No Registrasi </label>
							<input type="text" class="form-control" name="id_pasien_hc"  value="">
						</div>
						<div class="form-group">
							
							<input type="hidden" name="id_gudang_jasa" class="form-control" value="{{$test->id_gudang_jasa}}">
						</div>

						<!--{{-- div class="form-group">
							<label>Status </label>
							<select name="status" class="form-control">
								<option >Semua</option>
								<option value="0" selected="selected">Baru</option>
								<option value="1">Proses</option>
								{{-- <option value="3">Selesai</option> --}}
							{{-- 	<option value="2">Selesai</option>
							</select>
						</div>
						<div class="form-group">
							<label>Limit / Page</label>
							<input type="text" name="id_gudang_jasa" class="form-control" value="{{$test->id_gudang_jasa}}">
							<select name="limit" class="form-control">
								<option value="5">5</option>
								<option value="10" selected="selected">10</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="500">500</option>
							</select>
						</div> --}}-->
						<div class="form-group">
							<butto class="btn btn-block btn-primary caritreatment"><i class="fa fa-search"></i> Cari</button>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

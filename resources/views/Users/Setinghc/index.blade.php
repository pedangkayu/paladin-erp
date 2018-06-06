@extends('Master.Template')

@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
	<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/js/user/Setinghc/searhing.js') }}"></script>
	<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-user tr:hover td .link{
			display: block;
		}
	</style>
@endsection

@section('title')
	Akses SIM & HC
@endsection

@section('content')
	
	<div class="row">
		<div class="col-sm-8">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>{{ $users->total() }} ditemukan</h4>
				</div>
				<div class="grid-body no-border">
					
					<div class="table-responsive">
						<table class="table table-striped daftar-user">
							<thead>
								<tr>
									<th>No</th>
									<th>Layanan HC</th>
									<th>No Antrian</th>
									<th>Gudang Barang</th>
									<th>Tabel</th>
									<th>Layanan SIM</th>

									
								</tr>
							</thead>

							<tbody class="alluser">
								<?php $no = 1; ?>
								@forelse($users as $user)
									<tr class="user-{{ $user->id_transfer }}">
										<td>{{ $no }}</td>
										<td>
											{{ $user->nm_layanan}}
											<div class="link">
												<small>[
													<a href="javascript:;" onclick="del({{ $user->id_transfer }});" class="text-danger">Hapus</a>
													
													|<a href="{{url('/Setinghc/update/'.$user->id_transfer)}}">Edit</a>
												]</small>
											</div>
										</td>
										<td>{{$user->no_antrian}}</td>
										<td>{{ $user->nm_gudang }}</td>
										<td>{{$tabel_antrian[$user->tabel_antrian]}}</td>
										<td>{{$user->jasa}}</td>
									</tr>
									<?php  $no++; ?>
									@empty
									<tr>
										<td colspan="5">Tidak ditemukan</td>
									</tr>
								@endforelse
							</tbody>
						</table>

						<div class="text-right paginuser">
							{!! $users->render() !!}
						</div>
					</div>

				</div>
			</div>

		</div>
		<div class="col-sm-4">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<a href="{{ url('/Setinghc/baru') }}" class="btn btn-primary btn-block">Buat Akses HC & SIM</a>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">

					<div class="form-group">
						<label>Limit / Page</label>
						<select name="limit" class="form-control">
							<option value="10">10</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="500">500</option>
						</select>
					</div>

					<div class="form-group">
						<butto class="btn btn-block btn-primary cariuser"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>

		</div>
	</div>

@endsection
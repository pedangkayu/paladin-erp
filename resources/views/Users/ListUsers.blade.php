@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('/js/user/user.js') }}"></script>

<style type="text/css">
	.items:hover td .tbl-opsi{
		display: block !important;
	}
</style>
@endsection

@section('title')
Daftar Pengguna
@endsection

@section('content')

<div class="row">
	<div class="col-sm-9">

		<div class="grid simple">
			<div class="grid-title no-border">
				<h4>{{ number_format($items->total(),0,',','.') }} surat <strong>ditemukan</strong></h4>
			</div>
			<div class="grid-body no-border">

				<div class="table-responsive">
					<table class="table table-striped table-flip-scroll cf">
						<thead>
							<tr>
								<th width="5%" class="text-center"><i class="glyphicon glyphicon-camera"></i></th>
								<th width="25%">Nama Lengkap</th>
								<th width="30%">Username</th>
								<th width="15%">Akses</th>
								<th width="15%">Status</th>
							</tr>
						</thead>

						<tbody class="contents-items">
							@forelse($items as $user)
							<tr class="item_{{ $user->id_user }} items">
								<td class="text-center"><img width="20" class="img-circle" src="{{ asset('/img/avatars/xs/' . $user->avatar) }}"></td>
								<td>
									<a href="javascript:;" title="{{ $user->name }}" data-toggle="tooltip" data-placement="bottom">{{ $user->name }} </a>
									<div style="display:none;" class="tbl-opsi">
										@if(Auth::user()->permission > 1 && Me::data()->id_karyawan != $user->id_karyawan)
										<small>[
											<a href="{{ url('/users/edit/' . $user->id_user) }}" >Edit</a>

											]</small>
											@endif
										</div>
									</td>
									<td>{{ $user->username }}</td>
									<td>
									<div>{{ $permission[$user->permission] }}</div>
									<div class="text-muted"><small>{{ Format::hari($user->created_at) }}, {{ Format::jam($user->created_at) }}</small></div>
									</td>
									<td>{!! Format::online($user->id_user) ? '<i class="fa fa-circle" style="color:green;"></i> Online' : '<i class="fa fa-circle text-muted"></i> Offline' !!}</td>
								</tr>
								@empty
								<tr>
									<td colspan="5">
										<div class="well">Data User Tidak ditemukan</div>
									</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="text-right pagins">
						{!! $items->render() !!}
					</div>
				</div>
			</div>

		</div>
		<div class="col-sm-3">

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">

					<div class="form-group">
					    @if(Auth::user()->permission == 3)
						<a class="btn btn-block btn-primary cari-user" href="{{ url('/users/add') }}"><i class="fa fa-plus"></i> Tambah Pengguna</a></button>
						@endif
					</div>

					<div class="form-group">
						<label>Username</label>
						<input type="text" name="src" class="form-control">
					</div>

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
						<button class="btn btn-block btn-primary cari-user"><i class="fa fa-search"></i> Cari</button>
					</div>

					</div>
				</div>

			</div>
		</div>

		@endsection
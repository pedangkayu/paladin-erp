@extends('Master.Template')

@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/personalia/karyawan.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function () {
    $('#date_start').datepicker();
    $('#date_end').datepicker();
    $('#tgl_lahir').datepicker();
});
$(function(){
		$('#id_komponen_honor').select2({
			placeholder: "Pilih Jenis Komponen Honor..."
		});

	});
</script>
@endsection

@section('title')
Data Honor Karyawan
@endsection

@section('content')
<div class="col-md-12">
	<ul class="nav nav-tabs">
		<li>
		@if($id == 0)
		<a href="{{ url('/karyawan/add') }}">Biodata</a>
		@else
		<a href="{{ url('/karyawan/update/'.$id) }}">Biodata</a>
		@endif
		</li>

		<li>
		@if($id == 0)
		<a href="#">Keluarga</a>
		@else
		<a href="{{ url('/karyawan/keluarga/'.$id) }}">Keluarga</a>
		@endif
		</li>

		<li class="active"><a href="{{ url('/karyawan/honor') }}">Honor</a></li>

		<li>
		@if($id == 0)
		<a href="#">Photo</a>
		@else
		<a href="{{ url('/karyawan/photo/'.$id) }}">Photo</a>
		@endif
		</li>
	</ul>


	<div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
	<div class="tab-content">
		<div class="tab-pane active" id="data_karyawan">
			<form action="{{ url('karyawan/honor') }}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="id_karyawan" value="<?php if(count($id) > 0){ echo $id; }else{ echo ""; }  ?>">
				<div class="row column-seperation">
					<div class="col-md-6">
						<div class="form-group">

						<h4> {{$karyawan->nm_depan}} <b>{{$karyawan->nm_belakang}}</b> </h4>

						<h5> Departemen : {{$karyawan->nm_departemen}} </h5>
						<h5> Jabatan : {{$karyawan->nm_jabatan}} </h5>
						<h5> Profesi : {{$karyawan->nm_profesi}} </h5>

 
						</div>
					</div>
					<div class="col-md-6">

						<div class="form-group">
							<div class="form-label">Komponen Honor</div>
							<div class="control">
								<select class="form-control" id="id_komponen_honor" name="id_komponen_honor" required>
									<option value="">- Pilih -</option>
									@foreach ($komponen as $key)
									 	<option value="{{ $key->id_komponen_honor}}">{{$key->nm_komponen_honor}}</option>
									@endforeach
								</select>
							</div>
						</div>


						<div class="form-group">
							<div class="form-label">Nilai / Rate</div>

							<div class="control">
								<input type="text" class="form-control" name="nilai" id="nilai" required=>
							</div>
						</div>

						<div class="form-group">
							<div class="control">
								<button class="btn btn-primary" type="submit"> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Table Data Honor -->
<div class="col-md-12">
	<div class="grid simple">
		<div class="grid-title no-border">
			<h4>Master  <span class="semi-bold">Honor</span></h4>
			<div class="tools">	<a href="javascript:;" class="collapse"></a>
				<a href="#grid-config" data-toggle="modal" class="config"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>

		<div class="grid-body no-border">
            <table class="table no-more-tables">
				<thead>
					<tr>
						<th>Komponen Honor</th>
						<th>Nilai / rate</th>
						<th>Action</th>
					</tr>
				</thead>
					@if(count($honor) > 0)
					@foreach($honor as $row)
					<tr>
						<td><a href="{{ url('karyawan/updatehonor?id='.$row->id_karyawan_honor) }}">{{ $row->nm_komponen_honor }} </a></td>

						<td>{{number_format($row->nilai,0,',','.')}}</td>
						<td></td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="4"><i>Tidak ada Data Komponen Honor</i></td>
					</tr>

					@endif
			</table>
		</div>
	</div>
</div>
@endsection

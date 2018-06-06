@extends('Master.Template')

@section('meta')

<script src="{{ asset ('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function () {
    $('#tgl_bergabung').datepicker();
    $('#tgl_lahir').datepicker();
});
</script>
@endsection

@section('title')
Update Data Karyawan
@endsection

@section('content')
<div class="col-md-12">
	<ul class="nav nav-tabs">
		<li><a href="{{ url('/karyawan/update/'.$honor->id_karyawan) }}">Biodata</a></li>
		<li><a href="{{ url('/karyawan/keluarga/'.$honor->id_karyawan) }}">Keluarga</a></li>
		<li class="active"><a href="{{ url('/karyawan/honor/'.$honor->id_karyawan) }}">Honor</a></li>
		<li><a href="{{ url('/karyawan/photo/'.$honor->id_karyawan) }}">Photo</a></li>
	</ul>

	<div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
	<div class="tab-content">
		<div class="tab-pane active">
			<form action="{{ url('karyawan/updatehonor') }}" method="post">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="id_karyawan_honor" value="{{$honor->id_karyawan_honor}}">
				<div class="row column-seperation">
					<div class="col-md-6">
						<div class="form-group">

							<h3>{{$honor->nm_depan}} {{$honor->nm_belakang}}</h3>
							<h5> Departemen : {{$honor->nm_departemen}} </h5>
							<h5> Jabatan : {{$honor->nm_jabatan}} </h5>
						</div>
					</div>
					<div class="col-md-6">

						<div class="form-group">
							<div class="form-label">Komponen Honor</div>
							<div class="control">
								<select class="form-control" id="id_komponen_honor" name="id_komponen_honor" required>
									<option value="">- Pilih -</option>
									@foreach ($komponen as $key)
									 	<option value="{{ $key->id_komponen_honor}}" {{$key->id_komponen_honor == $honor->id_komponen_honor  ? 'selected' : ''}}>{{$key->nm_komponen_honor}}</option>
									@endforeach  
								</select>
							</div>
						</div>

 
						<div class="form-group">
							<div class="form-label">Nilai / Rate</div>

							<div class="control">
								<input type="text" class="form-control" name="nilai" id="nilai" value="{{ $honor->nilai}}" required=>
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
						<th></th>
					</tr>
				</thead>
					@if(count($data) > 0)
					@foreach($data as $row)
					<tr>
						<td><a href="{{ url('karyawan/updatehonor?id='.$row->id_karyawan_honor) }}">{{ $row->nm_komponen_honor }} </a></td>
						<td>Rp. {{number_format($row->nilai,0,',','.')}}</td>
						<td><a href="{{ url('karyawan/updatehonor?id='.$row->id_karyawan_honor) }}"><span class="label label-success">Update</span></a></td>
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
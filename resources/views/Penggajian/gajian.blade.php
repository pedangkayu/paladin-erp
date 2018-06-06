@extends('Master.Template')

@section('meta')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
		$('#id_karyawan').select2({
			placeholder: "Pilih karyawan..."
		});

	});

function id_karyawan(){
    var id_karyawan=$('#id_karyawan').val();
    $('#data-detail-gaji').load(_base_url + '/penggajian/detailkaryawan', {id_karyawan : id_karyawan}, function() {
    });
}
</script>
@endsection

@section('title')
Rekap Penggajian Karyawan
@endsection

@section('content')
@if(Session::get('notif'))
<div class="panel-alert">
	<div class="alert alert-{{ Session::get('notif')['label'] }}">
		<button type="button" class="close"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
		<p>{!! Session::get('notif')['err'] !!}</p>
	</div>
</div>
@endif

@if (count($errors) > 0)
<div class="panel-alert">
	<div class="alert alert-danger">
		<button type="button" class="close"><span aria-hidden="true"></span><span class="sr-only">Close</span></button>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
</div>
@endif
	<div class="row">
		<div class="col-sm-12">
			<div class="grid simple">
				<div class="grid-title no-border">Group Gaji</div>
				<div class="grid-body no-border">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
							<label for="tahun">Tahun</label>
							<select class="select text-center" style="width:100%;" name="tahun" id="tahun">
								@for($i = date('Y'); $i <= date('Y'); $i++)
								<option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label for="nama">Nama</label>
								<select class="form-control" onchange="id_karyawan()" id="id_karyawan" name="id_karyawan" >
									<option value="">- Pilih -</option>
									@foreach ($karyawan as $key)
										<option value="{{ $key->id_karyawan}}">{{$key->nm_depan}} {{$key->nm_belakang}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
				<div class="data-detail-gaji" id="data-detail-gaji">
				</div>
		</div>
	</div>

@endsection

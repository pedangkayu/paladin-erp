@extends('Master.Template')
@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/treatment/pindah.js') }}"></script>

@endsection
@section('title')
Pindah Kelas
@endsection
@section('content')
<form method="post" action="{{url('/treatment/pindah')}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" value="{{ date('m/d/Y  h:i:s') }}" name="tgl_input" id="tgl_input" class="form-control" readonly="readonly">
	<input type="hidden" value="{{ date('m/d/Y   h:i:s')}}" name="tgl_pemeriksa" id="tgl_pemeriksa" class="form-control" readonly="readonly">
	<input type="hidden" value="{{$set->id_layanan}}" name="id_layanan" >
	<div class="row">
		<!-- left -->
		<div class="col-sm-12">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="row center">
					<div class="col-sm-10">
						<table class="table table-bordered table-hover">
								<tr>
									<td>Nomor Reg</td>
									<td >
										<input type="hidden" class="form-control" readonly="readonly" name="tipe" id="tipe" value="2">
										<input type="text" class="form-control" readonly="readonly" name="id_pasien" id="id_pasien" value="{{$data->id_pasien}}" required="">
									</td>
								</tr>
								<tr>
									<td>Nomor Antrian</td>
									<td>
										<input type="text" class="form-control" readonly="readonly" name="no_antrian_hc" id="no_antrian_hc" value="{{$set->no_antrian_hc}}" required>
									</td>
								<tr>
									<td>Nama</td>
									<td>
										<input type="text" class="form-control" readonly="readonly" name="nama_pasien" id="nama_pasien" value="{{$set->nama_pasien}}" required>
									</td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>{{$set->alamat_pasien}}</td>
								</tr>
										<input type="hidden" name="status" id="status" value="1">
										<input type="hidden" class="form-control" readonly="readonly" name="nomor_antrian" id="nomor_antrian" value="{{$set->nomor_antrian}}">
	
								<tr>
									<td>Awal masuk</td>
									<td>{{ Format::indoDate2($set->created_at) }} &nbsp;{{ Format::hari($set->created_at) }}, {{ Format::jam($set->created_at) }}</td>
								</tr>
								<?php $ke=1; ?>
									@foreach($k as $kel)
									<tr>
										<td>Pindah &nbsp;{{$ke}}</td>
										<td>{{$kel->k}} &nbsp; &nbsp; &nbsp; {{ Format::indoDate2($kel->created_at) }} &nbsp;{{ Format::hari($kel->created_at) }}, {{ Format::jam($kel->created_at) }}</td>
									</tr>
									<?php $ke++;?>
									@endforeach
								<tr>
								<td>Kelas</td>
								<td>
									<select class="form-control" id="id_kelas" name="id_kelas" required>
										<option value="">Pilih Kelas</option>
										@foreach($kelas as $ke)
										<option value="{{$ke->id_kelas}}">{{$ke->nm_kelas}}</option>
										@endforeach
									</select>
								 </td>
								 </tr>
								</table>
					<!-- End Input Header -->
					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-2">
								<a href="" class="btn btn-default btn-block">Batal</a>
							</div>
							<div class="col-sm-offset-7 col-sm-3">
								<!-- <button class="btn btn-primary btn-block" type="submit">Simpan</button> -->
								<a href="javascript:;" onclick="pindah" class="text-danger"><button   class="btn btn-primary btn-block">Simpan</button></a>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection
@section('footer')

@endsection

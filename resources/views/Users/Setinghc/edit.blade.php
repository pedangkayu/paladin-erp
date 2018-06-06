@extends('Master.Template')
@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/user/Setinghc/baru.js') }}"></script>

@endsection
@section('title')
Tambah Akses HC & SIM
@endsection
@section('content')
<form method="post" action="{{url('/Setinghc/update')}}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="row">
		<!-- left -->
		<div class="col-sm-12">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<!-- Input Header -->
					<div class="row center">
					<!-- End Input Header -->
					<div class="table-responsive">
						
					<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th width="25%" class="text-left">Layanan HC</th>
									<th width="15%">No Antrian</th>
									<th width="20%">Gudang Barang</th>
									<th width="20%">Tabel</th>
									<th width="20%">Layanan SIM</th>
									<th width="5%"></th>
								</tr>
							</thead>
							<tr>
								<td>
								<input type="hidden" name="id_transfer" value="{{$data->id_transfer}}">
									<select class="select2" style="width:100%;" name="id_layanan" required>
										<option value="">Silahkan Pilih</option>
										@foreach($layanan as $ly)
										<option value="{{ $data->id_layanan}}"{{$ly->id_layanan == $data->id_layanan ? 'selected' : ''}}>{{ $ly->nm_layanan }}</option>
										
										@endforeach
									</select>
								</td>
								<td><input type="text" name="no_antrian"  class="form-control" value="{{$data->no_antrian}}" required></td>
								<td>
									<select style="width:100%;" name="id_gudang">
										<option value="">Silahkan Pilih</option>
										@foreach($gudang as $unit)
										<option value="{{$unit->id_gudang}}"{{$unit->id_gudang == $data->id_gudang_item ? 'selected' : ''}}>{{$unit->nm_gudang}}</option>
										@endforeach
									</select>
								</td>
								<td>
									<select style="width:100%;" name="tabel_antrian" required>
										<option value="">Silahkan Pilih</option>
										<option value="1"{{empty($data) ? '' : "1" == $data->tabel_antrian ? 'selected="selected"' : ''}}>ANTRIAN</option>
										<option value="2" {{empty($data) ? '' : "2" == $data->tabel_antrian ? 'selected="selected"' : ''}}>PERIKSA PENUNJANG MEDIS</option>
										<option value="3" {{empty($data) ? '' : "3" == $data->tabel_antrian ? 'selected="selected"' : ''}}>PELAYANAN RAWAT INAP</option>
										<option value="4" {{empty($data) ? '' : "4" == $data->tabel_antrian ? 'selected="selected"' : ''}}>JADWAL OPERASI</option>
										<option value="5" {{empty($data) ? '' : "5" == $data->tabel_antrian ? 'selected="selected"' : ''}}>ANTRIAN NON POLI</option>
 								
									</select>
								</td>
								<td>
									<select style="width:100%;" name="id_layanan_sim" required>
										<option value="">Silahkan Pilih</option>
										@foreach($gudang as $unit1)
										<option value="{{$unit1->id_gudang}}" {{$unit1->id_gudang == $data->id_gudang_jasa ? 'selected' :''}}>{{$unit1->nm_gudang}}</option>
										@endforeach
									</select>
								</td>
							</tr>
						</table>
					</div>
					</div>
					
				</div>
					<!-- footer  -->

					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-2">
								<a href="{{url('/Setinghc')}}" class="btn btn-default btn-block">Batal</a>
							</div>
							<div class="col-sm-5">
							</div>
							<div  class="col-sm-3">
								<button class="btn btn-primary btn-block" type="submit">Simpan</button>
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

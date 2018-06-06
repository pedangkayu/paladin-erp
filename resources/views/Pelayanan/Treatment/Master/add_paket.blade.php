@extends('Master.Template')
@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('/js/treatment/master/createpaket.js') }}"></script>

@endsection
@section('title')
Master Treatment
@endsection
@section('content')
<form method="post" action="{{url('/mastertreatment/paket')}}">
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
									<th width="20%" class="text-left">Nama Paket</th>
								
									<th width="30%">Coa pendapatan</th>
								</tr>
							</thead>
							<tr>
								<td><input type="text" value="" name="nm_service[]" class="form-control" required placeholder="Masukan Nama Paket"></td>
									<input type="hidden" name="coa[]" value="0">
									<input type="hidden" name="coa_dr[]" value="0">
									<input type="hidden" name="coa_rs[]" class="form-control" value="0">
									<input type="hidden"  min="0" value="0" name="persen_rs[]">
									<input type="hidden" min="0" value="0" name="persen_dr[]" >
									<input type="hidden" name="type[]" value="1">
								<td>
									 
						                  <select name="coa_pendapatan"  class="form-control">
						                      <option value="">Pilih COA Ppendapatan</option>
						                       {!! $select_coa !!}
						                  </select>
						              
								</td>
							</tr>
						
							<tbody class="content-item"></tbody>
						</table>
					</div>
					</div>
					
				</div>
					<!-- footer  -->

					<div class="grid-footer">
						<div class="row">
							<div class="col-sm-2">
								<a href="" class="btn btn-default btn-block">Batal</a>
							</div>
							<div class="col-sm-5">
								<button type="button" class="btn btn-primary " id="add-new-blank"><i class="fa fa-plus"></i> Form</button>
								<button type="button" class="btn btn-danger " id="btn-hapus" style="display:none;"><i class="fa fa-trash"></i> Hapus</button>
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

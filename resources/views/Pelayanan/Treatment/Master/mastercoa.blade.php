@extends('Master.Template')

@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/akunting/coa/coa.js') }}" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/dragdrop/drugdrop.css') }}">
<script type="text/javascript" src="{{ asset('/plugins/dragdrop/jquery.nestable.js') }}"></script>
@stop

@section('title')
Master
@endsection
@section('content')
<div class="col-md-12">
  <div class="grid simple">
    <div class="grid-title no-border">
      <h4>Tambah service</h4>
      <div class="tools">
        <a href="javascript:;" class="collapse"></a> 
        <a href="javascript:;" class="reload"></a>
      </div>
    </div>

    <div class="grid-body no-border">
      <div class="row">
        <form action="{{ url('/coa/add') }}" method="post" role="form">
          <div class="col-sm-10">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @if((Session::get('sess')))
            <div class="alert alert-block alert-info">
              <button data-dismiss="alert" class="close" type="button">&times;</button>
              {{ Session::get('sess') }}
            </div>
            @endif

            <div class="form-group">
              <label for="title">Grup Account *</label>
				  <div>
					
				  </div>
            </div>

            <div class="form-group">
              <label for="title">Kode Account *</label>
				  <div>
					
				  </div>
            </div>
      
            grup

            <div class="form-group">
              <label for="parent">Type Coa</label>
			  <!-- bagian ini masih dibutuhkan ato tidak, tergantung RSOS -->
              <div>
                <select class="form-control" id="type_coa" name="type_coa">
                  <option value="">-Pilih-</option>
                  <option value="1">Debit</option>
                  <option value="2">Kredit</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="note">Note</label>
				  <div>
					
				  </div>
            </div>


            <div class="form-group">
              <label></label>
					  <div>
						<button type="submit" class="btn btn-flat btn-primary"> Account Grup</button>
						<a href=""><button type="button" class="btn btn-flat btn-default">Kembali</button></a>
					  </div>
            </div>

          </div><!-- EOF -->

        </form>
      </div>

    </div>
  </div>
</div>
@stop

@extends('Master.Template')

@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/akunting/coa/coa.js') }}" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/dragdrop/drugdrop.css') }}">
<script type="text/javascript" src="{{ asset('/plugins/dragdrop/jquery.nestable.js') }}"></script>
@stop

@section('title')
Chart Of Account 
@endsection

@section('content')

<div class="col-md-12">
  <div class="grid simple">
    <div class="grid-title no-border">
      <h4>Tambahkan Grup Akun</h4>
      <div class="tools">
        <a href="javascript:;" class="collapse"></a> 
        <a href="javascript:;" class="reload"></a>
      </div>
    </div>

    <div class="grid-body no-border">
      <div class="row">
        <form action="{{ url('/coa/update') }}" method="post" role="form">
          <div class="col-sm-10">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id_coa" value="{{ $data->id_coa }}">
            @if((Session::get('sess')))
            <div class="alert alert-block alert-info">
              <button data-dismiss="alert" class="close" type="button">&times;</button>
              {{ Session::get('sess') }}
            </div>
            @endif

            <div class="form-group">
              <label for="title">Grup Akun *</label>
				  <div>
					<input type="text" value="{{ $data->nm_coa }}" class="form-control" id="nm_coa" name="nm_coa" required="required" />
				  <input type="hidden" value="{{$data->id_coa}}" name="id_coa">
          </div>
            </div>

            <div class="form-group">
              <label for="title">Kode Akun *</label>
				  <div>
					<input type="text" value="{{ $data->kode }}" class="form-control" id="kode" name="kode" required="required" />
				  </div>
            </div>

            <input type="hidden" name="grup" id="grup" value="1">
            <div class="form-group">
              <label for="parent">Parent Grup</label>
                 <select name="parent_id" required class="form-control">
                <option value="">None</option>
                {!! $select_id !!}
                 </select>
            </div>

            <div class="form-group">
              <label for="parent">Tipe</label>
			  <!-- bagian ini masih dibutuhkan ato tidak, tergantung RSOS -->
              <div>
                <select class="form-control" id="type" name="type" required>
                  <option value="">-Pilih-</option>
                   <option value="1" {{empty($data) ? '' : "1" ==$data->type ? 'selected="selected"' : ''}}>Debit</option>
                    <option value="2" {{empty($data) ? '' : "2" ==$data->type ? 'selected="selected"' : ''}}>Kredit</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="note">Keterangan</label>
				  <div>
					<textarea type="text" class="form-control" id="note" name="keterangan" />{{ $data->keterangan }}</textarea>
				  </div>
            </div>


            <div class="form-group">
              <label></label>

				  @if(Auth::user()->permission == 3)
					  <div>
						<button type="submit" class="btn btn-flat btn-primary">Perbarui Akun Grup</button>
						<a href="{{ url('coa') }}"><button type="button" class="btn btn-flat btn-default">Kembali</button></a>
					  </div>
				  @endif
            </div>

          </div><!-- EOF -->

        </form>
      </div>

    </div>
  </div>
</div>
@stop

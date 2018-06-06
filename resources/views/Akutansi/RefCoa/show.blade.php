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
              <label for="title">Grup Akun *</label>
				  <div>
					<input type="text" value="{{ empty($menu->nm_coa) ? '' : $menu->nm_coa }}" class="form-control" id="nm_coa" name="nm_coa" required="required" />
				  </div>
            </div>

            <div class="form-group">
              <label for="title">Kode Akun *</label>
				  <div>
            <input type="hidden" name="grup" id="grup" value="1">
					<input type="text" value="{{ empty($menu->kode) ? '' : $menu->kode }}" class="form-control" id="kode" name="kode" required="required" />
				  </div>
            </div>

             <div class="form-group">
              <label for="parent">Parent Grup</label>
                 <select name="id_coa" required class="form-control">
                <option value="">None</option>
                {!! $select_coa !!}
                 </select>
            </div>

            <div class="form-group">
              <label for="parent">Tipe</label>
			  <!-- bagian ini masih dibutuhkan ato tidak, tergantung RSOS -->
              <div>
                <select class="form-control" id="type" name="type">
                  <option value="">-Pilih-</option>
                  <option value="1">Debit</option>
                  <option value="2">Kredit</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="note">Keterangan</label>
				  <div>
					<textarea value="{{ empty($menu->keterangan) ? '' : $menu->keterangan }}" type="text" class="form-control" id="note" name="keterangan" /></textarea>
				  </div>
            </div>


            <div class="form-group">
              <label></label>

				  @if(Auth::user()->permission == 3)
					  <div>
						<button type="submit" class="btn btn-flat btn-primary">{{ !empty($menu) ? 'Perbarui' : 'Tambahkan' }} Grup Akun</button>
						<a href="{{ url('coa') }}"><button type="button" class="btn btn-flat btn-default">Kembali</button></a>
						@if(!empty($menu))
						<input type="hidden" value="{{ $menu->id_coa }}" name="id_coa" />
						@endif
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

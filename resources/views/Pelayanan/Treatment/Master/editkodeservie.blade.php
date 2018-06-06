@extends('Master.Template')

@section('meta')
<script src="{{ asset ('/js/tabs_accordian.js') }}" type="text/javascript"></script>
<script src="{{ asset ('/js/akunting/coa/coa.js') }}" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/dragdrop/drugdrop.css') }}">
<script type="text/javascript" src="{{ asset('/plugins/dragdrop/jquery.nestable.js') }}"></script>
@stop

@section('title')
Update Master Paket
@endsection

@section('content')

<div class="col-md-12">
  <div class="grid simple">
    <div class="grid-title no-border">
      <h4>Update Master Paket</h4>
      <div class="tools">
        <a href="javascript:;" class="collapse"></a> 
        <a href="javascript:;" class="reload"></a>
      </div>
    </div>

    <div class="grid-body no-border">
      <div class="row">
        <form action="{{ url('/mastertreatment/editkode') }}" method="post" role="form">
          <div class="col-sm-10">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="service_kode" value="{{ $data->service_kode }}">
            @if((Session::get('sess')))
            <div class="alert alert-block alert-info">
              <button data-dismiss="alert" class="close" type="button">&times;</button>
              {{ Session::get('sess') }}
            </div>
            @endif
          <div class="form-group">
              <label for="title">Nama layanan *</label>
				  <div>
					   <input type="text" value="{{ $data->nm_service }}" class="form-control" id="nm_service" name="nm_service" required="required" />
				  </div>
          </div>
          <div class="form-group">
                <label for="supplier">Akun Coa Pendapatan  </label>
                  <select name="coa_pendapatan"  class="form-control">
                      <option value="">Pilih COA Ppendapatan</option>
                       {!! $coa_pendapatan !!}
                  </select>
              </div>
           @if(Me::subgudang()->id_gudang==20)
          <div class="form-group">
            <label for="parent">Pilih Coa</label>
               <select name="coa" required class="form-control">
              <option value="">None</option>
                      {!! $select_coa !!}
               </select>
          </div>
          <div class="form-group">
            <label class="title">Jenis Treatmen</label>
           <div>
             <input type="text"  class="form-control" readonly="readonly"  value="{{$type[$data->type]}}">
             <input type="hidden" name="type" value="{{$data->type}}"> 
           
           </div>
          </div>

          <div class="form-group">
              <label for="title">Tarif Dasar*</label>
          <div>
             <input type="text" value="{{ $data->tarif_dasar }}" class="form-control" id="tarif_dasar" name="tarif_dasar" required="required" />
          </div>

           <div class="form-group">
              <label for="title">Coa Rs *</label>
          <div>
              <select name="coa_rs" required class="form-control">
              <option value="">None</option>
                      {!! $select_coa !!}
               </select>
          </div>
          </div>
           <div class="form-group">
              <label for="title"> RS (%) *</label>
          <div>
             <input type="text" value="{{ $data->persen_rs }}" class="form-control" id="persen_rs" name="persen_rs" required="required" />
          </div>
          </div>
           <div class="form-group">
              <label for="title">Coa Dr *</label>
          <div>
            <select name="coa_dr" required class="form-control">
              <option value="">None</option>
                      {!! $select_coa !!}
               </select>
          </div>
          </div>
           <div class="form-group">
              <label for="title">DR (%)*</label>
          <div>
             <input type="text" value="{{ $data->persen_dr }}" class="form-control" id="persen_dr" name="persen_dr" required="required" />
             <!-- <input type="hidden" value="{{$data->status}}" name="status"> -->
          </div>

          </div>
          <div class="form-group">
            <label class="title">Status</label>
            <label><input type="radio"  name="status" value="1">Non Aktif
             <input type="radio" name="status" value="2">Aktif</label>
          </div>
          @else
              <input type="hidden" value="{{ $data->coa }}" class="form-control" id="coa" name="coa" required="required" />
             <input type="hidden" value="{{ $data->coa_rs }}" class="form-control" id="coa_rs" name="coa_rs" required="required" />
              <input type="hidden" name="type" value="{{$data->type}}">
             <input type="hidden" value="{{ $data->persen_rs }}" class="form-control" id="persen_rs" name="persen_rs" required="required" />
             <input type="hidden" value="{{ $data->coa_dr }}" class="form-control" id="coa_dr" name="coa_dr" required="required" />
             <input type="hidden" value="{{ $data->persen_dr }}" class="form-control" id="persen_dr" name="persen_dr" required="required" />
              <input type="hidden" value="{{$data->status}}" name="status">
              <input type="hidden" value="{{ $data->tarif_dasar }}" class="form-control" id="tarif_dasar" name="tarif_dasar" required="required" />
          @endif
            <div class="form-group">
              <label></label>
				  @if(Auth::user()->permission == 3)
					  <div>
						<button type="submit" class="btn btn-flat btn-primary">Update Service</button>
						<a href="{{ url('/mastertreatment') }}"><button type="button" class="btn btn-flat btn-default">Kembali</button></a>
						
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

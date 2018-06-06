
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

<div class="col-md-7">
  <div class="grid simple">
    <div class="grid-title no-border">
      <h4>Tambahkan Akun Ledger</h4>
      <div class="tools">
        <a href="javascript:;" class="collapse"></a> 
        <a href="javascript:;" class="reload"></a>
      </div>
    </div>
    <div class="grid-body no-border">
      <div class="row">
        <form action="{{ url('/coa/ledger') }}" method="post" role="form">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @if((Session::get('sess')))
            <div class="alert alert-block alert-info">
              <button data-dismiss="alert" class="close" type="button">&times;</button>
              {{ Session::get('sess') }}
            </div>
            @endif


            <div class="form-group">
              <label for="title">Ledger Akun *</label>
              <div>
                <input type="text" class="form-control" id="nm_coa" name="nm_coa" required="required" />
              </div>
            </div>

            <div class="form-group">
              <label for="title">Kode Akun *</label>
              <div>
                <input type="text" class="form-control" id="kode" name="kode" required="required" />
              </div>
            </div>

      
              <input type="hidden" name="grup" id="grup" value="2">
            <div class="form-group">
              <label for="parent">Parent Grup</label>
                 <select name="parend_id" class="form-control">
                <option value="0">None</option>
                     {!! $select_coa_group!!}
                 </select>
            </div>

            <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                    <label for="title">Kategori *</label>
                    <select class="form-control " name="coa_kategori">
                          <option>Pilih </option>
                          <option value="1">Harta</option>
                          <option value="2">Kewajiban</option>
                          <option value="3">Modal</option>
                          <option value="4">Pendapatan</option>
                          <option value="5">Biaya-biaya</option>

                          <option value="6">Pendapatan diluar usaha</option>
                          <option value="7">beban diluar usaha</option>
                          <option value="8">Pajak</option>
                      </select>
                  </div>
                </div>             

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="title">KAS *</label>
                      <select class="form-control " name="cash">
                          <option>Pilih </option>
                          <option value="0">Non Cash</option>
                          <option value="1">Cash</option>
                      </select>
                      </div>
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                        <label for="title">Opening Balance*</label>
                        <select class="form-control col-sm-3" name="type">
                         <option>Pilih</option>
                          <option value="1">Dr</option>
                          <option value="2">Cr</option>
                        </select>
                        <input type="hidden" class="form-control" required name="balance" id="balance" value="0">
                      </div>

                  </div>
            </div>

            <div class="form-group">
              <label for="saldo_awal">Saldo Awal</label>
              <input type="number" class="form-control text-right" id="saldo_awal" name="saldo_awal" value="0" />
            </div>            
           

            <div class="form-group">
              <label for="note">Keterangan</label>
              <textarea type="text" class="form-control" id="note" name="keterangan" /></textarea>
            </div>

            <div class="form-group">
              <label></label>
              <button type="submit" class="btn btn-flat btn-primary">Add Account Ledger</button>
              <a href="{{ url('coa') }}"><button type="button" class="btn btn-flat btn-default">Cancel</button></a>
            </div>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>

@stop

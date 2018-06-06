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

<div class="row">

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
          <form action="{{ url('/coa/editledger') }}" method="post" role="form">

              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="id_coa" value="{{ $data->id_coa }}">
              @if((Session::get('sess')))
              <div class="alert alert-block alert-info">
                <button data-dismiss="alert" class="close" type="button">&times;</button>
                {{ Session::get('sess') }}
              </div>
              @endif


              <div class="form-group">
                <label for="title">Ledger Akun *</label>
                <div>
                  <input type="text" value="{{ $data->nm_coa }}" class="form-control" id="nm_coa" name="nm_coa" required="required" />
                </div>
              </div>

              <div class="form-group">
                <label for="title">Kode Akun *</label>
                <div>
                  <input type="text" value="{{ $data->kode }}" class="form-control" id="kode" name="kode" required="required" />
                </div>
              </div>

        
                <input type="hidden" name="grup" id="grup" value="2">
              <div class="form-group">
                <label for="parent">Parent Grup</label>
                   <select name="parend_id" class="form-control">
                  <option value="0">None</option>
                       {!! $coa_parent!!}
                   </select>
              </div>

              <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                      <label for="title">Kategori *</label>
                      <select class="form-control " name="coa_kategori">
                            <option>Pilih </option>
                            <option value="1" {{empty($data) ? '' : "1" == $data->coa_kategori ? 'selected="selected"' : ''}}>Harta</option>
                            <option value="2" {{empty($data) ? '' : "2" == $data->coa_kategori ? 'selected="selected"' : ''}}>Kewajiban</option>
                            <option value="3" {{empty($data) ? '' : "3" == $data->coa_kategori ? 'selected="selected"' : ''}}>Modal</option>
                            <option value="4" {{empty($data) ? '' : "4" == $data->coa_kategori ? 'selected="selected"' : ''}}>Pendapatan</option>
                            <option value="5" {{empty($data) ? '' : "5" == $data->coa_kategori ? 'selected="selected"' : ''}}>Biaya-biaya</option>

                            <option value="5" {{empty($data) ? '' : "6" == $data->coa_kategori ? 'selected="selected"' : ''}}>Pendapatan diluar usaha</option>
                            <option value="5" {{empty($data) ? '' : "7" == $data->coa_kategori ? 'selected="selected"' : ''}}>beban diluar usaha</option>
                            <option value="5" {{empty($data) ? '' : "8" == $data->coa_kategori ? 'selected="selected"' : ''}}>Pajak</option>
                            
                        </select>
                    </div>
                  </div>             

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="title">KAS *</label>
                        <select class="form-control " name="cash">
                            <option>Pilih </option>
                            <option value="0" {{empty($data) ? '' : "0" == $data->cash ? 'selected="selected"' : ''}}>Non Cash</option>
                            <option value="1" {{empty($data) ? '' : '1' ==$data->cash ? 'selected="selected"' : ''}}>Cash</option>
                        </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="title">Opening Balance*</label>
                          <select class="form-control col-sm-3" name="type">
                           <option>Pilih</option>
                            <option value="1" {{empty($data) ? '' : "1" ==$data->type ? 'selected="selected"' : ''}}>Dr</option>
                            <option value="2" {{empty($data) ? '' : "2" ==$data->type ? 'selected="selected"' : ''}}>Cr</option>
                          </select>
                          <input type="hidden" class="form-control" required name="balance" id="balance" value="{{ $data->balance }}">
                        </div>

                    </div>
              </div>

              <div class="form-group">
                <label for="saldo_awal">Saldo Awal</label>
                <input type="number" class="form-control text-right" id="saldo_awal" name="saldo_awal" value="{{ $data->saldo_awal }}" />
              </div>            
             

              <div class="form-group">
                <label for="note">Keterangan</label>
  					    <textarea type="text" class="form-control" id="note" name="keterangan" />{{ $data->keterangan }}</textarea>
              </div>

              <div class="form-group">
                <label></label>
                <button type="submit" class="btn btn-flat btn-primary">Change Account Ledger</button>
                <a href="{{ url('coa') }}"><button type="button" class="btn btn-flat btn-default">Cancel</button></a>
              </div>
              </div>
          </form>
        </div>

      </div>
    </div>
    

     <div class="col-md-5">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>Log Akun</h4>
          </div>
          <div class="grid-body no-border">
            <table class="table">
              @forelse($logs as $log)
                <tr>
                  <td>
                    <small class="pull-right">{{ Format::indoDate2($log->created_at) }} at {{ Format::jam($log->created_at) }}</small>
                    <div class="form-group">
                      <strong>Diperbaharui</strong>
                    </div>

                    <div class="form-group">
                      <button class="btn btn-default btn-sm btn-block" data-toggle="collapse" data-target="#collapseExample{{ $log->id }}" aria-expanded="false" aria-controls="collapseExample{{ $log->id }}">Lihat Detail</button>
                    </div>

                    <div class="collapse" id="collapseExample{{ $log->id }}">
                    <div class="well">
                      <table style="width:100%;">
                        <tr>
                          <td>Oleh</td>
                          <td>{{ Format::full_name($log->id_karyawan) }}</td>
                        </tr>
                        <tr>
                          <td>Akun</td>
                          <td>{{ $log->nm_coa }}</td>
                        </tr>
                        <tr>
                          <td>Kode Akun</td>
                          <td>{{ $log->kode }}</td>
                        </tr>

                        <tr>
                          <td>Saldo Awal</td>
                          <td>{{ number_format($log->saldo_awal,0,',','.') }}</td>
                        </tr>
                      </table>  
                    </div>
                    </div>

                  </td>
                </tr>
              @empty  
                <tr>
                  <td>Tidak ada log</td>
                </tr>
              @endforelse  
            </table>
          </div>
        </div>
    </div>

  </div>




@stop

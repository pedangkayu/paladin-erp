@extends('Master.Template')
@section('csstop')
<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/treatment/update.js') }}"></script>

@endsection

@section('title')
  Detail Treatment
@endsection
@section('content')
<div class="row">
     <div class="col-sm-12">
      <div class="grid simple">
        <div class="grid-title no-border"></div>
        <div class="grid-body no-border">
          <h3> Nama  : {{$data->nama_pasien}} </h3>
          <h4> Alamat &nbsp;: {{ $data->alamat_pasien}} &nbsp; </h4>
          <h4> Kota &nbsp; &nbsp; &nbsp;: {{$data->kota_pasien}}</h4>
          <h4> Hp &nbsp; &nbsp; &nbsp; &nbsp; : {{  $data->hp_pasien}}</h4>
          <span class="text-muted">
          Tanggal Penanganan : {{ Format::indoDate2($data->created_at) }} &nbsp;{{ Format::hari($data->created_at) }}, {{ Format::jam($data->created_at) }}
          </span>
          <p><div class="well well-sm"></div></p>
          <div class="text-right">
            <tr >
              <a href="{{ url('/treatment') }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
            </tr>
          </div>
        </div>
      </div>
    </div>
  <form method="post" action="{{ url('/treatment/update') }}">
  <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
    <input type="hidden" class="form-control" name="unit_jasa" value="{{$data->id_unit}}">{{-- jasa --}}
    <input type="hidden" class="form-control" name="unit" value="{{$data->id_unit_item}}"> {{-- Barang --}}
  <div class="col-sm-12">
    <div class="grid simple">
      <div class="grid-title no-border"></div>
      <div class="grid-body no-border">
        <div class="table-responsive">
          <table class="table table-bordered">
          @if($jumlah->count() > 0)
            <thead>
              <tr>
                <th width="20%" class="text-center">Jasa/Tindakan</th>
                <th colspan="2" width="15%" class="text-center">Dokter</th>
              </tr>
            @else
            @endif
            </thead>
            <?php $no=1; ?>
            @foreach($tindakan as $item)
           
                        <input type="hidden" name="tipee[]" value="1">
                        <input type="hidden" name="id_treatment_item[]" value="{{$item->id_treatment_item}}">
                        <input type="hidden" name="id_treatment" value="{{$item->id_treatment}}">
                        <input type="hidden" name="id_service1[]" value="{{$item->id_service}}" >
                        <input type="hidden" name="service_kod[]" value="{{$item->service_kode}}">
                        <input type="hidden" name="tarif_das[]" value="{{$item->tarif_dasar}}">
          
           @if($item->tipe==2)   
                <tr class="treatment_">
                  <td>
                    <div>{{$item->nm_service}}</div>
                      <input type="text" name="id_treatment" value="{{$item->id_treatment}}">
                      <input type="hidden" name="id_service1[]" value="{{$item->id_service}}" >
                      <input type="hidden" name="service_kod[]" value="{{$item->service_kode}}">
                      <input type="hidden" name="tarif_das[]" value="{{$item->tarif_dasar}}">
                      <div class="link text-muted">
                        <small>
                          [
                            @if($item->status < 4)
                              <a href="javascript:;" title="Hapus Jasa Yang udah Di Pilih" onclick="hapus({{ $item->id_treatment_item}});">
                              <i class="fa fa-trash-o text-danger"></i>
                            </a>
                            @endif
                          ]
                           @if($item->jabatan==1)
                            DPJP
                           @elseif($item->jabatan==2)
                                OPERATOR/ANGGOTA
                           @else

                           @endif
                            ,{{ Format::indoDate2($item->created_at) }}, {{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}
                        </small>
                      </div>
                  </td>
                  <td colspan="2">
                    <input type="hidden" name="jabatan[]" value="{{$item->jabatan}}">
                    <input type="hidden" name="id_treatment_item[]" value="{{$item->id_treatment_item}}">
                      @if($item->id_dokter > 0)
                          <input type="hidden" name="id_treatment_dokter[]" value="{{$item->id_treatment_dokter}}">
                          <select class="form-control" name="id_dokter[]" value="" id="id_dokter" >
                              <option value="">Pilih dokter</option>
                              @foreach ($dokter as $dok )
                               <option value="{{$dok->id_karyawan}}" {{empty($item) ? '' : $dok->id_karyawan == $item->id_dokter ? 'selected="selected"' : ''}}>{{$dok->nm_depan}} &nbsp; {{$dok->nm_belakang}}</option> 
                              @endforeach
                        </select>
                      @else
                         <input type="hidden" name="id_treatment_dokter[]" value="{{$item->id_treatment_dokter}}">
                          <input type="hidden" name="id_dokter[]" value="0">
                      @endif
                  </td>
                </tr>
            
          @else
               <?php  
                    $bhps=$item->bhp()
                    ->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
                    ->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
                      ->join('data_item_gudang', 'data_item_gudang.id_item_gudang', '=','data_resep_item.id_item_gudang')
                  ->select(
                      'data_resep_item.*',
                      'data_item_gudang.id_gudang',
                      'data_item_gudang.in as masuk',
                      'data_item_gudang.out as Keluar',
                      'data_barang.nm_barang',
                      'data_barang.harga_jual',
                      'ref_satuan.nm_satuan'
                      )
                    ->get();
                    $c=$no;
                    ?>
              @if($bhps->count() > 0)
              <thead>
                <tr>
                  <th width="20%" class="text-center">Nama &nbsp;{{$item->nm_service}} &nbsp; </th>
                  <th width="15%" class="text-center"> Stok</th>
                  <th width="15%" class="text-center">qty / Satuan</th>
                </tr>
                <tr><td colspan="3"> 
                      <button class="btn btn-primary"  data-placement="top" title="Tambah BHP ini adalah untuk menambah BHP yang belum ada dalam Tindakan ini" type="button"  onclick="reseptretment({{$item->id_treatment_item }},1);" >Tambah BHP</button>
                      <button class="btn btn-info " type="button"  onclick="updatejasa({{$item->id_treatment_item}},1);" >Tambah Jasa</button>
                      <span class="label label-info">Stok di Atas 10</span>
                      <span class="label label-warning">Stok Di Bawah 10</span>
                      <span class="label label-important">Stok sudah Kosong</span>
                        <spin>Perhatikan Stok anda dulu sebelum melakukan simpan transaksi</spin>
                    </td>
                </tr>
              </thead>
              @endif
              @foreach ($bhps as $obt1)
                <tr>
                  <td >{{$obt1->nm_barang}}
                  </td>
                  <td>
                    <div class="input-group input-group-sm ">
                      <center>
                      @if(($obt1->masuk - $obt1->Keluar) > 10)
                        <span class="label label-info">
                      @elseif(($obt1->masuk - $obt1->Keluar) < 10)
                        <span class="label label-warning">
                      @else
                        <span class="label label-important">
                      @endif
                        {{($obt1->masuk - $obt1->Keluar)}} &nbsp;{{$obt1->nm_satuan}}
                        </span>
                      </center>
                    </div>
                  </td>
                  <td class="col-sm-3">
                        <input type="hidden" name="id_item_gudang[]" value="{{$obt1->id_item_gudang}}">
                        <input type="hidden" name="id_gudang[]" value="{{$obt1->id_gudang}}">
                        <input type="hidden" name="id_treatment_itembhp[]" value="{{$obt1->id_treatment_item}}">
                        <input type="hidden" name="id_barang[]" value="{{$obt1->id_barang}}">
                        <input type="hidden" name="id_resep_item[]" value="{{$obt1->id_resep_item}}" >
                        <input type="hidden" name="harga_jual[]" value="{{$obt1->harga_jual}}" >
                    <div class="input-group input-group-sm">
                        <input type="hidden"   min="0" max="{{$obt1->qty}}" value="{{$obt1->qty}}"  name="qty[]" readonly="readonly"  class="form-control text-left"  required />
                        <input type="number"   min="0"  value="{{$obt1->qty}}"  name="kurang[]"  class="form-control text-left"  required />
                        <span class="input-group-addon">{{$obt1->nm_satuan}}</span>
                        <input type="hidden" name="id_satuan[]" value="{{$obt1->id_satuan}}">
                        <input type="hidden" name="reuse[]" value="{{$obt1->reuse}}">
                        &nbsp;
                        <span class="input-group-addon">
                        <i class="text-danger"> {{ $obt1->reuse==1 ? 'Reuse' : ' ' }}</i>
                        </span>

                    </div>
                  </td>


                </tr>
            <?php $c++; ?>
              @endforeach
              <tr>
                  <td colspan="4" class="item-{{$item->id_treatment_item}}"></td>
             </tr>
          @endif
         
         <?php $no++; ?>
            @endforeach
             <tr>
                       <td colspan="4" class="content-jasaupdate-"></td>
              </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
   <div class="grid-footer">
    <div class="row">
      <div class="col-sm-2">
        <a href="{{ url('/treatment') }}" class="btn btn-default btn-block">Batal</a>
      </div>
      <div class="col-sm-offset-7 col-sm-3">
        <button class="btn btn-primary btn-block" type="submit">Simpan</button>
      </div>
    </div>
  </div>
</div>
</form>
<div class="modal fade" id="reseptreatment" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Data BHP</h4>
      </div>
      <div class="modal-body">
          <li class="active" data-toggle="link-tab"><a href="#items"></a></li>
        <div class="tab-content">
          <div class="tab-pane active"  id="items">
            <div class="row">
              <div class="col-sm-4">
                <input type="text" name="modal-kode-itemupdate" class="form-control" placeholder="Kode BHP">
              </div>
              <div class="col-sm-5">
                <input type="text" name="modal-barang-itemupdate" class="form-control" placeholder="Nama  BHP">
              </div>
              <div class="col-sm-3">
                <div class="btn-group">
                  <button class="btn btn-white btn-search-itemupdate"><i class="fa fa-search"></i></button>
                  <button title="Refresh" class="btn btn-white btn-search-itemupdate"><i class="fa fa-refresh"></i></button>
                </div>
              </div>
            </div>
            <br />
            <div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="modal-itemupdate-list">
                  <tr>
                    <td colspan="3">Memuat...</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-itemupdate-pagin text-center"></div>
            <input type="hidden" name="id_treatment_item" value="0">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
        <input type="hidden" name="home-tab" value="#items">
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="updatejasaform" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Data Jasa/ Tindakan</h4>
      </div>
      <div class="modal-body">
          <li class="active" data-toggle="link-tab"><a href="#items"></a></li>
        <div class="tab-content">
          <div class="tab-pane active"  id="items">
           <div class="row">
              <!-- <div class="col-sm-4">
                <input type="text" name="modal-kode-jasa" class="form-control" placeholder="Jasa ">
              </div> -->
              <div class="col-sm-8">
                <input type="text" name="modal-nama-jasa" class="form-control" placeholder="Jasa">
              </div>
              <div class="col-sm-3">
                <div class="btn-group">
                  <button class="btn btn-white btn-search-jasa"><i class="fa fa-search"></i></button>
                  <button title="Refresh" class="btn btn-white btn-search-jasa"><i class="fa fa-refresh"></i></button>
                </div>
              </div>
            </div>
            <br />
            <div>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="modal-jasa-list">
                  <tr>
                    <td colspan="3">Memuat...</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-jasa-pagin text-center"></div>
            <input type="hidden" name="id_treatment_item" value="0">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
        <input type="hidden" name="home-tab" value="#items">
      </div>
    </div>
  </div>
</div>
@endsection
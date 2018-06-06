@extends('Master.frontend')

@section('meta')
<script type="text/javascript" src="{{ asset('js/treatment/update.js') }}"></script>
@endsection

@section('title')
	Detail Resep OBat
@endsection
@section('content')
 <div class="row">
    <div class="col-md-12">
      <div class="grid simple ">
       {{--  <div class="grid-title no-border">
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div> --}}
        <div class="grid-body no-border">
          <div class="row-fluid">
            <h3>Catatan <span class="semi-bold"></span></h3>
            <br>
            <div class="alert">
             <tr>
              <td>
                <h4><b>Welcome</b> &nbsp;{{ Me::data()->nm_depan }}&nbsp;{{ Me::data()->nm_belakang }}</h4>
                    <h5>Saat ini Anda berada diLayanan <b>{{$test->gudang_jasa}}</b> dan Menggunakan Obat/Barang<b> {{$test->nm_gudang}}</b>
                    </h5>
                Info:&nbsp; gunakan tombol <a href="#" class="link">logout</a> untuk menutup halaman ini 
              <a href="{{url('/authhc/logout')}}" class="btn-danger btn  btn-sm btn-small pull-right">Logout</a>
                
              </td>
            </tr>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
	<div class="row">
		<div class="col-sm-11">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
      <!-- dokter -->

          <h3> Nama  : {{$data->nama_pasien}} </h3>
          <h4> Alamat &nbsp;: {{ $data->alamat_pasien}} &nbsp; </h4>
          <h4> Kota &nbsp; &nbsp; &nbsp;: {{$data->kota_pasien}}</h4>
          <h4> Hp &nbsp; &nbsp; &nbsp; &nbsp; : {{  $data->hp_pasien}}</h4>
          <span class="text-muted">
          Tanggal Penanganan : {{ Format::indoDate2($data->created_at) }} &nbsp;{{ Format::hari($data->created_at) }}, {{ Format::jam($data->created_at) }}
          </span>
          
					<div class="text-right"><tr><td>No Treatment</td><td>&nbsp;{{$data->nomor_treatment}}</td></tr></div>
            <div class="text-right">
					<tr >
						<a href="{{ url('/treatment/viewhc/'.$data->id_pasien.'/'.$test->id_layanan.'/'.$test->no_antrian) }}" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
					</tr>
					</div>
				</div>
			</div>
      </div>
      <div class="col-sm-11">
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
    								<tr> 
      									<th  class="text-center" >Jenis</th>
                      	<th  class="text-center" >Dokter / Jabatan</th>
                        <th>Action</th>
    								</tr>
    							</thead>
                  <?php $no=1 ?>
                  @foreach($tindakan as $item)
                    @if($item->tipe==2)
                        @if($item->status == 1)
                          <tr class="refound_{{$item->id_treatment_item}}">
                        @else
                          <tr class="batalrefund_{{$item->id_treatment_item}}">
                        @endif
                            <td>{{$item->nm_service}}
                                  <div class="text-muted"><small>{{ Format::indoDate2($item->created_at) }}, {{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small></div>
                            </td>
                            <td>
                               <div>{{$item->nm_depan}} &nbsp; {{$item->nm_belakang}}
                                    <div class="link text-muted">
                                        <small>
                                         <i class="text-danger"> 
                                           @if($item->jabatan==1)
                                              DPJP
                                           @elseif($item->jabatan==2)
                                                OPERATOR/ANGGOTA
                                           @else

                                           @endif
                                        </i>
                                        </small>
                                </div>
                            </td>
                            <td> 
                               @if($item->status ==4)
                                  <a href="javascript:;" title="Pengajuan Refund Jasa {{$item->nm_service}}" onclick="refound({{ $item->id_treatment_item}});"><button type="button" class="btn-danger">Refund</button></a>
                                @elseif($item->status ==2 )
                                  <a href="javascript:;" title="Batal Refund Jasa {{$item->nm_service}}" onclick="batalrefound({{ $item->id_treatment_item}});"><button type="button" class="btn-success">Batal Refund</button></a>
                                @endif
                            </td>
                      
                      </tr>
                    @else
                       <?php  
                          $bhps=$item->bhp()
                            ->leftJoin('data_barang', 'data_barang.id_barang', '=', 'data_resep_item.id_barang')
                            ->leftJoin('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_resep_item.id_satuan')
                            ->select(
                              'data_resep_item.*',
                              'data_barang.nm_barang',
                              'ref_satuan.nm_satuan'
                              )
                            ->get();
                           $c=$no;
                            // $c=1;
                          ?>
                             @if($bhps->count() > 0)
                            <thead>
                              <tr>
                              
                                <th width="20%" class="text-center">Nama ---<i>{{$item->nm_service}}</i>---</th>
                                <th width="15%" class="text-center">qty</th>
                                <th width="3%">Satuan</th>
                              </tr>
                            </thead>
                            @endif
                             @foreach ($bhps as $obt1)
                                <tr>
                                  
                                  <td>{{$obt1->nm_barang}}</td>
                                  <td>{{$obt1->qty}}&nbsp;</td>
                                  <td>{{$obt1->nm_satuan}}</td>
                                </tr>
                            <?php $c++; ?>
                              @endforeach


                  @endif
                  @endforeach
                
             
          </table>
          </div>
				</div>
			</div>
		</div>
	</div>
@endsection

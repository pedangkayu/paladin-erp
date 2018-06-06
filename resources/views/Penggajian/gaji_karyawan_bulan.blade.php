@extends('Master.Template')
@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
	<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/js/akunting/fakturpembelian/view.js') }}"></script>
	<script type="text/javascript">
	function btn_bayar(id) {
	    var sure = confirm('Apakah Anda yakin untuk membayarkan gaji ini ?');
	    $.post(_base_url + '/penggajian/btnbayar', {id : id}, function(json){
	        $('.btn_bayaran').remove();
			location.reload();
	        $('#detail_gajiku').modal('hide');
	    }, 'json');

	}
	</script>
	<style>
		.datepicker{z-index:1151 !important;}
	</style>
@endsection

@section('title')
Numerisasi Gaji Pegawai
@endsection

@section('content')

<div class="row">
	<!-- left -->
	<div class="col-sm-9">
		<div class="grid simple header-status">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">

				<div class="row">
					<div class="col-sm-7">
						<h5> PERIODE GAJI {{ date('m/d/Y') }} </h5>
						<h3> {{$data_karyawan->nm_depan}} {{$data_karyawan->nm_belakang}}</h3>
						<h5> Departemen : {{$data_karyawan->nm_departemen}} </h5>
						<h5> Jabatan : {{$data_karyawan->nm_jabatan}} </h5>
						<h5> Profesi : {{$data_karyawan->nm_profesi}} </h5>
						<hr />
						<h5>
                            <div>
                                Status Pembayaran :
								@if($data_karyawan->status_pembayaran ==1)
									<b>Belum Dibayarkan</b>
								@elseif($data_karyawan->status_pembayaran==2)
									<b>Selesai Dibayarkan</b>
								@endif
                            </div>
                        </h5>
					</div>

					<div class="col-sm-5 text-right">
					   <h5>Total Pendapatan : Rp. {{ number_format($data_karyawan->total_pendapatan,0,',','.')}}</h5>
					   <h5>Total Potongan : Rp. {{number_format($data_karyawan->total_potongan,0,',','.')}}</h5>
					   <h5>Total Gaji Bersih : Rp. {{ number_format($data_karyawan->sisa_gaji,0,',','.')}}</h5>
				    </div>
			     </div>
		</div>
	</div>
</div>
<div class="col-sm-3">
	<div class="grid simple">
		<div class="grid-title no-border"></div>
		<div class="grid-body no-border">
			@if($data_karyawan->status_pembayaran ==1)
			<a class="btn btn-primary btn-block" href="{{ url('/penggajian/update/' . $data_karyawan->id_log_honor) }}"><i class="pull-left fa fa-pencil"></i> Perbaharui</a>
			<a href="#"  data-toggle="modal" data-target="#detail_gajiku" class="btn btn-danger btn-block" title="Review Data">
				<i class="pull-left fa fa-plus"></i> Add Payment</a>
			@elseif($data_karyawan->status_pembayaran==2)
			<a class="btn btn-primary btn-block" target="_blank" href="{{ url('/penggajian/print/' . $data_karyawan->id_log_honor) }}"><i class="pull-left fa fa-print"></i> Print</a>
			@endif
			<a class="btn btn-white btn-block" href="{{ url('/penggajian') }}"><i class="pull-left fa fa-arrow-circle-left"></i> Kembali</a>
		</div>
	</div>
</div>
	<div class="col-sm-6">
		<div class="grid simple">
			<div class="grid-title no-border">
			    <h4> Data <b>Pendapatan</b></h4>
			</div>

			<div class="grid-body no-border">
				<table class="table">
	                <thead>
	                    <tr>
	                        <th>Pendapatan </th>
	                        <th>Jumlah</th>
	                    </tr>
	                </thead>
	                <tbody class="content-gaji">
						@foreach($detail_gaji as $item)
						<tr>
							<td>{{ $item->nm_komponen_honor }}</td>
							<td>Rp. {{ number_format($item->nilai,0,',','.') }}</td>
						</tr>
						@endforeach
	                </tbody>
	            </table>
			</div>
		</div>
	</div>
	@if($data_potongan->count() >0)
		<div class="col-sm-6">
			<div class="grid simple">
				<div class="grid-title no-border">
				    <h4> Data <b>Potongan Honor</b></h4>
				</div>

				<div class="grid-body no-border">
					<table class="table">
		                <thead>
		                    <tr>
		                        <th>Potongan </th>
		                        <th>Jumlah</th>
		                    </tr>
		                </thead>
		                <tbody class="content-gaji">
							@foreach($data_potongan as $p)
							<tr>
								<td>{{ $p->nm_potongan }}</td>
								<td>Rp. {{ number_format($p->jumlah_potongan,0,',','.') }}</td>
							</tr>
							@endforeach
		                </tbody>
		            </table>
				</div>
			</div>
		</div>
	@endif
	@if($data_casbon->count() >0)
		<div class="col-sm-6">
			<div class="grid simple">
				<div class="grid-title no-border">Potongan Kasbon</div>
				<div class="grid-body no-border">
					<table class="table">
		                <thead>
		                    <tr>
		                        <th>Potongan </th>
		                        <th>Jumlah</th>
		                    </tr>
		                </thead>
		                <tbody class="content-gaji">
							@foreach($data_casbon as $p)
							<tr>
								<td>{{ $p->nm_potongan }}</td>
								<td>Rp. {{ number_format($p->jumlah_potongan,0,',','.') }}</td>
							</tr>
							@endforeach
		                </tbody>
		            </table>
				</div>
			</div>
		</div>
		@endif
<!-- right -->
</div>
<div class="modal fade" id="detail_gajiku" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	   <div class="modal-content">
	       <div class="modal-header">
	           <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	           <h4 class="modal-title" id="myModalLabel">Data Detail Honor <span class="viewkode">karyawan Priode  {{Format::indoDate2($data_karyawan->periode) }}</span></h4>
	       </div>
	       <div class="modal-body">
	           <div class="row">
	               <div class="col-sm-6">
	                   <div class="grid solid red">
	                       <div class="grid-body">
	                           <p>
	                               <div class="data-karyawan">
	                                   <strong> Nama karyawan</strong> : {{$data_karyawan->nm_depan}}&nbsp; {{$data_karyawan->nm_belakang}}
	                               </div>
	                           </p>
	                           <p>
	                               <div class="data-karyawan">
	                                   <strong> Departemen</strong> : {{$data_karyawan->nm_departemen}}
	                               </div>
	                           </p>
	                           <p>
	                               <div class="data-karyawan">
	                                   <strong> jabatan</strong> : {{$data_karyawan->nm_jabatan}}
	                               </div>
	                           </p>
	                           <p>
	                               <div class="data_karyawan">
	                                   <strong>Profesi</strong>  : {{$data_karyawan->nm_profesi}}
	                               </div>
	                           </p>
	                       </div>
	                   </div>
	               </div>
	               <div class="col-sm-6">
	                   <div class="grid solid green">
	                       <div class="grid-body">
	                           <p>
	                               <div class="data_karyawan">
	                                   <strong>Total Pendapatan</strong>  : Rp. {{ number_format($data_karyawan->total_pendapatan,0,',','.')}}
	                               </div>
	                           </p>
	                           <p>
	                               <div class="data_karyawan">
	                                   <strong>Total Potongan</strong>  : Rp. {{number_format($data_karyawan->total_potongan,0,',','.')}}
	                               </div>
	                           </p>
	                           <p>
	                               <div class="data_karyawan">
	                                   <strong>Total Gaji Bersih</strong>  :  Rp. {{ number_format($data_karyawan->sisa_gaji,0,',','.')}}
	                               </div>
	                           </p>
	                           <hr />
	   						<h5>
	                               <div>
	                                   Status Pembayaran :
	   								@if($data_karyawan->status_pembayaran ==1)
	   									<b>Belum Dibayarkan</b>
	   								@elseif($data_karyawan->status_pembayaran==2)
	   									<b>Selesai Dibayarkan</b>
	   								@endif
	                               </div>
	                           </h5>

	                       </div>
	                   </div>
	               </div>
	               <div class="col-sm-6">
	                   <div class="grid simple">
	                       <div class="grid-title no-border">
	                           <h4> Data <strong>ditemukan</strong></h4>
	                       </div>
	                       <div class="grid-body no-border">
	                           <div class="table-responsive">
	                               <table class="table table-striped daftar-skb">
	                   	                <thead>
	                   	                    <tr>
	                   	                        <th>Pendapatan </th>
	                   	                        <th>Jumlah</th>
	                   	                    </tr>
	                   	                </thead>
	                   	                <tbody class="content-gaji">
	                                        <?php
	                                        $total_pendapatan = '';
	                                         ?>
	                   						@foreach($detail_gaji as $item)
	                                        <?php
	                                            $total_pendapatan +=$item->nilai;
	                                         ?>
	                                        <tr>
	                   							<td>{{ $item->nm_komponen_honor }}</td>
	                   							<td>Rp. {{ number_format($item->nilai,0,',','.') }}</td>
	                   						</tr>
	                   						@endforeach
	                                        <tr>
	                                            <td>
	                                                <b>Total Pendapatan</b>
	                                            </td>
	                                            <td>
	                                                Rp. {{ number_format($total_pendapatan,0,',','.') }}
	                                            </td>
	                                        </tr>
	                   	                </tbody>
	                               </table>
	                           </div>
	                       </div>
	                    </div>
	                 </div>
	                 @if($data_casbon->count() >0)
	              		<div class="col-sm-6">
	              			<div class="grid simple">
	              				<div class="grid-title no-border">Potongan Kasbon</div>
	              				<div class="grid-body no-border">
	              					<table class="table">
	              		                <thead>
	              		                    <tr>
	              		                        <th>Potongan </th>
	              		                        <th>Jumlah</th>
	              		                    </tr>
	              		                </thead>
	              		                <tbody class="content-gaji">
	                                        <?php
	                                        $total_potongan= '';
	                                         ?>
	              							@foreach($data_casbon as $p)
	                                        <?php
	                                        $total_potongan += $p->jumlah_potongan;
	                                         ?>
	              							<tr>
	              								<td>{{ $p->nm_potongan }}</td>
	              								<td>Rp. {{ number_format($p->jumlah_potongan,0,',','.') }}</td>
	              							</tr>
	              							@endforeach
	                                        <tr>
	                                            <td>
	                                                <b>Total Kasbon</b>
	                                            </td>
	                                            <td>
	                                                <b>Rp. {{ number_format($total_potongan,0,',','.') }}</b>
	                                            </td>
	                                        </tr>
	              		                </tbody>
	              		            </table>
	              				</div>
	              			</div>
	              		</div>
	              		@endif
	                 @if($data_potongan->count() >0)
	             		<div class="col-sm-6">
	             			<div class="grid simple">
	             				<div class="grid-title no-border">
	             				    <h4> Data <b>Potongan Honor</b></h4>
	             				</div>
	             				<div class="grid-body no-border">
	             					<table class="table">
	             		                <thead>
	             		                    <tr>
	             		                        <th>Potongan </th>
	             		                        <th>Jumlah</th>
	             		                    </tr>
	             		                </thead>
	             		                <tbody class="content-gaji">
	                                        <?php
	                                        $potongan_honor ='';
	                                         ?>
	             							@foreach($data_potongan as $p)
	                                            <?php $potongan_honor +=$p->jumlah_potongan; ?>
	                                        <tr>
	             								<td>{{ $p->nm_potongan }}</td>
	             								<td>Rp. {{ number_format($p->jumlah_potongan,0,',','.') }}</td>
	             							</tr>
	             							@endforeach
	                                        <tr>
	                                            <td>
	                                                <b>Total Potongan Honor</b>
	                                            </td>
	                                            <td>
	                                                <b>Rp. {{ number_format($potongan_honor,0,',','.') }}</b>
	                                            </td>
	                                        </tr>
	             		                </tbody>
	             		            </table>
	             				</div>
	             			</div>
	             		</div>
	             	@endif

	           </div>
	       </div>
	       <div class="modal-footer">
	           <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
	           @if($data_karyawan->status_pembayaran ==1)
	            <button data-loading-text="<i class=\'fa fa-circle-o-notch fa-spin\'></i> Proses..." class="btn btn-primary pull-right btn_bayaran" onclick="btn_bayar({{$data_karyawan->id_log_honor}});"><i class="fa fa-check"></i>Bayar</button>
	           @elseif($data_karyawan->status_pembayaran==2)
	           @endif

	           <span class="btn-accpinjaman"></span>
	       </div>
	   </div>
	</div>
</div>
@endsection

@section('footer')
<!-- Modal -->

@endsection

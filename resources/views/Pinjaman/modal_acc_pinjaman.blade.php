<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">NO <span class="viewkode">{{$item->no_pinjaman}}</span></h4>
		</div>
		<div class="modal-body">
			@if($item->id_acc > 0)
	 			<div class="grid simple">
	                <div class="grid-title no-border"></div>
	                <div class="grid-body no-border">
						<div class="row">
							<div class="col-sm-6 ">
			                    <b>Dibuat : {{$item->user_a}} {{$item->user_b}}</b> <br />
			                    <small class="text-muted">
			                    	{{Format::hari($item->tanggal) }}, {{Format::indoDate2($item->tanggal) }} {{Format::jam($item->tanggal) }}
			                    </small>
			                </div>
							<div class="col-sm-6 text-right">
			                    <b>Disetujui : {{$item->depan_approval}} {{$item->belakang_approvL}}</b> <br />
			                    <small class="text-muted">
			                    	{{Format::hari($item->tgl_approval) }}, {{Format::indoDate2($item->tgl_approval) }} {{Format::jam($item->tgl_approval) }}
			                    </small>
			                </div>
			            </div>
	                </div>
	            </div>
			@endif
	            <div class="grid simple">
	                <div class="grid-title no-border"></div>
	                <div class="grid-body no-border">
	                    <div class="row">
	                    	<div class="col-sm-12 text-center">
	                    		<b>Detail Pemohon Pinjaman</b>
	                    	</div>
	                        <div class="col-sm-12 ">
	                            <div class="form-group">
	                                <b>Nama :</b> {{ $item->nd}} {{$item->nb}}
	                            </div>
	                        </div>
		                    <div class="col-sm-12 ">
		                        <div class="form-group">
		                              <b>No Pinjaman :</b> {{$item->no_pinjaman}}
		                        </div>
		                    </div>
		                    <div class="col-sm-12 ">
		                        <div class="form-group">
		                              <b>Alamat :</b> {{$item->alamat}}
		                        </div>
		                    </div>
		                    <div class="col-sm-12">
		                    	<div class="form-group"><b>Telpon :</b> {{$item->telp}}</div>
		                    </div>
		                    <div class="col-sm-12">
		                    	<div class="form-group"><b>Nominal Pinjaman  :</b> {{number_format($item->nominal,0,',','.')}}   </div>
		                    </div>
		                    <div class="col-sm-12">
		                    	<div class="form-group"><b>Durasi Pinjaman :</b> {{Format::indoDate2($item->start_time)}}  {{ Format::hari($item->start_time) }} s/d    {{Format::indoDate2($item->end_time)}}{{ Format::hari($item->end_time) }}  </div>
		                    </div>
	                    </div>
	                </div>
	            </div>
	            @if($item->status < 2)
	            <button item-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Proses..." class="btn btn-primary btn-accpinjaman" onclick="acc({{$item->id_loan}});"><i class="fa fa-check"></i> Setujui</button>
	            @endif
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Keluar</button>
			<span class="btn-accpinjaman"></span>
		</div>
	</div>
</div>
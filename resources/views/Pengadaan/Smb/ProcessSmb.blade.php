@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/pengadaan/skb.js') }}"></script>
	<script type="text/javascript">
		$(function(){
			$('[type="number"]').change(function(){
				var val = $(this).val();
				var max = $(this).data('max');
				var qty = $(this).data('qty');
				if(val > max){
					$(this).val(qty);
				}if(val < 0){
					$(this).val(qty);
				}
			});
		});
	</script>
@endsection

@section('title')
	Proses SPB
@endsection

@section('content')
	
	<form method="post" action="{{ url('/Smb/process') }}" id="prosesSPB">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="id_mutasi_spb" value="{{ $spb->id_mutasi_spb }}">
		<input type="hidden" name="tipe" value="{{ $spb->tipe }}">
		<input type="hidden" name="id_unit_asal" value="{{$spb->id_unit_asal}}">
		<input type="hidden" name="id_unit_tujuan" value="{{$spb->id_unit_tujuan}}">
		<div class="row">
			<div class="col-sm-9">
				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">

						<div class="row">
							<div class="col-sm-6"><br />
								<h1><i>No. {{ $spb->no_mutasi_spb }}</i></h1>
							</div>
							<div class="col-sm-6 text-right">
								<address>
									<strong>Tanggal</strong>
									<p>
										{{ Format::indoDate($spb->created_at) }}<br />
										<small class="text-muted">{{ Format::hari($spb->created_at) }}, {{ Format::jam($spb->created_at) }}</small>
									</p>
									<strong>Dept.</strong>
									<p>{{ $spb->nm_departemen }}</p>
								</address>
							</div>
						</div>

						<p>
							<textarea name="keterangan" class="form-control">{{ $spb->keterangan }} - {{ '@' . ucwords(Me::data()->nm_depan) . ucwords(Me::data()->nm_belakang) }} : ...</textarea>
							<small>* Anda bisa menambahkan keterangan di sini</small>
						</p>

						<div class="text-right">

							<div class="row">
								<div class="col-sm-6 text-left">
									<div class="checkbox check-primary">
										<input type="checkbox" name="view_bacth" id="view_bacth">
										<label for="view_bacth">Tampilkan List Batch setelah diproses</label>
									</div>
								</div>
								<div class="col-sm-6">
									<a class="btn btn-default" href="{{ url('/skb/spb') }}"><i class="fa fa-arrow-circle-left"></i> Batal</a>
									@if(Auth::user()->permission > 1)
									<button class="btn btn-primary btn-prosesSPB" type="button"><i class="fa fa-cog"></i> Proses</button>
									@endif
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="grid simple">
					<div class="grid-title no-border"></div>
					<div class="grid-body no-border">
						<address>
							<strong>Deadline</strong>
							<p>
								{{ Format::indoDate($spb->deadline) }}<br />
								<small class="text-muted">{{ Format::hari($spb->deadline) }}, {{ Format::jam($spb->deadline) }}</small>
							</p>
							<strong>Oleh</strong>
							<p>{{ $spb->nm_depan }} {{ $spb->nm_belakang }}</p>
							<strong>Acc</strong>
							<p>{{ $spb->acc_depan }} {{ $spb->acc_belakang }}</p>
						</address>

					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="grid simple">
					<div class="grid-title no-border">
						<h4>{{ count($items) }} barang <strong>ditemukan</strong></h4>
					</div>
					<div class="grid-body no-border">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th width="15%">Kode</th>
										<th width="15%">Nama Barang</th>
										<th width="10%" class="text-right">Sisa</th>
										<th width="10%" class="text-right">Req Qty</th>
{{-- 										<th width="10%" class="text-right">Realisasi</th> --}}
										<th width="20%" class="text-right">Acc Qty</th>
										<th width="30%">Ket.</th>
									</tr>
								</thead>
								<tbody>

								<?php
								$gudang= \Me::subgudang()->id_gudang;
								 $data = $spb->spbm()
				                        ->join('data_barang', 'data_barang.id_barang', '=', 'data_mutasi_spb_item.id_item')
				                        ->join('ref_satuan', 'ref_satuan.id_satuan', '=', 'data_mutasi_spb_item.id_satuan')
				                        ->leftJoin('data_item_gudang', 'data_item_gudang.id_barang', '=', 'data_barang.id_barang')
				                        ->where('data_item_gudang.id_gudang',$gudang)
				                        ->select(
				                            'data_mutasi_spb_item.*',
				                            'data_barang.kode',
				                            'data_barang.nm_barang',
				                            'data_barang.harga_jual',
				                            'ref_satuan.nm_satuan',
				                            'data_item_gudang.in',
				                            'data_item_gudang.out',
				                            'data_barang.reuse',
				                            'data_item_gudang.id_item_gudang'
				                            )
				                        ->get();

								?>
									@foreach($data as $item)
										
										<tr>
											<!-- kode -->
											<td>
												{{ $item->kode }}
												<input type="hidden" name="id_mutasi_spb_item[]" value="{{ $item->id_mutasi_spb_item }}">
												<input type="hidden" name="id_barang[]" value="{{ $item->id_item }}">
												<input type="hidden" name="id_unit_item[]" value="{{ $item->id_unit }}">
												<input type="hidden" name="kets[]" value="{{ $item->keterangan }}">
												<input type="hidden" name="sisa[]" value="{{ ($item->in - $item->out) }}">
												<input type="hidden" name="id_satuan[]" value="{{ $item->id_satuan }}">
												<input type="hidden" name="qty_awal[]" value="{{ $item->qty_awal }}">
												<input type="hidden" name="id_item_gudang[]" value="{{$item->id_item_gudang}}">
											</td>
											<!-- Nama Barang -->
											<td>{{ $item->nm_barang }}</td>
											<!-- Sisa -->
											<td class="text-right">{{ number_format($item->in - $item->out,0,',','.') }} {{ $item->nm_satuan}}</td>
											<!-- Req Qty -->
											<td class="text-right">{{ number_format($item->qty_awal,1,',','.') }} {{ $item->nm_satuan }}</td>
											<td>
												<div class="input-group transparent">
													<span class="input-group-addon">&nbsp;</span>
												  	<input
														type="number"
														name="qty_acc[]"
														class="form-control text-right"
														required
														value="{{ $item->qty_awal > ($item->in - $item->out) ? ((($item->in - $item->out) - $item->qty_awal) + $item->qty_awal) : $item->qty_awal }}"
														data-max="{{ ($item->in - $item->out) }}"
														data-qty="{{ $item->qty_awal > ($item->in - $item->out) ? ((($item->in - $item->out) - $item->qty_awal) + $item->qty_awal) : $item->qty_awal }}"
													/>
												  	<span class="input-group-addon">{{ $item->nm_satuan }}&nbsp;&nbsp;</span>
												</div>
											</td>
											<!-- Ket. -->
											<td>{{ $item->keterangan }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>

@endsection
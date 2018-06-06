@extends('Master.Template')

@section('meta')
<script type="text/javascript" src="{{ asset('/js/akunting/coa/coa.js') }}"></script>

@endsection

@section('title')
Chart Of Account 
@endsection

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="grid simple">
			<div class="grid-title no-border">

				<!-- <a href="{{ url('coa/add') }}"><button type="button" class="btn btn-primary">Tambah Group</button></a>  -->
				<a href="{{ url('coa/ledger') }}"><button type="button" class="btn btn-info">Tambah Ledger</button></a> 

				<div class="tools">
					<a href="javascript:;" class="collapse"></a> 
					<a href="javascript:;" class="reload"></a>
				</div>
			</div>

			<div class="grid-body no-border">
				<table class="table">
					<thead>
						<tr>
							<th colspan="3"><a href="javascript:;">Account Name</a></th>
							<th align=""><a href="javascript:;">Type</a></th>
							<th align=""><a href="javascript:;">Normal</a></th>
							<!-- th><a href="javascript:;">Saldo Awal</a></th -->
							<th ></th>
						</tr>
					</thead>

					<tbody>
						<?php 
						$no = 1;
						$head = '';
						$tree = '';
						$akhir = '';
						$fo ='';
						$ledger = '';
						?>
						@if(count($items) > 0)
						@foreach($items as $item) <!-- 1 -->
							@if($head != $item->nm_coa && $item->parent_id == 0)
							<tr style="background: #FEF59E; margin-top:50px;">
								<td colspan="3"><strong>{{ $item->kode }} &nbsp; &nbsp;{{ $item->nm_coa }} </strong> </td>
								<td colspan="4">
									<button type="button" class="btn btn-danger btn-xs btn-mini">Group</button>
								</td>
							</tr>

								@foreach($items as $row) <!-- 2 -->
								@if($tree != $row->nm_coa && $item->id_coa == $row->parent_id)
								<tr class="items">
									<td width="10"></td>
									<td colspan="2">
										<a href="{{ url('/coa/akun/' . $row->id_coa) }}">
											{{ $row->kode }}&nbsp;&nbsp; {{ $row->nm_coa }}
										</a>
									</td>
									
									@if($row->grup==1)
									<td><button type="button" class="btn btn-warning btn-xs btn-mini">{{ $grup[$row->grup]}}</button></td>
									<td></td>
									<td></td>
									@else
										<td><button type="button" class="btn btn-success btn-xs btn-mini">{{ $grup[$row->grup]}}</button></td>
										<td>{{ $normal[$row ->type] }}</td>
									<!-- td>
										Rp. {{ number_format($row->saldo_awal,0,'','')}},00
									</td -->
									@endif
									<td class="text-right">
										<div style="display" class="tbl-opsi">
											<small>
											@if(Auth::user()->permission > 2)
												@if($row->grup==1)
													<a href="{{ url('coa/editgrup/'.$row->id_coa) }}">Edit </a> 
												@else
													<a href="{{ url('coa/editleadger/'.$row->id_coa) }}">Edit </a> 
												@endif
											<!--  a href="" class=" hapusCoa" data-id="{{ $row->id_coa }}"> Hapus</a -->
		                                    @else
											@endif
											</small>
										</div>
									</td>
								</tr>
									

									<!-- link -->

									@foreach($items as $cd) <!-- kk -->
									@if($akhir != $cd->nm_coa && $cd->parent_id == $row->id_coa)
									<tr class="items">
										<td width="10"></td>
										<td colspan="2">
											<a href="{{ url('/coa/akun/' . $cd->id_coa) }}">
												&nbsp; &nbsp;&nbsp; &nbsp;{{ $cd->kode }} &nbsp;&nbsp;{{ $cd->nm_coa }}
											</a>
										</td>
										
											@if($cd->grup==1)
											<td>
											<button type="button" class="btn btn-warning btn-xs btn-mini">{{ $grup[$cd->grup]}}</button>
											</td>
											<td></td>
											<td></td>
											@else
											<td>
											<button type="button" class="btn btn-success btn-xs btn-mini">{{ $grup[$cd->grup]}}</button>
										</td>
										<td>{{ $normal[$cd ->type] }}</td>
										<!-- td>Rp. {{ number_format(($cd->saldo_awal),0,',','.')}},00 </td -->
										@endif
										<td class="text-right">
											<div style="display" class="tbl-opsi">
												<small>
												@if(Auth::user()->permission > 2)
													@if($cd->grup==1)
														<a href="{{ url('coa/editgrup/'.$cd->id_coa) }}">Edit </a> 
													@else
														<a href="{{ url('coa/editleadger/'.$cd->id_coa) }}">Edit </a> 
													@endif
												<!-- a href="" class="close hapusCoa" data-id="{{ $row->id_coa }}"> Hapus</a -->
			                                    @else
												@endif
												</small>
											</div>
										</td>
									</tr>
										
									
									<!-- link -->

									@foreach($items as $em) <!-- yy -->
									@if($fo != $em->nm_coa && $em->parent_id == $cd->id_coa)
									<tr class="items">
										<td width="10"></td>
										<td colspan="2">
											<a href="{{ url('/coa/akun/' . $em->id_coa) }}">
												&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; {{ $em->kode }}  &nbsp;&nbsp;{{ $em->nm_coa }} 
											</a>
										</td>
										<td>
										@if($em->grup==1)
											<button type="button" class="btn btn-warning btn-xs btn-mini">{{ $grup[$em->grup]}}</button>
										@else
										<button type="button" class="btn btn-success btn-xs btn-mini">{{ $grup[$em->grup]}}</button>
										</td>
										<td>{{ $normal[$em ->type] }}</td>
										<!-- td>Rp. {{ number_format(( $em->saldo_awal),0,',','.')}},00 </td -->
										@endif
										<td class="text-right">
											<div style="display" class="tbl-opsi">
												<small>
												@if(Auth::user()->permission > 2)
												@if($em->grup==1)
												<a href="{{ url('coa/editgrup/'.$em->id_coa) }}">Edit </a> 
												@else
												<a href="{{ url('coa/editleadger/'.$em->id_coa) }}">Edit </a> 
												@endif 
												<!-- a href="" class="close hapusCoa" data-id="{{ $row->id_coa }}"> Hapus</a -->
			                                    @else
												@endif
												</small>
											</div>
										</td>
									</tr>

									@foreach($items as $k) <!-- kk -->
									@if($fo != $em->nm_coa && $k->parent_id == $em->id_coa)
									<tr class="items">
										<td width="10"></td>
										<td colspan="2">
											&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;{{ $k->kode }}  &nbsp;&nbsp;{{ $k->nm_coa }}
										</td>
										<td>
										@if($k->grup==1)
											<button type="button" class="btn btn-warning btn-xs btn-mini">{{ $grup[$k->grup]}}</button>
										@else
										<button type="button" class="btn btn-success btn-xs btn-mini">{{ $grup[$k->grup]}}</button>
										</td>
										<td>{{ $normal[$row ->type] }}</td>
										<!-- td>Rp. {{ number_format(( $k->saldo_awal ),0,',','.')}},00 </td -->
										@endif
										<td class="text-right">
											<div style="display" class="tbl-opsi">
												<small>
												@if(Auth::user()->permission > 2)
												@if($k->grup==1)
													<a href="{{ url('coa/editgrup/'.$k->id_coa) }}">Edit </a> 
													@else
													<a href="{{ url('coa/editleadger/'.$k->id_coa) }}">Edit </a> 
													@endif
												<!-- a href="" class="close hapusCoa" data-id="{{ $row->id_coa }}"> Hapus</a -->
			                                    @else
												@endif
												</small>
											</div>
										</td>
									</tr>
						@endif
						@endforeach <!-- kk -->

						@endif
						@endforeach <!-- yy -->

								@endif
								@endforeach <!-- kk -->

						@endif
						@endforeach <!-- 2 -->

							@endif
							@endforeach <!-- 1 -->

						@else
						<tr>
							<td colspan="5"><i>Tidak Ada Data, Silakan melakukan penambahan data</i></td>
						</tr>	
						@endif
					</tbody>
				</table>

			</div>
		</div>
	</div>
</div>
@endsection
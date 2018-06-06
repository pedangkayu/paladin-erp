@extends('Master.Print')

@section('meta')
<style type="text/css">
	h3{
		font-weight: normal;
		margin: 0;
	}
</style>
@endsection

@section('content')

<center>
<h3><strong>Rekap Akun COa </strong></h3>

	</center>
	<br />
<table class="table table-bordered" cellspacing = "0">
	<thead>
		<tr>
			<th colspan="3">Account Name</th>
			<th align="">Type</th>
			<th align="">Normal</th>
			<th>Saldo Awal</th>
			<th>Balance</th>
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
								<td colspan="3">{{ $item->kode }} &nbsp; &nbsp;{{ $item->nm_coa }}</td>
								<td colspan="4">
								<b>Group</b>
								</td>
							</tr>

								@foreach($items as $row) <!-- 2 -->
								@if($tree != $row->nm_coa && $item->id_coa == $row->parent_id)
								<tr class="items">
									<td width="10"></td>
									<td colspan="2">
											{{ $row->kode }}&nbsp;&nbsp; {{ $row->nm_coa }}
									</td>
									
									@if($row->grup==1)
									<td colspan="4"><b>{{ $grup[$row->grup]}}</b></td>
									@else
										<td><b>{{ $grup[$row->grup]}}</b></td>
										<td>{{ $normal[$row ->type] }}</td>
									<td>
										Rp .{{ number_format($row->saldo_awal,0,'','')}},00
									</td>
									<td>
										Rp .{{ number_format($row->balance,0,'','')}},00
									</td>
									@endif
								</tr>
									

									<!-- link -->

									@foreach($items as $cd) <!-- kk -->
									@if($akhir != $cd->nm_coa && $cd->parent_id == $row->id_coa)
									<tr class="items">
										<td width="10"></td>
										<td colspan="2">
												&nbsp; &nbsp;&nbsp; &nbsp;{{ $cd->kode }} &nbsp;&nbsp;{{ $cd->nm_coa }}
										</td>
										
											@if($cd->grup==1)
											<td colspan="4"><b>{{ $grup[$cd->grup]}}</b></td>
											@else
											<td>
											<b>{{ $grup[$cd->grup]}}</b>
										</td>
										<td>{{ $normal[$cd ->type] }}</td>
										<td>Rp .{{ number_format(($cd->saldo_awal),0,',','.')}},00</td>
										<td>Rp .{{ number_format(($cd->balance),0,',','.')}},00</td>
										@endif
									</tr>
										
									
									<!-- link -->

									@foreach($items as $em) <!-- yy -->
									@if($fo != $em->nm_coa && $em->parent_id == $cd->id_coa)
									<tr class="items">
										<td width="10"></td>
										<td colspan="2">
												&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; {{ $em->kode }}  &nbsp;&nbsp;{{ $em->nm_coa }} 
										</td>
										@if($em->grup==1)
										<td><b>{{ $grup[$em->grup]}}</b></td>
										<td></td>
										<td></td>
										<td></td>	
										@else
										<td><b>{{ $grup[$em->grup]}}</b></td>
										<td>{{ $normal[$em ->type] }}</td>
										<td>Rp .{{ number_format(( $em->saldo_awal),0,',','.')}},00 </td>
										<td>Rp .{{ number_format(( $em->balance),0,',','.')}},00 </td>
										@endif
									</tr>

									@foreach($items as $k) <!-- kk -->
									@if($fo != $em->nm_coa && $k->parent_id == $em->id_coa)
									<tr class="items">
										<td width="10"></td>
										<td colspan="2">
											&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;{{ $k->kode }}  &nbsp;&nbsp;{{ $k->nm_coa }}
										</td>
										@if($k->grup==1)
										<td colspan="4">
											<b>{{ $grup[$k->grup]}}</b>
										</td>
										
										@else
										<td><b>{{ $grup[$k->grup]}}</b></td>
										<td>{{ $normal[$row ->type] }}</td>
										<td>Rp .{{ number_format(( $k->saldo_awal ),0,',','.')}},00</td>
										<td>Rp .{{ number_format(( $k->balance ),0,',','.')}},00</td>
										@endif
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
@endsection
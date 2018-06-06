@extends('Master.Print')

@section('content')

	<div>
	
		<table class="table table-bordered" cellspacing="0">
			<thead>
				<tr>
					<th width="20%" >Kode </th>
					<th width="25%" >Nama Barang</th>
					<th width="10%" >Sisa</th>
					<th >REQ QTY</th>
					<th >REALISASI</th>
					<th >ACC QTY</th>
					<th width="10%" >Keterangan</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $item)
				<?php 
					$conver = Format::convertSatuan($item->id_item, $item->id_satuan, $item->id_satuan_barang);
				?>
					<tr>
						<td >{{ $item->kode }}</td>
						<td>{{ $item->nm_barang }}</td>
						<td class="text-right">{{ number_format($item->in - $item->out,0,',','.') }} {{ $item->satuan_barang }}</td>
						<td class="text-right">{{ number_format($item->qty_lg,1,',','.') }} {{ $item->nm_satuan }}</td>
						<td class="text-right">
							<a href="javascript:;" title="1 {{ $item->nm_satuan }} = {{ $conver }} {{ $item->satuan_barang }} x {{ $item->qty }} {{ $item->nm_satuan }}">
							{{ number_format($item->qty,0,',','.') }} {{ $item->satuan_barang }}
							</a></td>
						<td class="text-right">
						{{ $item->qty > ($item->in - $item->out) ? ((($item->in - $item->out) - $item->qty) + $item->qty) : $item->qty }} &nbsp;{{ $item->satuan_barang }}&nbsp;&nbsp;
						</td>
						<td></td>
					</tr>
					@endforeach
			</tbody>
		</table>
	</div>

@endsection
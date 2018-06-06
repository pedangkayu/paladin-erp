<html>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td >
<center>
		<h2 style="margin:0;"><center>LAPORAN PURCHASE ORDER</center>
</h2>
</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td >

Priode
		@if($req->waktu == 1)
			{{ Format::nama_bulan($req->bulan) }} {{ $req->tahun }}
		@else
			{{ Format::indoDate2($req->dari) }} - {{ Format::indoDate2($req->sampai) }}
		@endif

		</td>
</tr>
	<div>
		<table class="table table-bordered" cellspacing="0">
			<thead>
				<tr>
					<td rowspan="2" >No.</td>
					<td rowspan="2" class="text-middle">No. PRQ</td>
					<td rowspan="2" class="text-middle">No. PO</td>
					<td rowspan="2" class="text-middle">No. GR</td>
					<td rowspan="2" class="text-middle">Supplier</td>
					<td colspan="2" class="text-middle ">Barang</td>

					<td rowspan="2" class="text-middle">Qty</td>
					<td rowspan="2" class="text-middle">Harga/item</td>

					<td colspan="2" class="text-center">PO (%)</td>
					<td colspan="2" class="text-center">Item (%)</td>
					<td rowspan="2" class="text-middle">Total</td>
					<td rowspan="2" class="text-middle">Deadline</td>
				</tr>

				<tr>

					<td></td>
					<td></td>
					<td></td>
					<td></th>
					<td></td>
					<td>Kode</td>

					<td>Nama</td>
					
					<td></td>
					<td></td>
					<td>DISK</td>
					<td>PPN</td>
					<td>DISK</th>
					<td>PPN</td>

				</tr>
			</thead>

			<tbody>
				<?php $no = 1; ?>
				@forelse($items as $item)
					<?php
						$diskonitem	= ($item->harga * $item->diskon) / 100;
						$aftdiskon 	= $item->harga - $diskonitem;
						$ppnitem	= ($aftdiskon * $item->ppn) / 100;
						$pphitem	= ($aftdiskon * $item->pph) / 100;
						$totalitem 	= $aftdiskon + $ppnitem + $pphitem;

						$gdiskon 	= ($totalitem * $item->gdiskon) / 100;
						$gaftdisk	= $totalitem - $gdiskon;
						$gppn		= ($gaftdisk * $item->gppn) / 100;
						$gpph		= ($gaftdisk * $item->gpph) / 100;

						$grandtotal = ($gaftdisk + $gppn + $gpph) * $item->qty;
					?>
					<tr>
						<td>{{ $no }}</td>
						<td>{{ $item->no_prq }}</td>
						<td>{{ $item->no_po }}</td>
						<td>{{ $item->no_spbm }}</td>
						<td>{{ $item->nm_vendor }}</td>
						<td>{{$item->kode}}</td>
						<td>
							{{ $item->nm_barang }}
						</td>
						<td class="text-right">{{ number_format($item->qty,0,',','.') }} {{ $item->nm_satuan }}</td>
						<td class="text-right">{{ number_format($item->harga,0,',','.') }}</td>
						<td class="text-right">{{ number_format($item->gdiskon,0,',',',') }}</td>
						<td class="text-right">{{ number_format($item->gppn,0,',',',') }}</td>
						<td class="text-right">{{ number_format($item->diskon,0,',',',') }}</td>
						<td class="text-right">{{ number_format($item->ppn,0,',',',') }}</td>
						<td class="text-right">{{ number_format($grandtotal,0,',','.') }}</td>
						<td>{{ date('d/m/Y', strtotime($item->deadline)) }}</td>
					</tr>
					<?php $no++; ?>
				@empty
					<tr>
						<td colspan="14">Tidak ditemukan</td>
					</tr>
				@endforelse
</html>
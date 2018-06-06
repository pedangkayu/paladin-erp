@extends('Master.Print')

@section('meta')
<style type="text/css">
	h3{
		font-weight: normal;
		margin: 0;
	}
	.table-bordered tr td, .table-bordered tr th{
		border-right: none !important;
	}
</style>
@endsection
@section('content')

<center>
	<h3><strong>LAPORAN PENERIMAAN PENJUALAN</strong></h3>
	<h3><strong>
    Tanggal {{ Format::indoDate($req['tanggal']) }}, jam {{ $req['dari'] }} - {{ $req['sampai'] }}
  </strong></h3>
</center>
<br />

<table class="table table-bordered" cellspacing = "0">
		<thead>
			<tr>
				<th>Nama Kasir</th>
				<th>Tangal</th>
				<th>Kassa</th>
				<th>(A) Saldo Awal</th>
				<th>(B) Kembali</th>
				<th>(C) Pendapatan</th><!-- nilai ini harus sesuai dengan duit hasil filter nya -->
				<th>Status</th>
			</tr>
		</thead>

		<tbody>
			@forelse($kasir as $item)
				@if(!empty($req['id_shift_kasir']) > 0 && $req['id_shift_kasir'] == $item->id_shift_kasir)
				<tr>
					<td>{{ $item->nm_depan }} {{ $item->nm_belakang }}</td>
					<td>{{ Format::indoDate2($item->created_at) }}</td>
					<td>{{ $shifts[$item->shift] }}</td>
					<td>{{ number_format($item->saldo_awal,0,',','.') }}</td>
					<td>{{ number_format($item->saldo_kembali,0,',','.') }}</td>
					<td>{{ number_format($item->pendapatan_kassa,0,',','.') }}</td>
					<td><span class="label {{  $item->status ? 'label-important' : 'label-success' }}">{{ $status[$item->status] }}</span></td>
				</tr>
				@elseif(!empty($req['id_shift_kasir']) == 0)
				<tr>
					<td>{{ $item->nm_depan }} {{ $item->nm_belakang }}</td>
					<td>{{ Format::indoDate2($item->created_at) }}</td>
					<td>{{ $shifts[$item->shift] }}</td>
					<td>{{ number_format($item->saldo_awal,0,',','.') }}</td>
					<td>{{ number_format($item->saldo_kembali,0,',','.') }}</td>
					<td>{{ number_format($item->pendapatan_kassa,0,',','.') }}</td>
					<td><span class="label {{  $item->status ? 'label-important' : 'label-success' }}">{{ $status[$item->status] }}</span></td>
				</tr>
				@endif

				@empty
				<tr>
					<td colspan="7">Tidak ditemukan</td>
				</tr>
				@endforelse
		</tbody>
</table>

<?php $jumlahTotal = 0; ?>

<table class="table table-bordered" cellspacing = "0">
  <tr>
    <th>BANK</th>
    <th>FAKTUR</th>
    <th>PELANGGAN</th>
    <th>TANGGAL</th>
	<th>KASIR</th>
    <th>TOTAL</th>
  </tr>
@forelse($header as $header)

    <tr>
      <td>{{ strtoupper($header->keterangan) }}</td>
      <td>{{ $header->nomor_faktur }}</td>
      <td>{{ $header->nama_pasien }}</td>
      <td>{{ Format::indoDate2($header->tgl_kwitansi) }}</td>
	  <td>{{ $header->nm_depan }} {{ $header->nm_belakang }}</td>
      <td class="text-right">{{ number_format($header->jumlah,0,',','.') }}</td>
    </tr>
    <?php $total = 0; ?>
    @foreach($items[$header->keterangan] as $item)
      @if($total != 0)
      <tr>
        <td></td>
        <td>{{ $item->nomor_faktur }}</td>
        <td>{{ $item->nama_pasien }}</td>
        <td>{{ Format::indoDate2($item->tgl_kwitansi) }}</td>
		<td>{{ $item->nm_depan }} {{ $item->nm_belakang }}</td>
        <td class="text-right">{{ number_format($item->jumlah,0,',','.') }}</td>
      </tr>
      @endif
      <?php $total += $item->jumlah; ?>
    @endforeach
      <tr>
        <th class="text-right" colspan="5">TOTAL</th>
        <th class="text-right">{{ number_format($total,0,',','.') }}</th>
        <?php $jumlahTotal += $total; ?>
      </tr>
@empty
  <tr>
    <td colspan="5">
      Tidak ditemukan
    </td>
  </tr>
@endforelse

  <tr>
    <th class="text-right" colspan="5">JUMLAH TOTAL</th>
    <th class="text-right">{{ number_format($jumlahTotal,0,',','.') }}</th>
  </tr>

</table>

@endsection

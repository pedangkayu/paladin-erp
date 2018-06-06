@extends('Master.Print')
@section('meta')
@endsection
@section('content')
	
	<center><h3><strong>DETAIL TRANSAKSI PINJAMAN KARYAWAN</strong></h3></center>

	<table width="100%">
		<tr>
			<td width="65%" valign="top">
				<address>
					<h3 style="margin:0;"><strong>{{ $data->nd }} {{$data->nb}}</strong></h3>
					<div>NO. PINJAMAN #{{ $data->no_pinjaman }} </div>
					<div>Alamat:</div>
					<div>Telpon :{{$data->telp}} </div>
					
				</address>
				 
			</td>
			<td width="35%" valign="top">
				<table class="table table-bordered" cellspacing="0" cellpadding="3" width="100%">
					<tr>
						<td width="50%" class="bold"><strong>Tanggal Pengajuan</strong></td>
						<td width="50%" align="right">#{{ Format::indoDate($data->tanggal) }}</td>
					</tr>
					<tr>
						<td width="50%" class="bold"><strong>Acc Oleh :</strong></td>
						<td width="50%" align="right">#{{ $data->acc_depan}} {{$data->acc_belakang}}</td>
					</tr>
					<tr>
						<td width="50%" class="bold"><strong>Tanggal Acc :</strong></td>
						<td width="50%" align="right">##{{ Format::indoDate($data->tgl_approval) }}</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>

	<br />

	<table class="table table-bordered" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				
				<th width="15%">Nominal Pinjaman</th>
				<th width="15%">Sisa Pinjaman</th>
				<th width="15%">Mulai Pinjam</th>
				<th width="20%">Selesai Pinjaman</th>
				<th width="30%">Keterangan</th>
			</tr>
		</thead>

		<tbody>
			
                <?php $akhir =($data->nominal) - ($data->total_terbayar) ;
				  ?>
			<tr>
				<td>Rp. {{ number_format($data->nominal,0,',','.') }} 
				</td>
				 
				<td class="text-middle">Rp{{ number_format($data->nominal - $data->total_terbayar,0,',','.') }}</td>
				<td  class="text-middle">{{Format::indoDate2($data->start_time) }}  {{ Format::hari($data->start_time) }}</td>
				<td class="text-middle" align="right">{{Format::indoDate2($data->end_time)}}{{ Format::hari($data->end_time) }}</td>
				<td>{{$data->keterangan}}</td>
			</tr>
		</tbody>
	</table>
	<h4><strong>History Pembayaran</strong></h4>
	<table class="table table-bordered" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="20%">Tanggal</th>
				<th width="15%">Akun</th>
				<th width="15">Hutang Awal</th>
				<th width="15%">Sisa Hutang</th>
				<th width="15%">Jumlah Bayar</th>
				<th width="20%">Petugas</th>
				<th width="30%">Keterangan</th>
			</tr>
		</thead>

		<tbody>

			@forelse($log as $cek)
			<tr>
				<td>{{Format::indoDate2($cek->created_at) }}  {{ Format::hari($cek->created_at) }} {{ Format::jam($cek->created_at) }}</td>
				<td>
					@if($cek->nm_bank >  0)
						{{$cek->nm_bank}}
					@else
						Cash
					@endif
				</td>
				<td class="text-middle">Rp{{ number_format($cek->sisa_hutang + $cek->bayar,0,',','.') }}</td>
				<td>Rp. {{ number_format($cek->sisa_hutang,0,',','.') }}</td>
				<td class="text-right">Rp.{{ number_format($cek->bayar,0,',','.') }}</td>
				<td>{{$cek->nm_depan}} {{$cek->nm_belakang}}</td>
				<td>{{$cek->keterangan}}</td>
			</tr>
			@empty
			<tr>
				<td colspan="4">Tidak ditemukan</td>
			</tr>
			@endforelse
		</tbody>
	</table>
	
@endsection
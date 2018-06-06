@extends('Master.Print')

@section('meta')

@endsection

@section('content')

	<center><h2><strong>SLIP GAJI KARYAWAN</strong></h2></center>

	<table width="100%">
		<tr>
			<td width="65%" valign="top">
				<address>
					<strong> Nama karyawan</strong> : {{$data_karyawan->nm_depan}} &nbsp;{{$data_karyawan->nm_belakang}}
					<input type="hidden" name="id_karyawan" value={{$data_karyawan->id_karyawan}}>
					<p>
						<div class="data-karyawan">
							<strong> Departemen</strong> : {{$data_karyawan->nm_departemen}}
						</div>
					</p>
					<div>
						<strong> jabatan</strong> : {{$data_karyawan->nm_jabatan}}
					</div>
					<div class="data_karyawan">
						<strong>Profesi</strong>  : {{$data_karyawan->nm_profesi}}
					</div>
				</address>
				<p><em>""</em></p>
			</td>
			<td width="35%" valign="top">
				<table class="table table-bordered" cellspacing="0" cellpadding="3" width="100%">
					<tr>
						<td width="50%" class="bold">Total Pendapatan</td>
						<td width="50%" align="right">: Rp.{{ number_format($data_karyawan->total_pendapatan,0,',','.')}}</td>
					</tr>

					<tr>
						<td width="50%" class="bold">Total Potongan</td>
						<td width="50%" align="right">Rp.{{ number_format($data_karyawan->total_potongan,0,',','.')}}</td>
					</tr>
					<tr>
						<td width="50%" class="bold">Gajin Bersih</td>
						<td width="50%" align="right">Rp.{{ number_format($data_karyawan->sisa_gaji,0,',','.')}}</td>
					</tr>
					<tr>
						<td width="50%" class="bold">Priode</td>
						<td width="50%" align="right">2016/21</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br />
	<table class="table table-bordered" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="5%">No.</th>
				<th width="40%">Pendapatan</th>
				<th width="15%">Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php $no = 1; ?>
				@foreach($detail_gaji as $item)
				<tr>
					<td>{{$no}}</td>
					<td>{{ $item->nm_komponen_honor }}</td>
					<td>Rp. {{ number_format($item->nilai,0,',','.') }}</td>
				</tr>
				<?php $no++; ?>
				@endforeach
			</tr>
		</tbody>
	</table>
	@if($data_potongan->count() >0)
	<table class="table table-bordered" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th width="5%">No.</th>
				<th width="40%">Potongan</th>
				<th width="15%">Jumlah</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php $no = 1; ?>
				@foreach($data_potongan as $p)
				<tr>
					<td>{{$no}}</td>
					<td>{{ $p->nm_potongan }}</td>
					<td>Rp. {{ number_format($p->jumlah_potongan,0,',','.') }}</td>
				</tr>
				<?php $no++; ?>
				@endforeach
			</tr>
		</tbody>
	</table>
	@endif
	@if($data_casbon->count() >0)
		<div class="col-sm-6">
			<div class="grid simple">
				<div class="grid-title no-border">Potongan Casbon</div>
				<div class="grid-body no-border">
					<table class="table">
		                <thead>
		                    <tr>
		                        <th>Petongan </th>
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
@endsection

@extends('Master.Template')

@section('meta')
	 <!--  -->
<script type="text/javascript" src="{{ asset('js/deposit/index.js') }}"></script>
	<style type="text/css">
		td > .link{
			display: none;
		}
		table.daftar-skb tr:hover td .link{
			display: block;
		}
	</style>
@endsection

@section('title')
	Data saldo Pasien
@endsection

@section('content')
<div class="row">
		<div class="col-sm-9">
			<div class="grid simple">
				<div class="grid-title no-border">
				</div>
				<div class="grid-body no-border">

					<div class="table-responsive">
						<table class="table table-striped daftar-skb">
							<thead>
								<tr>
								    <th width="5%"  class="text-middle">No. </th>
									<th width="35%" class="text-middle">Nama</th>
									<th width="20%" class="text-middle">No.Reg</th>
									<th width="20%" class="text-middle">Saldo</th>
									<th width="20%" class="text-middle">Update</th>
								</tr>
				 
							</thead>
							<tbody class="alldeposit">
								
								<?php $no = $depo->currentPage() == 1 ? 1 : ($depo->perPage() + $depo->currentPage()) -1 ; ?>
								@forelse($depo as $item)
								<tr class="sr_{{ $item->id_deposit }}">
										<td>{{ $no }}</td>
										<td width="20%">
										{{ $item->nama_pasien}}
											<div class="link text-muted">
												<small>
														<a href="{{ url('/Deposit/transaksi/'.$item->id_deposit) }}">Lihat &middot; </a>
														<a href="{{url('/Deposit/createpengembalian/'.$item->id_deposit)}}">Kredit Deposit</a>
												</small>
											</div>
										</td>
										<td>
											<b>{{ $item->id_pasien_hc  }}</b>
											
											</td>
										<td>Rp. {{ number_format($item->saldo,0,',','.') }}</td>
										<td>{{Format::indoDate2($item->tanggal) }}</td>
										
									</tr>
									<?php $no++; ?>
							@empty
							<tr>
								<td colspan="6">Tidak ditemukan</td>
							</tr>
							@endforelse

							</tbody>
						</table>
					</div>

					<div class="text-right pagindeposit">
						{!! $depo->render() !!}
					</div>

				</div>
			</div>
		</div>

		<!-- halaman kanan layar -->
		<div class="col-sm-3">
		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border text-center">
				<div class="btn-group" style="width:100%;">
				<a href="{{ url('/Deposit/create') }}" class="btn btn-primary btn-block dropdown-toggle" >Tambah Saldo pasien <span class="caret"></span></a>
				</div>
			</div>
		</div>

		<div class="grid simple">
			<div class="grid-title no-border"></div>
			<div class="grid-body no-border">
				<div class="form-group">
					<label>No.Reg</label>
					<input type="text" name="id_pasien_hc" class="form-control">
				</div>
				<div class="form-group">
					<label>Nama</label>
					<input type="text" name="nama_pasien" class="form-control">
				</div>
				
				<div class="form-group">
					<label>Limit / Page</label>
					<select name="limit" class="form-control">
						<option value="5">5</option>
						<option value="10" selected="selected">10</option>
						<option value="50">50</option>
						<option value="100">100</option>
						<option value="500">500</option>
					</select>
				</div>

				<div class="form-group">
					<button class="btn btn-block btn-primary cariresep"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
		@endsection

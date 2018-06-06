@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/pengadaan/retur/skb.js') }}"></script>
	<script type="text/javascript">
		$(function(){
			$('[name="tanggal"]').datepicker({
				format : 'yyyy-mm-dd'
			});
			$('.tanggal-btn').click(function(){
				$('[name="tanggal"]').val('');
			});
		});
	</script>
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
	Surat Keluar Barang
@endsection

@section('content')
	
	<div class="row">
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4>{{ number_format($items->total(),0,',','.') }} surat <strong>ditemukan</strong></h4>
				</div>
				<div class="grid-body no-border">

					<div class="table-responsive">
						<table class="table table-striped daftar-skb">
							<thead>
								<tr>
									<th>No.</th>
									<th>No. Surat</th>
									<th>No. PMB/PMO</th>
									<th>Tipe</th>
									<th>Departemen</th>
									<th>Tanggal</th>
								</tr>
							</thead>
							<tbody class="content-skb">
								<?php $no = 1; ?>
								@forelse($items as $item)
									<tr>
										<td>{{ $no }}</td>
										<td>
											<div>{{ $item->no_skb }}</div>
											<div class="link text-muted">
												<small>
													[
														<a href="{{ url('/returgudang/create/' . $item->id_skb) }}">Proses</a>
														| <a href="{{ url('/skb/print/' . $item->id_skb) }}" target="_blank">Print</a>
													]
												</small>
											</div>
										</td>
										<td>{{ $item->no_spb }}</td>
										<td>{{ $item->tipe == 1 ? 'Obat' : 'Barang' }}</td>
										<td>{{ $item->nm_departemen }}</td>
										<td>
											{{ Format::indoDate($item->created_at) }}<br />
											<small class="text-muted">{{ Format::hari($item->created_at) }}, {{ Format::jam($item->created_at) }}</small>
										</td>
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

					<div class="text-right pagin-skb">
						{!! $items->render() !!}
					</div>
				</div>
			</div>

		</div>
		<div class="col-sm-3">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					Pengembalian hanya bisa dilakukan berdasarkan Permohonan barang
				</div>
			</div>
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					
					<div class="form-group">
						<label>No. Surat</label>
						<input type="text" name="no_skb" class="form-control">
					</div>

					<div class="form-group">
						<label>No. PMB/PMO</label>
						<input type="text" name="no_spb" class="form-control">
					</div>

					<div class="form-group">
						<label>Tanggal</label>
						<div class="input-group">
					      <input type="text" class="form-control" name="tanggal" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default tanggal-btn" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>

					<div class="form-group">
						<label>Limit / Page</label>
						<select name="limit" class="form-control">
							<option value="10">10</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="500">500</option>
						</select>
					</div>

					<div class="form-group">
						<button class="btn btn-block btn-primary cariskb"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>
			
			
		</div>
	</div>

@endsection
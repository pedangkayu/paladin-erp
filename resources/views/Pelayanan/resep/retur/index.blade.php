@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/resep/retur/index.js') }}"></script>
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
	
@endsection

@section('title')
	Retur Obat Resep
@endsection

@section('content')
	
	<div class="row">
		<!-- left -->
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4><!--   -->Retur Obat <strong>ditemukan</strong></h4>
				</div>
				<div class="grid-body no-border">
					
					<div class="table-responsive">
						<table class="table table-striped daftar-adj">
							<thead>
								<tr>
									<th width="5%" class="text-middle">No.</th>
									<th width="20%" class="text-middle">No Retur</th>
									<th width="20%" class="text-middle">NO Resep</th>
									<th width="30%" class="text-middle">Pasien</th>
									<th width="30%" class="text-middle">Tanggal Retur</th>
									<th width="20%" class="text-middle">Status Retur</th>

								</tr>
							</thead>
							<tbody class="item-retur">
							<?php $no = $items->currentPage() == 1 ? 1 : ($items->perPage() + $items->currentPage()) -1 ; ?>
								@forelse($items as $item)
									<tr>
										<td>{{$no}}</td>
										<td>{{$item->no_retur_resep}}
											<div class="link">
												<small>[
														<a target="_blank" href="{{ url('/resep/printresep/' . $item->id_retur_resep ) }}">Print</a>
													]
												</small>
											</div>
										</td>
										<td>{{$item->nomor_resep}}</td>
										<td>
											{{$item->nama_pasien}}
												<small class="text-muted"><br>
														{{$item->id_pasien_hc}}
												</small>
										</td>
										<td>{{ Format::indoDate($item->tanggal_retur) }} at {{ Format::hari($item->tanggal_retur) }}</td>
										<td>
											{{ $status[$item->status] }}
										</td>
									</tr>
								<?php $no++; ?>
								@empty
									<tr>
										<td colspan="4">Tidak ditemukan</td>
									</tr>
								@endforelse
								
							</tbody>
						</table>
					</div>

					<div class="text-right pagin">
						{!! $items->render() !!}
					</div>

				</div>
			</div>

		</div>

		<!-- right -->
		<div class="col-sm-3">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<!-- <div class="grid-body no-border">
					<a href="{{ url('/subgudang/select') }}" class="btn btn-block btn-primary">Buat Penyesuaian</a>
				</div> -->
			</div>
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<div class="form-group">
						<label for="no">No Retur</label>
						<input type="text" id="no_retur_resep" name="no_retur_resep" class="form-control">
					</div>
					<div class="form-group">
						<label for="no_resep">No Resep</label>
						<input type="text" name="no_resep" class="form-control">
					</div>
					<div class="form-group">
						<label for="id_pasien_hc">No registrasi</label>
						<input type="text" name="id_pasien_hc" class="form-control">
					</div>
					<div class="form-group">
						<label>Tanggal</label>
						<div class="input-group">
					      <input type="text" class="form-control" name="tanggal_retur" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default tanggal-btn" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>


					<div class="form-group">
						<label>Limit / Page</label>
						<select name="limit" class="form-control">
							<option value="5">5</option>
							<option value="10" selected>10</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="500">500</option>
						</select>
					</div>

					<div class="form-group">
						<butto class="btn btn-block btn-primary cari"><i class="fa fa-search"></i> Cari</button>
					</div>

				</div>
			</div>

		</div>

	</div>

@endsection
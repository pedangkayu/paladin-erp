@extends('Master.Template')

@section('meta')
	<script type="text/javascript" src="{{ asset('/js/akunting/akun/index.js') }}"></script>
@endsection

@section('title')
	<strong>( {{ $akun->kode }} ) {{ $akun->nm_coa }}</strong>
@endsection

@section('content')
	
	<div class="row">
		<!-- left -->
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th class"text-right">Debit</th>
									<th class"text-right">Kredit</th>
								</tr>
							</thead>

							<tbody class="logakun">
								@forelse($jurnal as $item)

								<tr>
									<td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
									<td>
										<a href="{{ url($item->link_slug) }}">{{ $item->deskripsi }}</a>
									</td>
									<td class"text-right">{{ number_format($item->debit,0,',','.') }}</td>
									<td class"text-right">{{ number_format($item->kredit,0,',','.') }}</td>
								</tr> 

								@empty
									<tr>
										<td colspan="5">Tidak ada transaksi</td>
									</tr>
								@endforelse

							</tbody>
						</table>

						<div class="pagin text-right">
							{!! $jurnal->render() !!}
						</div>

					</div>

				</div>
			</div>

		</div>

		<!-- right -->
		<div class="col-sm-3">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					<div class="form-group">
						<a href="{{ url('/coa/editleadger/' . $akun->id_coa) }}" class="btn btn-primary btn-block">Ubah Akun</a>
					</div>

					<div class="form-group">
						<a href="{{ url('/coa') }}" class="btn btn-primary btn-block">Kembali</a>
					</div>
				</div>
			</div>

			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					
					<div class="form-group">
						<label>Dari Tanggal</label>
						<div class="input-group">
					      <input type="text" class="form-control" name="dari" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default dari-btn" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>


					<div class="form-group">
						<label>Sampai Tanggal</label>
						<div class="input-group">
					      <input type="text" class="form-control" name="sampai" readonly="readonly">
					      <span class="input-group-btn">
					        <button class="btn btn-default sampai-btn" type="button"><i class="fa fa-trash"></i></button>
					      </span>
					    </div><!-- /input-group -->
					</div>

					<div class="form-group">
						<label>Limit / Page</label>
						<select name="limit" class="form-control">
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="500">500</option>
						</select>
					</div>

					<input type="hidden" value="{{ $akun->id_coa }}" name="id_coa">
					<button class="btn cari-logakun btn-primary btn-block" type="button">Cari</button>

				</div>
			</div>

		</div>
		
	</div>

@endsection
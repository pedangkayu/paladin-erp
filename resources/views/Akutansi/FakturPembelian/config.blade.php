@extends('Master.Template')

@section('meta')
	
@endsection

@section('title')
	Seting Akun Pembelian
@endsection

@section('content')
	
	<div class="row">
		<!-- left -->
		<div class="col-sm-8">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
				
				<form method="post" action="">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-sm-6">
							
							<div class="form-group">
								<label>Akun PPN</label>
								<select name="coa_ppn" style="width:100%;">
									{!! $coa_ppn !!}
								</select>
							</div>

							<div class="form-group">
								<label>Akun Adjustment / Penyesuaian Harga</label>
								<select name="coa_adjustment" style="width:100%;">
									{!! $coa_adjustment !!}
								</select>
							</div>

							<div class="form-group">
								<label>Akun Item Tambahan</label>
								<select name="coa_penambahan_item" style="width:100%;">
									{!! $coa_penambahan_item !!}
								</select>
							</div>

						</div>

						<div class="col-sm-6">
							
							<div class="form-group">
								<label>Akun Sebelum Dibayar</label>
								<select name="coa_jumlah_sebelum_dibayar" style="width:100%;">
									{!! $coa_jumlah_sebelum_dibayar !!}
								</select>
							</div>

							<div class="form-group">
								<label>Akun Pembayaran Cash</label>
								<select name="coa_pembayaran_cash" style="width:100%;">
									{!! $coa_pembayaran_cash !!}
								</select>
							</div>

							<div class="form-group">
								<label>&nbsp;</label>
								<button type="submit" class="btn btn-primary btn-block">Simpan</button>
							</div>

						</div>

					</div>
				</form>

				</div>
			</div>

		</div>

		<!-- right -->
		<div class="col-sm-4">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					
				<p>Silakan lakukan beberapa setingan untuk menentukan Akun yang terlibat dalam transaksi pembelian</p>

				</div>
			</div>

		</div>
		
	</div>

@endsection
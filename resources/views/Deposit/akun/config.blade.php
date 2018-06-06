@extends('Master.Template')

@section('meta')
	
@endsection

@section('title')
	Konfigurasi Akun Pembayaran Deposit (Biling)
@endsection

@section('content')
	
	<div class="row">
		<!-- left -->
		<div class="col-sm-9">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					
					<form method="post" action="">
						
						<div class="row">
							<div class="col-sm-6">
								
								<div class="form-group">
									<label for="coa_pembayaran_cash">Akun Pembayaran Cash</label>
									<select id="coa_pembayaran_cash" name="coa_pembayaran_cash" class="form-control">
										{!! $coa_pembayaran_cash !!}
									</select>
								</div>
								<div class="form-group">
									<label for="coa_deposit">Akun Coa Deposit</label>
									<select id="coa_deposit" name="coa_deposit" class="form-control">
										{!! $coa_deposit !!}
									</select>
								</div>
							</div>
							
						</div>

						<button class="btn btn-primary" type="submit">Simpan</button>
						{{ csrf_field() }}

					</form>

				</div>
			</div>

		</div>

		<!-- right -->
		<div class="col-sm-3">
			
			<div class="grid simple">
				<div class="grid-title no-border"></div>
				<div class="grid-body no-border">
					Silakan sesuaikan akun-akun di samping
				</div>
			</div>

		</div>
		
	</div>

@endsection
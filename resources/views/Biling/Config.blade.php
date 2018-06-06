@extends('Master.Template')
@section('meta')
@endsection

@section('title')
	Konfigurasi Akun Keuangan
@endsection

@section('content')

	<div class="row">
		<!-- left -->
		<div class="col-sm-9">
            
			<!-- setong #1 -->
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4 class="bold"><i class="fa fa-check-circle-o"></i> Konfigurasi Akun Billing (Pembayaran)</h4>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
						<a href="#grid-config" data-toggle="modal" class="config"></a>
						<a href="javascript:;" class="reload"></a>
						<a href="javascript:;" class="remove"></a>
					</div>
				</div>
				<div class="grid-body no-border">
					<form method="post" action="{{ url('biling/config') }}">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_sebelum_dibayar">Akun Sebelum Pembayaran</label>
									<select id="coa_sebelum_dibayar" name="coa_sebelum_dibayar" class="form-control">
										{!! $coa_sebelum_dibayar !!}
									</select>
								</div>

								<div class="form-group">
									<label for="coa_adjustment">Akun Adjustment (Penyesuaian)</label>
									<select id="coa_adjustment" name="coa_adjustment" class="form-control">
										{!! $coa_adjustment !!}
									</select>
								</div>

								<div class="form-group">
									<label for="coa_item_tambahan">Akun Penambahan Item</label>
									<select id="coa_item_tambahan" name="coa_item_tambahan" class="form-control">
										{!! $coa_item_tambahan !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_rawat_inap">Akun Rawat Inap</label>
									<select id="coa_rawat_inap" name="coa_rawat_inap" class="form-control">
										{!! $coa_rawat_inap !!}
									</select>
								</div>

								<div class="form-group">
									<label for="coa_saldo">Akun Penyimpanan Saldo Pasien</label>
									<select id="coa_saldo" name="coa_saldo" class="form-control">
										{!! $coa_saldo !!}
									</select>
								</div>

								<div class="form-group">
									<label for="coa_pendapatan_resep">Akun Pendapatan Resep</label>
									<select id="coa_pendapatan_resep" name="coa_pendapatan_resep" class="form-control">
										{!! $coa_pendapatan_resep !!}
									</select>
								</div>
							</div>
						</div>

					<button class="btn btn-primary btn-xs btn-mini">Simpan Perubahan</button>
					{{ csrf_field() }}
					</form>
				</div>
			</div>

            <!-- duek #2 -->
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4 class="bold"><i class="fa fa-check-circle-o"></i> Konfigurasi Akun Deposit Saldo Pasien</h4>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
						<a href="#grid-config" data-toggle="modal" class="config"></a>
						<a href="javascript:;" class="reload"></a>
						<a href="javascript:;" class="remove"></a>
					</div>
				</div>
				<div class="grid-body no-border">
						<div class="row">
							<form method="post" action="{{ url('biling/deposit') }}">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="coa_pembayaran_cash">Akun Penerimaan Pembayaran Saldo Secara Cash</label>
										<select id="coa_pembayaran_cash" name="coa_pembayaran_cash" class="form-control">
											{!! $coa_pembayaran_cash !!}
										</select>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<label for="coa_deposit">Akun Penyimpanan Saldo Pasien</label>
										<select id="coa_deposit" name="coa_deposit" class="form-control">
											{!! $coa_deposit !!}
										</select>
									</div>
								</div>
					      </div>
						<button class="btn btn-primary btn-xs btn-mini">Simpan Perubahan</button>
						{{ csrf_field() }}
						</form>
				</div>
			</div>

			<!-- telok #3 -->
			<div class="grid simple">
				<div class="grid-title no-border">
					<h4 class="bold"><i class="fa fa-check-circle-o"></i> Konfigurasi Akun Pinjaman Karyawan</h4>
					<div class="tools">
						<a href="javascript:;" class="collapse"></a>
						<a href="#grid-config" data-toggle="modal" class="config"></a>
						<a href="javascript:;" class="reload"></a>
						<a href="javascript:;" class="remove"></a>
					</div>
				</div>
				<div class="grid-body no-border">
						<div class="row">
							<form method="post" action="{{ url('biling/loan') }}">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="coa_pembayaran_cash_loan">Akun Bank </label>
										<select id="coa_pembayaran_cash_loan" name="coa_pembayaran_cash_loan" class="form-control">
											{!! $coa_pembayaran_cash_loan !!}
										</select>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<label for="coa_loan">Akun Pinjaman</label>
										<select id="coa_loan" name="coa_loan" class="form-control">
											{!! $coa_loan !!}
										</select>
									</div>
								</div>
					     </div>
					<button class="btn btn-primary btn-xs btn-mini">Simpan Perubahan</button>
					{{ csrf_field() }}
					</form>
				</div>
		</div>


		<!-- empak #4 -->
		<div class="grid simple">
			<div class="grid-title no-border">
				<h4 class="bold"><i class="fa fa-check-circle-o"></i> Konfigurasi Akun Pembelian</h4>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
					<a href="#grid-config" data-toggle="modal" class="config"></a>
					<a href="javascript:;" class="reload"></a>
					<a href="javascript:;" class="remove"></a>
				</div>
			</div>
			<div class="grid-body no-border">
					<div class="row">
						<form method="post" action="{{ url('biling/pembelian') }}">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_ppn_pembelian">Akun PPN Pembelian </label>
									<select id="coa_ppn_pembelian" name="coa_ppn_pembelian" class="form-control">
										{!! $coa_ppn_pembelian !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_adjustment_pembelian">Akun Adjustment (Penyesuaian) Pembelian</label>
									<select id="coa_adjustment_pembelian" name="coa_adjustment_pembelian" class="form-control">
										{!! $coa_adjustment_pembelian !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_jumlah_sebelum_dibayar_pembelian">Akun Jumlah Sebelum Bayar </label>
									<select id="coa_jumlah_sebelum_dibayar_pembelian" name="coa_jumlah_sebelum_dibayar_pembelian" class="form-control">
										{!! $coa_jumlah_sebelum_dibayar_pembelian !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_penambahan_item_pembelian">Akun Penambahan Item Pembelian</label>
									<select id="coa_penambahan_item_pembelian" name="coa_penambahan_item_pembelian" class="form-control">
										{!! $coa_penambahan_item_pembelian !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_pembayaran_cash_pembelian">Akun Pembayaran Cash </label>
									<select id="coa_pembayaran_cash_pembelian" name="coa_pembayaran_cash_pembelian" class="form-control">
										{!! $coa_pembayaran_cash_pembelian !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
                                           
								</div>
							</div>
					</div>

					<button class="btn btn-primary btn-xs btn-mini">Simpan Perubahan</button>
					{{ csrf_field() }}
					</form>
			</div>
		</div>


		<!-- lemak #5 -->
		<div class="grid simple">
			<div class="grid-title no-border">
				<h4 class="bold"><i class="fa fa-check-circle-o"></i> Konfigurasi Akun Pendapatan</h4>
				<div class="tools">
					<a href="javascript:;" class="collapse"></a>
					<a href="#grid-config" data-toggle="modal" class="config"></a>
					<a href="javascript:;" class="reload"></a>
					<a href="javascript:;" class="remove"></a>
				</div>
			</div>
			<div class="grid-body no-border">
					<div class="row">
						<form method="post" action="{{ url('biling/pendapatan') }}">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_pendapatan_lainnya">Akun Pendapatan Lainnya </label>
									<select id="coa_pendapatan_lainnya" name="coa_pendapatan_lainnya" class="form-control">
										{!! $coa_pendapatan_lainnya !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_adjustment_pendapatan">Akun Adjustment (Penyesuaian) Pendapatan</label>
									<select id="coa_adjustment_pendapatan" name="coa_adjustment_pendapatan" class="form-control">
										{!! $coa_adjustment_pendapatan !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_piutang">Akun Hutang Pendapatan </label>
									<select id="coa_piutang" name="coa_piutang" class="form-control">
										{!! $coa_piutang !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_ppn_pendapatan">Akun PPN Pendapatan</label>
									<select id="coa_ppn_pendapatan" name="coa_ppn_pendapatan" class="form-control">
										{!! $coa_ppn_pendapatan !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label for="coa_pembayaran_cash_pendapatan">Akun Pembayaran Cash </label>
									<select id="coa_pembayaran_cash_pendapatan" name="coa_pembayaran_cash_pendapatan" class="form-control">
										{!! $coa_pembayaran_cash_pendapatan !!}
									</select>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">

								</div>
							</div>
					</div>

					<button class="btn btn-primary btn-xs btn-mini">Simpan Perubahan</button>
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
					<i class="fa fa-check-circle-o"></i> Silahkan sesuaikan akun-akun di samping, agar pencatatan jurnal keuangan sesuai.
				</div>
			</div>

		</div>

	</div>

@endsection

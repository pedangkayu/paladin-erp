@extends('Master.Print')

@section('meta')
<style type="text/css">
	h3{
		font-weight: normal;
		margin: 0;
	}
	.table-bordered tbody tr td{
		border-right: none !important;
	}
</style>

@endsection

@section('content')

<center>
	<h3><strong>Rekap Aruskas Keuangan RSOS </strong></h3>
	<span>Periode
		 
		</span>
	</center>
	<br/>

	
	<h3>A. ARUS KAS DARI AKTIVITAS</h3>

			<table class="table table-bordered" cellspacing="0">
				<tr>
					<td>1. Laba Rugi setelah pajak</td>
					<td class="text-right">{{ empty($laba_rugi_setelah_pajak) ? '-' : number_format($laba_rugi_setelah_pajak,2,',','.') }}</td>
				</tr>

				<tr>
					<td>2. Penyusutan</td>
					<td class="text-right">-</td>
				</tr>

				<tr>
					<td>3. Laba bersih sebelum perubahan modal kerja</td>
					<td class="text-right">{{ empty(( $laba_rugi_setelah_pajak + $penyusutan )) ? '-' : number_format(( $laba_rugi_setelah_pajak + $penyusutan ),2,',','.') }}</td>
				</tr>

				<tr>
					<td>4. Perubahan Modal Kerja</td>
					<td class="text-right">-</td>
				</tr>

				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.1. Piutang Usaha</td>
					<td class="text-right">{{ empty($piutang_usaha) ? '-' : number_format($piutang_usaha,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.2. Persediaan</td>
					<td class="text-right">{{ empty($persediaan) ? '-' : number_format($persediaan,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.3. Hutang Usaha</td>
					<td class="text-right">{{ empty($hutang_usaha) ? '-' : number_format($hutang_usaha,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.4. Hutang Pemegang Saham</td>
					<td class="text-right">{{ empty($hutang_pemegang_saham) ? '-' : number_format($hutang_pemegang_saham,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.5. Hutang Pajak</td>
					<td class="text-right">{{ empty($hutang_pajak) ? '-' : number_format($hutang_pajak,2,',','.') }}</td>
				</tr>


				<tr>
					<td>Total</td>
					<td class="text-right">{{ number_format(($piutang_usaha + $persediaan + $hutang_usaha + $hutang_pemegang_saham + $hutang_pajak),2,',','.') }}</td>
				</tr>

			</table>



			<h3>B. ARUS KAS DARI AKTIVITAS INVESTASI</h3>

			<table class="table table-bordered" cellspacing="0">

				<tr>
					<td>1. Kenaikan / Penurunan Aktiva Teap</td>
					<td class="text-right">-</td>
				</tr>

				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.1. Tanah</td>
					<td class="text-right">{{ empty($tanah) ? '-' : number_format($tanah,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.2. Bangunan</td>
					<td class="text-right">{{ empty($bangunan) ? '-' : number_format($bangunan,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.3. Kendaraan</td>
					<td class="text-right">{{ empty($kendaraan) ? '-' : number_format($kendaraan,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.4. Inventaris Kantor</td>
					<td class="text-right">{{ empty($inventaris_kantor) ? '-' : number_format($inventaris_kantor,2,',','.') }}</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.5. Inventaris Medis</td>
					<td class="text-right">{{ empty($inventaris_medis) ? '-' : number_format($inventaris_medis,2,',','.') }}</td>
				</tr>
			</table>


			<h3>C. ARUS KAS DARI AKTIVITAS PENDANAAN</h3>

			<table class="table table-bordered" cellspacing="0">

				<tr>
					<td>1. Kenaikan / Penurunan Modal Disetor</td>
					<td class="text-right">-</td>
				</tr>

				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.1. Hutang Bank</td>
					<td class="text-right">-</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.2. Hutang Leasing</td>
					<td class="text-right">-</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.3. Hutang Bank</td>
					<td class="text-right">-</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.4. Hutang Leasing</td>
					<td class="text-right">-</td>
				</tr>


				<tr>
					<td>Kas Bersih dari Aktivitas Pendanaan</td>
					<td class="text-right">-</td>
				</tr>
			</table>

			<table class="table table-bordered" cellspacing="0">
				<tr>
					<td>
						<h3>D. BERSIH KAS DAN SETARA KAS</h3>
					</td>
					<td class="text-right"><h3>{{ number_format(($piutang_usaha + $persediaan + $hutang_usaha + $hutang_pemegang_saham + $hutang_pajak),2,',','.') }}</h3></td>
				</tr>


				<tr>
					<td>
						<h3>E. SALDO KAS DAN SETARA KAS AWAL BULAN</h3>
					</td>
					<td class="text-right"><h3>-</h3></td>
				</tr>


				<tr>
					<td>
						<h3>F. SALDO KAS DAN SETARA KAS AKHIR BULAN</h3>
					</td>
					<td class="text-right"><h3>{{ number_format(($piutang_usaha + $persediaan + $hutang_usaha + $hutang_pemegang_saham + $hutang_pajak),2,',','.') }}</h3></td>
				</tr>

			</table>

@endsection
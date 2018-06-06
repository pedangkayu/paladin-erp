@extends('Master.Template')

@section('csstop')
	<link href="{{ asset('/plugins/bootstrap-select2/select2.css') }}" rel="stylesheet" type="text/css" media="screen"/>
@endsection
@section('meta')
	<script src="{{ asset('/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/js/Laporan/Logistik/lps.js') }}"></script>
	<script type="text/javascript">
		$(function(){
			$('[name="gudang"]').select2();
		});
	</script>
	<style type="text/css">
		.table-responsive {
		    width: 100%;
		    height: 500px;
		    margin-bottom: 15px;
		    overflow-x: scroll;
		    overflow-y: scroll;
		    border: 1px solid #dddddd;
		    -ms-overflow-style: -ms-autohiding-scrollbar;
		    -webkit-overflow-scrolling: touch;
		  }
		  .table-responsive > .table {
		    margin-bottom: 0;
		  }
		  .table-responsive > .table > thead > tr > th,
		  .table-responsive > .table > tbody > tr > th,
		  .table-responsive > .table > tfoot > tr > th,
		  .table-responsive > .table > thead > tr > td,
		  .table-responsive > .table > tbody > tr > td,
		  .table-responsive > .table > tfoot > tr > td {
		    white-space: nowrap;
		  }
		  .table-responsive > .table-bordered {
		    border: 0;
		  }
		  .table-responsive > .table-bordered > thead > tr > th:first-child,
		  .table-responsive > .table-bordered > tbody > tr > th:first-child,
		  .table-responsive > .table-bordered > tfoot > tr > th:first-child,
		  .table-responsive > .table-bordered > thead > tr > td:first-child,
		  .table-responsive > .table-bordered > tbody > tr > td:first-child,
		  .table-responsive > .table-bordered > tfoot > tr > td:first-child {
		    border-left: 0;
		  }
		  .table-responsive > .table-bordered > thead > tr > th:last-child,
		  .table-responsive > .table-bordered > tbody > tr > th:last-child,
		  .table-responsive > .table-bordered > tfoot > tr > th:last-child,
		  .table-responsive > .table-bordered > thead > tr > td:last-child,
		  .table-responsive > .table-bordered > tbody > tr > td:last-child,
		  .table-responsive > .table-bordered > tfoot > tr > td:last-child {
		    border-right: 0;
		  }
		  .table-responsive > .table-bordered > tbody > tr:last-child > th,
		  .table-responsive > .table-bordered > tfoot > tr:last-child > th,
		  .table-responsive > .table-bordered > tbody > tr:last-child > td,
		  .table-responsive > .table-bordered > tfoot > tr:last-child > td {
		    border-bottom: 0;
		  }
	</style>
@endsection

@section('title')
	STOK AKHIR OBAT DAN BAHAN HABIS PAKAI
@endsection

@section('content')

	<form method="get" action="{{ url('/reportlogistik/lpbprint') }}" target="_blank">
		<div class="grid simple">
			<div class="grid-title no-border">
				<h4>Pencarian</h4>
			</div>
			<div class="grid-body no-border">
				
				<div class="form-group">
					<label>Gudang</label>
					<select name="gudang" multiple="" style="width:100%;">
						<option value="*">Pilih Semua Gudang</option>
						@foreach($items as $item)
						<option value="{{ $item->nm_gudang }}">{{ $item->nm_gudang }}</option>
						@endforeach
					</select>
				</div>

			
			<div class="grid-body no-border text-right">
				<button type="button" class="btn btn-primary btn-proses" data-loading-text="Loading...">Proses</button>
			</div>
		</div>
	</form>

	<div class="grid simple">
		<div class="grid-title no-border">
			<h4><span class="total">0</span> item <strong>ditemukan</strong></h4>
		</div>
		<div class="grid-body no-border">
			<div class="table-responsive result">
				
			</div>
			<div class="pagin text-center"></div>

		</div>
	</div>

@endsection
<!DOCTYPE html>
<html>
<head>
	<title>Document ERP</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/Print/print.css') }}">
	<script src="{{ asset('/plugins/jquery-1.8.3.min.js') }}" type="text/javascript"></script> 
	<script type="text/javascript" src="{{ asset('/js/print.js') }}"></script>
	<style>
		.page-break {
		    page-break-after: always;
		}
	</style>
	@yield('meta')
</head>
<body>
	<header>
		<img src="{{ asset('/img/logo-print.png') }}">
		<div class="alamat">
			ERP<br />
			NILEM RAYA 11, BANDUNG<br />
		</div>
	</header>
	<br />
	<section class="container">
		@yield('content')
	</section>
	<footer class="text-center btn-print">
		<hr />
		<button type="button" onclick="window.print();">Print Dokumen</button>
		<button type="button" onclick="window.close();">Keluar</button>
	</footer>


	<script type="text/javascript">
		var _base_url = '{{ url() }}';
	</script>
</body>
</html>
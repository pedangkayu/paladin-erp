@extends('Master.Template')

@section('meta')
	<!-- BEGIN PAGE LEVEL JS -->
	<script src="{{ asset('/plugins/raphael/raphael-min.js') }}"></script>
	<script src="{{ asset('/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/jquery-slider/jquery.sidr.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/jquery-numberAnimate/jquery.animateNumbers.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script> 
	<script src="{{ asset('/plugins/jquery-ricksaw-chart/js/d3.v2.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-ricksaw-chart/js/rickshaw.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-morris-chart/js/morris.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-easy-pie-chart/js/jquery.easypiechart.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.time.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.selection.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.animator.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.orderBars.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-sparkline/jquery-sparkline.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-easy-pie-chart/js/jquery.easypiechart.min.js') }}"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<script src="{{ asset('/js/charts.js') }}" type="text/javascript"></script>

	<link href="{{ asset('/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" media="screen"/>
	<link href="{{ asset('/plugins/jquery-slider/css/jquery.sidr.light.css') }}" rel="stylesheet" type="text/css" media="screen"/>
	<link rel="stylesheet" href="{{ asset('/plugins/jquery-ricksaw-chart/css/rickshaw.css') }}" type="text/css" media="screen">
	<link rel="stylesheet" href="{{ asset('/plugins/jquery-morris-chart/css/morris.css') }}" type="text/css" media="screen">
@endsection

@section('title')
	RS ONKOLOGI SURABAYA 
@endsection

@section('content')

<div class="row">
 
</div>
	
    

@endsection
@extends('Master.Template')

@section('meta')
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.time.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.selection.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.animator.min.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.orderBars.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.categories.js') }}"></script>
	<script src="{{ asset('/plugins/jquery-flot/jquery.flot.tooltip.js') }}"></script>
@endsection

@section('title', 'Ikhtisar')

@section('content')
	@widget('Keuangan')
@endsection

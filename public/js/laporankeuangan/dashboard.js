$(function(){

	// RationR Chart
	setChartRation = function(data){
		var data_roa = [];
		for(var i =0; i < data.length; i++){
			data_roa[i] = [
				data[i].tahun,
				data[i].roa
			];
		}
		var data_format = {
			color : '#57A4F4',
			data : data_roa,
	        label: 'Return on Asset'
		};

		$.plot("#placeholder", [ data_format ], {
			series: {
				lines: { show: true },
				points: { show: true }
			},
			grid: {
				borderColor: null,
				hoverable: true //IMPORTANT! this is needed for tooltip to work
			},
			yaxis:{
                tickFormatter: function(val, axis) {
					return val.toLocaleString() + '%';
			    },
			},
			xaxis : {
				mode : "categories"
			},
			tooltip: {
				show: true,
				content: "%s : Tahun:%x.0, persen: %y",
				shifts: {
					x: -60,
					y: 25
				}
			}
		});
	}

	// Rugi laba chart
	setChartRugiLaba = function(data){
		var data_gl = [];
		for(var i =0; i < data.length; i++){
			data_gl[i] = [
				data[i].tahun,
				data[i].rugi_laba
			];
		}
		var data_format = {
			color : '#57A4F4',
			data : data_gl,
	        label: 'Rugi Laba'
		};

		$.plot("#rugilaba", [ data_format ], {
			series: {
				lines: { show: true },
				points: { show: true }
			},
			grid: {
				borderColor: null,
				hoverable: true //IMPORTANT! this is needed for tooltip to work
			},
			yaxis:{
                tickFormatter: function(val, axis) {
					return 'RP ' + val.toLocaleString();
			    },
			},
			xaxis : {
				mode : "categories"
			},
			tooltip: {
				show: true,
				content: "%s : Tahun:%x.0, total: Rp %y",
				shifts: {
					x: -60,
					y: 25
				}
			}
		});
	}


	// Rugi laba chart bulanan
	setChartRugiLabaBln = function(data){
		var data_gl = [];
		for(var i =0; i < data.length; i++){
			data_gl[i] = [
				data[i].bulan,
				data[i].rugi_laba
			];
		}
		var data_format_bar = {
			// color : '#57A4F4',
			data : data_gl,
	        label: 'Rugi Laba',
	        bars: {
				show: true,
				barWidth: 0.6,
				align: "center"
			}
		};

		var data_format_line = {
			color : '#57A4F4',
			data : data_gl,
	        lines : {
				show: true,
				barWidth: 0.6,
				align: "center"
			},
			points: { show: true }
		};

		$.plot("#rlbln", [ data_format_bar, data_format_line ], {
			grid: {
				borderColor: null,
				hoverable: true //IMPORTANT! this is needed for tooltip to work
			},
			yaxis:{
                tickFormatter: function(val, axis) {
					return '' + val.toLocaleString();
			    },
			},
			xaxis : {
				mode : "categories",
				tickLength: 0
			}
		});
	}


	$.getJSON(_base_url + '/dashboard/keuangan', {}, function(json){
		$('.total_hutang').attr('data-value', json.res.total_hutang);
		$('.total_hutang_jth_tempo').attr('data-value', json.res.total_hutang_jth_tempo);
		$('.total_piutang').attr('data-value', json.res.total_piutang);
		$('.total_piutang_jth_tempo').attr('data-value', json.res.total_piutang_jth_tempo);

		$('.animate-number').each(function(){
			$(this).animateNumbers($(this).attr("data-value"), true, parseInt($(this).attr("data-animation-duration")));
		})

		setChartRation(json.roas);
		setChartRugiLaba(json.rugilaba);
		setChartRugiLabaBln(json.rugilababln);


		// Detail rugi laba per bulan
		var rlbln_htm = '';
		for(var i =0; i < json.rugilababln.length; i++){
			rlbln_htm += '<tr>\
				<td>' + json.rugilababln[i].bulan + '</td>\
				<td class="text-right"><span class="pull-left">Rp</span> ' + number_format(json.rugilababln[i].rugi_laba,0,',','.') + '</td>\
			</tr>';

		}
		$('.detail-rlbln').html(rlbln_htm);
		console.log(json);

	});


});

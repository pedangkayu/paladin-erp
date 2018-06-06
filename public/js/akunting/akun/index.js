$(function(){

	$('[name="dari"]').datepicker({
		format : 'yyyy-mm-dd'
	});

	$('.dari-btn').click(function(){
		$('[name="dari"]').val('');
	});

		$('[name="sampai"]').datepicker({
		format : 'yyyy-mm-dd'
	});

	$('.sampai-btn').click(function(){
		$('[name="sampai"]').val('');
	});



	logakun = function(page){

		var $limit 	= $('[name="limit"]').val();
		var $dari 	= $('[name="dari"]').val();
		var $sampai = $('[name="sampai"]').val();
		var $id_coa = $('[name="id_coa"]').val();

		$('.logakun').css('opacity', .3);
		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/coa/logakun',
			data 	: {
				page 	: page,
				dari 	: $dari,
				sampai 	: $sampai,
				id_coa	: $id_coa,
				limit	: $limit				
			},
			cache 	: false,
			dataType : 'json',
			success : function(json){
				$('.logakun').html(json.content);
				$('.pagin').html(json.pagin);

				$('div.pagin > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link = $(this).attr('href');
					var $split = $link.split('?page=');
					var $page = $split[1];
					logakun($page);
				});

				$('.logakun').css('opacity', 1);
				onDataCancel();
			}
		});
	}

	$('div.pagin > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link = $(this).attr('href');
		var $split = $link.split('?page=');
		var $page = $split[1];
		logakun($page);
	});

	$('.cari-logakun').click(function(){
		logakun(1);
	});

});
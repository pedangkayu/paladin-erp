$(function(){

	allrawat = function(page){

		var $nama_pasien = $('[name="nama_pasien"]').val();
		var $id_antrian  = $('[name="id_antrian"]').val();
		var $id_pasien   = $('[name="id_pasien"]').val();
		var $No_trans    =$('[name="No_trans"]').val();
		var $limit       = $('[name="limit"]').val();

		$('.allrawat').css('opacity', .3);
		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/Rawatinap/allrawat',
				data 			: {
				page 			: page, 
				nama_pasien 	: $nama_pasien, 
				id_antrian 		: $id_antrian, 
				limit 			: $limit,
				id_pasien 		: $id_pasien,
				No_trans		:$No_trans
			},
			cache 	: false,
			dataType : 'json',
			success : function(json){
				$('.allrawat').html(json.items);
				$('.paginrawat').html(json.pagin);
				$('.total-data').html(json.total);

				$('div.paginrawat > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link = $(this).attr('href');
					var $split = $link.split('?page=');
					var $page = $split[1];
					allrawat($page);
				});
				// onDataCancel();
				$('.allrawat').css('opacity', 1);
			}
		});
	}

	$('div.paginrawat > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link = $(this).attr('href');
		var $split = $link.split('?page=');
		var $page = $split[1];
		allrawat($page);
	});

	$('.carirawat').click(function(){
		allrawat(1);
	});

	$('select').change(function(){
		allrawat(1);
	});
	$('[name="nama_pasien"]').keyup(function(e){
		if(e.keyCode == 13)
			allrawat(1);
	});
	$('[name="id_pasien"]').keyup(function(e){
		if(e.keyCode == 13)
			allrawat(1);
	});
	$('[name="id_antrian"]').keyup(function(e){
		if(e.keyCode == 13)
			allrawat(1);
	});
	$('[name="No_trans"]').click(function(){
		allrawat(1);
	});

});
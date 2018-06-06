$(function(){

	alljasa = function(page){

		var $nm_service = $('[name="nm_service"]').val();
		var $limit       = $('[name="limit"]').val();

		$('.alljasa').css('opacity', .3);
		$.ajax({
			type 	: 'GET',
			url 	: _base_url + '/mastertreatment/alljasa',
				data 			: {
				page 			: page, 
				nm_service 		: $nm_service, 
				limit 			: $limit
			},
			cache 	: false,
			dataType : 'json',
			success : function(json){
				$('.alljasa').html(json.items);
				$('.paginjasa').html(json.pagin);
				$('.total-data').html(json.total);

				$('div.paginjasa > ul.pagination > li > a').click(function(e){
					e.preventDefault();
					var $link = $(this).attr('href');
					var $split = $link.split('?page=');
					var $page = $split[1];
					alljasa($page);
				});
				// onDataCancel();
				$('.alljasa').css('opacity', 1);
			}
		});
	}

	$('div.paginjasa > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link = $(this).attr('href');
		var $split = $link.split('?page=');
		var $page = $split[1];
		alljasa($page);
	});

	$('.carijasa').click(function(){
		alljasa(1);
	});

	$('select').change(function(){
		alljasa(1);
	});
	$('[name="nm_service"]').keyup(function(e){
		if(e.keyCode == 13)
			alljasa(1);
	});
	

});
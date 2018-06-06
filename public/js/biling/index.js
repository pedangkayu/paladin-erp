$(function(){

	$('.tgl').datepicker({
		format : 'yyyy-mm-dd'
	});

	itemsfaktur = function(page){

		var $no_faktur 	= $('[name="no_faktur"]').val();
		var $duodate 	= $('[name="duodate"]').val();
		var $status 	= $('[name="status"]').val();
		var $tanggal 	= $('[name="tanggal"]').val();
		var $limit 		= $('[name="limit"]').val();


		$('.content-biling').css('opacity', .3);
		$.getJSON(_base_url + '/biling/itemsfaktur', {

			page 		: page,
			no_faktur	: $no_faktur,
			duodate 	: $duodate,
			status		: $status,
			tanggal 	: $tanggal,
			limit 		: $limit

		}, function(json){

			// console.log(json);

			$('.total-biling').html(json.total);
			$('.content-biling').html(json.content);
			$('.pagin').html(json.pagin);

			$('.content-biling').css('opacity', 1);

			onDataCancel();

			$('div.pagin > ul.pagination > li > a').click(function(e){
				e.preventDefault();
				var $link 	= $(this).attr('href');
				var $split 	= $link.split('?page=');
				var $page 	= $split[1];
				itemsfaktur($page);
			});
		});

	}


	$('div.pagin > ul.pagination > li > a').click(function(e){
		e.preventDefault();
		var $link 	= $(this).attr('href');
		var $split 	= $link.split('?page=');
		var $page 	= $split[1];
		itemsfaktur($page);
	});

	$('.btn-tanggal').click(function(){
		$('#tanggal').val('');
	});
	$('.btn-duodate').click(function(){
		$('#duodate').val('');
	});

	$('.cari').click(function(){
		itemsfaktur(1);
	});
	$('select').change(function(){
		itemsfaktur(1);
	});
	$('[name="no_faktur"]').keyup(function(e){
		if(e.keyCode == 13)
			itemsfaktur(1);
	});
});